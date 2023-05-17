<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Login extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'logins';
}
