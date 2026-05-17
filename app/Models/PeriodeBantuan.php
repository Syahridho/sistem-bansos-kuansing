<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeBantuan extends Model
{
    use HasFactory;

    protected $table = 'periode_bantuans';

    protected $fillable = [
        'judul',
        'assistance_type_id',
        'tanggal',
        'status',
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function assistanceType()
    {
        return $this->belongsTo(AssistanceType::class);
    }

    public function alternatifs()
    {
        return $this->hasMany(Alternatif::class);
    }
}
