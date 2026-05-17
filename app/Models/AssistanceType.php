<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssistanceType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'jumlah_diterima', 'maksimal_penerima'];

    public function kriterias()
    {
        return $this->hasMany(Kriteria::class);
    }

    public function periodeBantuans()
    {
        return $this->hasMany(PeriodeBantuan::class);
    }
}
