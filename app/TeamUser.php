<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TeamUser extends Model
{
    protected $table='team_users';

    public $timestamps = false;

    protected $fillable = [
        'team_id',
        'user_id',
        'role'
    ];
}
