<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Brandmanufacturer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'parametermasters';
}
