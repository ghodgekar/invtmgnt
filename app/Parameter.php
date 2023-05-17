<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Parameter extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'parametermasters';
}
