<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Vendor extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'vendormasters';
}
