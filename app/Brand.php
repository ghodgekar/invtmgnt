<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Brand extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'brandmasters';
}
