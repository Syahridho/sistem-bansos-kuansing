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

    public function assistanceType()
    {
        return $this->belongsTo(AssistanceType::class);
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}
