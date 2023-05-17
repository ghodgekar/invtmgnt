<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Customer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'customermasters';
}
