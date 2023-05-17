<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Purchasedetail extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pur_det';
}
