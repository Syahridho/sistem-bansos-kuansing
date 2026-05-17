<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriteria extends Model
{
    use HasFactory;

    protected $table = 'kriterias';

    protected $fillable = [
        'kode',
        'nama',
        'bobot',
        'jenis',
        'tipe_input',
        'opsi',
        'assistance_type_id',
    ];

    protected $casts = [
        'opsi' => 'array',
        'bobot' => 'decimal:4',
    ];

    public function getOpsiAttribute($value)
    {
        if (is_null($value)) {
            return null;
        }

        // If it's already an array, since it's cast as array, Eloquent will deserialize it automatically.
        $decoded = is_string($value) ? json_decode($value, true) : $value;
        if (!is_array($decoded)) {
            return null;
        }

        // For checkbox input, the format is a flat array of string options like: ['Option A', 'Option B']
        if ($this->tipe_input === 'checkbox') {
            return array_values($decoded);
        }

        // For pilihan (dropdown) or status inputs
        if (in_array($this->tipe_input, ['pilihan', 'status'])) {
            $formatted = [];
            foreach ($decoded as $key => $val) {
                // If it is in the standard format: [['label' => 'A', 'nilai' => 1], ...]
                // The item $val is an array containing 'label' and 'nilai' keys
                if (is_array($val) && isset($val['label'])) {
                    $formatted[] = [
                        'label' => $val['label'],
                        'nilai' => $val['nilai'] ?? 0,
                    ];
                } else {
                    // Otherwise, it is in the old associative format: ['A' => 1, 'B' => 5]
                    // Or possibly a numeric indexed array where $val is not an array (e.g. ['A', 'B'])
                    $formatted[] = [
                        'label' => is_numeric($key) ? $val : $key,
                        'nilai' => is_numeric($key) ? $key : $val,
                    ];
                }
            }
            return $formatted;
        }

        return $decoded;
    }

    public function assistanceType()
    {
        return $this->belongsTo(AssistanceType::class);
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}
