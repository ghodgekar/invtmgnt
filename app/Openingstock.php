<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Openingstock extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'openingstocktransactions';
}
