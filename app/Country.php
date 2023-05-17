<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Country extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'countrymasters';
}
