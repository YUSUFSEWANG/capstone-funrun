<?php

namespace Tests\Feature;

use App\Models\Pembayaran;
use App\Models\Pengaturan;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class PendaftaranTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Pengaturan::setBanyak([
            'status_pendaftaran' => 'buka',
            'kuota_peserta' => 100,
            'stok_jersey' => json_encode(['S' => 5, 'M' => 5, 'L' => 5, 'XL' => 5, 'XXL' => 0]),
        ]);
    }

    private function dataPeserta(array $ganti = []): array
    {
        return array_merge([
            'nama_lengkap' => 'Andi Saputra',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '1995-04-11',
            'no_hp' => '081243740109',
            'email' => 'andi@example.test',
            'asal_instansi' => 'SDN 1 Tomini',
            'alamat' => 'Jl. Trans Sulawesi, Tomini',
            'paket' => 'lengkap',
            'ukuran_jersey' => 'M',
            'kontak_darurat_nama' => 'Siti',
            'kontak_darurat_hp' => '081200000000',
            'setuju' => '1',
        ], $ganti);
    }

    /**
     * UploadedFile::fake()->create() menghasilkan berkas kosong, jadi isinya ditulis manual.
     */
    private function fileBukti(string $nama = 'bukti.jpg', string $mime = 'image/jpeg'): UploadedFile
    {
        $file = UploadedFile::fake()->create($nama, 200, $mime);
        file_put_contents($file->getPathname(), random_bytes(2048));

        return $file;
    }

    public function test_peserta_dapat_mendaftar_dan_mendapat_kode(): void
    {
        $this->post(route('pendaftaran.store'), $this->dataPeserta())
            ->assertRedirect();

        $peserta = Peserta::first();

        $this->assertNotNull($peserta);
        $this->assertSame('menunggu_bayar', $peserta->status);
        $this->assertSame(120000, $peserta->biaya);
        $this->assertStringStartsWith('FR26-', $peserta->kode_daftar);
    }

    public function test_biaya_tidak_dapat_dimanipulasi_dari_form(): void
    {
        $this->post(route('pendaftaran.store'), $this->dataPeserta(['biaya' => 1000]));

        $this->assertSame(120000, Peserta::first()->biaya);
    }

    public function test_ukuran_jersey_habis_ditolak(): void
    {
        $this->post(route('pendaftaran.store'), $this->dataPeserta(['ukuran_jersey' => 'XXL']))
            ->assertSessionHasErrors('ukuran_jersey');

        $this->assertSame(0, Peserta::count());
    }

    public function test_paket_hemat_tidak_butuh_jersey(): void
    {
        $this->post(route('pendaftaran.store'), $this->dataPeserta([
            'paket' => 'hemat',
            'ukuran_jersey' => null,
        ]))->assertRedirect();

        $peserta = Peserta::first();

        $this->assertSame(60000, $peserta->biaya);
        $this->assertNull($peserta->ukuran_jersey);
    }

    public function test_pendaftaran_ditutup_menolak_pengiriman(): void
    {
        Pengaturan::set('status_pendaftaran', 'tutup');

        $this->post(route('pendaftaran.store'), $this->dataPeserta());

        $this->assertSame(0, Peserta::count());
    }

    public function test_upload_bukti_mengubah_status(): void
    {
        $this->post(route('pendaftaran.store'), $this->dataPeserta());
        $peserta = Peserta::first();

        $this->post(route('pendaftaran.bukti', $peserta->kode_daftar), [
            'nama_pengirim' => 'Andi Saputra',
            'bank_pengirim' => 'BRI',
            'nominal' => 120000,
            'tanggal_transfer' => now()->format('Y-m-d'),
            'file_bukti' => $this->fileBukti(),
        ])->assertRedirect();

        $peserta->refresh();
        $berkas = Pembayaran::where('peserta_id', $peserta->id)->first();

        $this->assertSame('menunggu_verifikasi', $peserta->status);
        $this->assertSame('image/jpeg', $berkas->file_mime);
        $this->assertNotEmpty($berkas->file_isi);
    }

    public function test_file_bukti_selain_gambar_atau_pdf_ditolak(): void
    {
        $this->post(route('pendaftaran.store'), $this->dataPeserta());
        $peserta = Peserta::first();

        $this->post(route('pendaftaran.bukti', $peserta->kode_daftar), [
            'nama_pengirim' => 'Andi',
            'nominal' => 120000,
            'tanggal_transfer' => now()->format('Y-m-d'),
            'file_bukti' => UploadedFile::fake()->create('virus.php', 10),
        ])->assertSessionHasErrors('file_bukti');

        $this->assertSame('menunggu_bayar', $peserta->fresh()->status);
    }

    public function test_admin_dapat_memverifikasi_dan_peserta_mengunduh_tiket(): void
    {
        $this->post(route('pendaftaran.store'), $this->dataPeserta());
        $peserta = Peserta::first();

        $this->post(route('pendaftaran.bukti', $peserta->kode_daftar), [
            'nama_pengirim' => 'Andi Saputra',
            'nominal' => 120000,
            'tanggal_transfer' => now()->format('Y-m-d'),
            'file_bukti' => $this->fileBukti(),
        ]);

        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->patch(route('admin.peserta.verifikasi', $peserta))
            ->assertRedirect();

        $peserta->refresh();
        $this->assertSame('terverifikasi', $peserta->status);
        $this->assertSame($admin->id, $peserta->verified_by);

        $this->get(route('pendaftaran.tiket', $peserta->kode_daftar))
            ->assertOk()
            ->assertHeader('content-type', 'application/pdf');
    }

    public function test_tiket_tidak_dapat_diunduh_sebelum_terverifikasi(): void
    {
        $this->post(route('pendaftaran.store'), $this->dataPeserta());
        $peserta = Peserta::first();

        $this->get(route('pendaftaran.tiket', $peserta->kode_daftar))->assertForbidden();
    }

    public function test_halaman_admin_butuh_login(): void
    {
        $this->get(route('admin.peserta.index'))->assertRedirect(route('admin.login'));
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
    }

    public function test_bukti_pembayaran_hanya_dapat_dibuka_admin(): void
    {
        $this->post(route('pendaftaran.store'), $this->dataPeserta());
        $peserta = Peserta::first();

        $this->post(route('pendaftaran.bukti', $peserta->kode_daftar), [
            'nama_pengirim' => 'Andi Saputra',
            'nominal' => 120000,
            'tanggal_transfer' => now()->format('Y-m-d'),
            'file_bukti' => $this->fileBukti(),
        ]);

        $this->get(route('admin.peserta.bukti', $peserta))->assertRedirect(route('admin.login'));

        $this->actingAs(User::factory()->create())
            ->get(route('admin.peserta.bukti', $peserta))
            ->assertOk()
            ->assertHeader('content-type', 'image/jpeg');
    }

    public function test_cek_pendaftaran_menemukan_peserta(): void
    {
        $this->post(route('pendaftaran.store'), $this->dataPeserta());
        $peserta = Peserta::first();

        $this->post(route('cek.cari'), ['kata_kunci' => $peserta->kode_daftar])
            ->assertOk()
            ->assertSee($peserta->nama_lengkap);
    }

    public function test_link_grup_whatsapp_muncul_setelah_pendaftaran(): void
    {
        Pengaturan::set('link_grup_wa', 'https://chat.whatsapp.com/ABC123xyz');

        $this->post(route('pendaftaran.store'), $this->dataPeserta())
            ->assertSessionHas('tampilkan_grup', true);

        $this->get(route('pendaftaran.show', Peserta::first()->kode_daftar))
            ->assertOk()
            ->assertSee('https://chat.whatsapp.com/ABC123xyz', false);
    }

    public function test_link_grup_selain_whatsapp_ditolak(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->put(route('admin.pengaturan.update'), [
            'status_pendaftaran' => 'buka',
            'kuota_peserta' => 100,
            'pendaftaran_mulai' => '2026-09-21',
            'pendaftaran_selesai' => '2026-10-10',
            'link_grup_wa' => 'https://situs-penipu.test/grup',
            'stok' => ['S' => 1, 'M' => 1, 'L' => 1, 'XL' => 1, 'XXL' => 1],
        ])->assertSessionHasErrors('link_grup_wa');
    }
}
