<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Paymentincludeexclude extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'paymentinclexclmasters';
}
