<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Participant extends Model
{
    //
    protected $fillable = [
        'full_name',
        'age',
        'email',
        'instagram_tag',
        'description',
        'video_path',
        'photo_path',
        'cv_path',
    ];
}
