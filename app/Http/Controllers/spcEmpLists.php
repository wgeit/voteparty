<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class spcEmpLists extends Model
{
    use HasFactory;

    protected $table = 'registerempnewyears';

    protected $fillable = [
        'id',
        'CodePin',
        'empcode',
        'empname',
        'empdiv',
        'empposition',
        'nickname',
        'TimeIn',
        'year',
        'type',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    protected $primaryKey = 'id';
    protected $connection = 'mysql';

}
