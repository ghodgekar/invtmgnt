<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Subcategory extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'categorysubmasters';
}
