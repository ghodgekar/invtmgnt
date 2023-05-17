<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Transactiondetail extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'stock_detail';
}
