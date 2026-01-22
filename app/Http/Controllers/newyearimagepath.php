<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class newyearimagepath extends Model
{
    use HasFactory;

    protected $table = 'newyearimagepath';

    protected $fillable = [
        'yearEvent',
        'siteEvent',
        'image_path',
        'category',
        'post_name',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    protected $primaryKey = 'id';
    protected $connection = 'mysql';



}
