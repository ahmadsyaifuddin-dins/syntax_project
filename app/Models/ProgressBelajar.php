<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgressBelajar extends Model
{
    protected $table = 'progress_belajar';

    protected $fillable = ['user_id', 'studi_kasus', 'step_sekarang', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
