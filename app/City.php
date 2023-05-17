<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class City extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'citymasters';
}
