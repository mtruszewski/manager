<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DataSurnames extends Model
{
    public $timestamps = false;
    protected $table = 'data_surnames';

    protected $fillable = [
        'surname'
    ];
}