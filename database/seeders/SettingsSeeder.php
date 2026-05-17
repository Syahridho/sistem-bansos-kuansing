<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'key' => 'maintenance_mode',
                'value' => '0',
                'label' => 'Mode Maintenance',
                'group' => 'maintenance',
            ],
            [
                'key' => 'maintenance_message',
                'value' => 'Sistem sedang dalam pemeliharaan. Silakan coba beberapa saat lagi.',
                'label' => 'Pesan Maintenance',
                'group' => 'maintenance',
            ],
            [
                'key' => 'app_name',
                'value' => 'SPK Bansos',
                'label' => 'Nama Aplikasi',
                'group' => 'app_info',
            ],
            [
                'key' => 'app_description',
                'value' => 'Sistem Pendukung Keputusan Penerima Bantuan Sosial',
                'label' => 'Deskripsi Aplikasi',
                'group' => 'app_info',
            ],
            [
                'key' => 'app_logo',
                'value' => null,
                'label' => 'Logo Aplikasi',
                'group' => 'app_info',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                [
                    'value' => $setting['value'],
                    'label' => $setting['label'],
                    'group' => $setting['group'],
                ]
            );
        }
    }
}
