<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Broker extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'brokermasters';
}
