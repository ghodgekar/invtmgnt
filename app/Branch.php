<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Branch extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'branchmasters';
}
