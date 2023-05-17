<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Paymentmode extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'paymentmodemasters';
}
