<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Commonlist extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'commonlistmasters';
}
