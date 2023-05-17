<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Itemtax extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'itemtaxmasters';
}
