<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Manufacturer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'manufracturermasters';
}
