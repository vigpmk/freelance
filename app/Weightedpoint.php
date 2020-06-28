<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Weightedpoint extends Model
{
    protected $table='weightedpoint';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'dialsphone',
        'speaksomeone',
        'speakexistingclient',
        'forceno',
        'setappointmentnewprospect',
        'receiveQIorreferral',
        'givearefrral',
        'giveQIfacetoface',
        'firstmeetings',
        'strategicPartner',
        'closes'
    ];

    public function getWeightedpoint(){

      return Weightedpoint::first();

    }
}
