<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Purchaseheader extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'pur_hdr';
}
