<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FreeTestActivity extends Model
{
    use HasFactory;

      public function user() 
    {
        return $this->belongsTo(FreeTestActivity::class,'phone','mobile');
    }
}
