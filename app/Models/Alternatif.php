<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternatif extends Model
{
    use HasFactory;

    protected $table = 'alternatifs';

    protected $fillable = [
        'periode_bantuan_id',
        'nik',
        'nama',
        'alamat',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function periodeBantuan()
    {
        return $this->belongsTo(PeriodeBantuan::class);
    }

    public function penilaians()
    {
        return $this->hasMany(Penilaian::class);
    }
}
