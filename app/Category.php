<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Category extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'categorymasters';
}
