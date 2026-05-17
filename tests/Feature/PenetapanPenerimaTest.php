<?php

namespace Tests\Feature;

use App\Models\Alternatif;
use App\Models\AssistanceType;
use App\Models\PeriodeBantuan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PenetapanPenerimaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seeding database with necessary config/setting elements if any
    }

    public function test_admin_can_toggle_period_status_to_close_and_lock_results()
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@spkbansos.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $type = AssistanceType::create([
            'name' => 'Bantuan Langsung Tunai',
            'maksimal_penerima' => 10,
            'jumlah_diterima' => 300000,
        ]);

        $periode = PeriodeBantuan::create([
            'judul' => 'BLT 2026 Tahap 1',
            'assistance_type_id' => $type->id,
            'tanggal' => now(),
            'user_id' => $admin->id,
            'status' => 'buka',
        ]);

        $this->assertEquals('buka', $periode->status);

        // Toggle status to closed
        $response = $this->actingAs($admin)
            ->patch(route('periode.toggle-status', $periode));

        $response->assertStatus(302);
        $this->assertEquals('tutup', $periode->fresh()->status);
    }

    public function test_operator_can_close_period_but_cannot_reopen_it()
    {
        $operator = User::create([
            'name' => 'Operator Dinas',
            'email' => 'operator@spkbansos.id',
            'password' => bcrypt('password'),
            'role' => 'operator',
        ]);

        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@spkbansos.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $type = AssistanceType::create([
            'name' => 'Bantuan Langsung Tunai',
            'maksimal_penerima' => 10,
            'jumlah_diterima' => 300000,
        ]);

        $periode = PeriodeBantuan::create([
            'judul' => 'BLT 2026 Tahap 1',
            'assistance_type_id' => $type->id,
            'tanggal' => now(),
            'user_id' => $admin->id,
            'status' => 'buka',
        ]);

        // Operator closes the period
        $response = $this->actingAs($operator)
            ->patch(route('periode.toggle-status', $periode));

        $response->assertStatus(302);
        $this->assertEquals('tutup', $periode->fresh()->status);

        // Operator tries to reopen the closed period - should be blocked
        $response2 = $this->actingAs($operator)
            ->patch(route('periode.toggle-status', $periode));

        $response2->assertStatus(403);
        $this->assertEquals('tutup', $periode->fresh()->status);
    }

    public function test_modifying_alternatif_is_blocked_when_period_is_closed()
    {
        $admin = User::create([
            'name' => 'Admin Utama',
            'email' => 'admin@spkbansos.id',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        $type = AssistanceType::create([
            'name' => 'Bantuan Langsung Tunai',
            'maksimal_penerima' => 10,
            'jumlah_diterima' => 300000,
        ]);

        $periode = PeriodeBantuan::create([
            'judul' => 'BLT 2026 Tahap 1',
            'assistance_type_id' => $type->id,
            'tanggal' => now(),
            'user_id' => $admin->id,
            'status' => 'tutup', // CLOSED / LOCKED
        ]);

        $alternatif = Alternatif::create([
            'periode_bantuan_id' => $periode->id,
            'nik' => '1234567890123456',
            'nama' => 'Warga Percobaan',
            'alamat' => 'Alamat Percobaan',
            'user_id' => $admin->id,
        ]);

        // Attempting to create should redirect with error
        $responseCreate = $this->actingAs($admin)
            ->get(route('alternatif.create', $periode));
        $responseCreate->assertRedirect(route('periode.show', $periode));
        $responseCreate->assertSessionHas('error', 'Periode bantuan ini telah ditutup.');

        // Attempting to store should redirect with error
        $responseStore = $this->actingAs($admin)
            ->post(route('alternatif.store', $periode), [
                'nik' => '9876543210987654',
                'nama' => 'Warga Baru',
            ]);
        $responseStore->assertRedirect(route('periode.show', $periode));
        $responseStore->assertSessionHas('error', 'Periode bantuan ini telah ditutup.');

        // Attempting to edit should redirect with error
        $responseEdit = $this->actingAs($admin)
            ->get(route('alternatif.edit', [$periode, $alternatif]));
        $responseEdit->assertRedirect(route('periode.show', $periode));
        $responseEdit->assertSessionHas('error', 'Periode bantuan ini telah ditutup.');

        // Attempting to update should redirect with error
        $responseUpdate = $this->actingAs($admin)
            ->put(route('alternatif.update', [$periode, $alternatif]), [
                'nik' => '1234567890123456',
                'nama' => 'Warga Terupdate',
            ]);
        $responseUpdate->assertRedirect(route('periode.show', $periode));
        $responseUpdate->assertSessionHas('error', 'Periode bantuan ini telah ditutup.');

        // Attempting to destroy should redirect with error
        $responseDestroy = $this->actingAs($admin)
            ->delete(route('alternatif.destroy', [$periode, $alternatif]));
        $responseDestroy->assertRedirect(route('periode.show', $periode));
        $responseDestroy->assertSessionHas('error', 'Periode bantuan ini telah ditutup.');

        // Attempting to import should redirect with error
        $responseImport = $this->actingAs($admin)
            ->post(route('periode.import', $periode), [
                'excel' => null,
            ]);
        $responseImport->assertRedirect(route('periode.show', $periode));
        $responseImport->assertSessionHas('error', 'Periode bantuan ini telah ditutup.');
    }
}
