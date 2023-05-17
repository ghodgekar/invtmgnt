<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Purchaseentry extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'openingstocktransactions';
}
