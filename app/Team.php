<?php

namespace App;

use Laravel\Spark\Team as SparkTeam;

class Team extends SparkTeam
{
    protected $table='teams';

    protected $fillable = [
        'owner_id',
        'name',
        'trial_ends_at'
    ];
}
