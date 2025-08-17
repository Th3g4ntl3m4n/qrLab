<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GeneratedQr extends Model
{
    protected $fillable = ['user_id','url','file_png','file_svg'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
