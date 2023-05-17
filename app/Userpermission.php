<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Userpermission extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'userpermissionmasters';
}
