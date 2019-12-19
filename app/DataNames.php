<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataNames extends Model
{
    public $timestamps = false;
    protected $table = 'data_names';

    protected $fillable = [
        'name'
    ];
}