<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class State extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'statemasters';
}
