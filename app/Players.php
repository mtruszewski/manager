<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Team;

class Players extends Model
{
    protected $table = 'players';

    protected $fillable = [
        'name',
        'surname',
        'number',
        'age',
        'height',
        'weight',
        'foot',
        'speed',
        'stamina',
        'intelligence',
        'short_pass',
        'long_pass',
        'ball_control',
        'heading',
        'shooting',
        'tackling',
        'set_plays',
        'keeping',
        'experience',
        'form'
    ];
    
    function rollDiceForStats($arg)
    {
        $min = 1;
        $min_physicals = 40;

        if (age($arg) > 25) {
            $randNumber = rand(1, 60);
            $min_physicals = 80;
        }
        elseif (age($arg) > 21) {
            $randNumber = rand(1, 360);
            $min_physicals = 50;
        }
        else $randNumber = rand(1, 1000);

        if ($randNumber <= 10) $max = 100;
        elseif ($randNumber <= 60) $max = 90;
        elseif ($randNumber <= 160) $max = 80;
        elseif ($randNumber <= 360) $max = 70;
        elseif ($randNumber <= 560) $max = 60;
        else $max = 50;

        $range = array($min, $max, $min_physicals);

        return $range;
    }
}
