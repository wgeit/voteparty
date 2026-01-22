<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class newyearvotes extends Model
{
    use HasFactory;

    protected $table = 'newyearvotes';
    protected $fillable =[
        'card_number',
        'image_id',
        'category',
        'voted_at',
        'created_at',
        'updated_at'

    ];
}
