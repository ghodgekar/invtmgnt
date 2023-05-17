<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'companymasters';
}
