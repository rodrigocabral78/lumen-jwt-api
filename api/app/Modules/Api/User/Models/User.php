<?php

namespace App\Modules\Api\User\Models;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'id', 'uuid', 'name', 'email', 'password', 'is_admin', 'is_active', 'created_by', 'updated_by',
    ];
}
