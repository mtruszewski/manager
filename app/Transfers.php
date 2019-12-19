<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Transfers extends Model
{
    protected $table = 'transfers';

    protected $fillable = [
        'player_id',
        'seller_id',
        'price'
    ];
}
