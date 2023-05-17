<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Tax extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'taxmasters';
}
