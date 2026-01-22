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
        'user_agent',
        'ip_address',
        'device_info',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    protected $primaryKey = 'id';
    protected $connection = 'mysql';

}
