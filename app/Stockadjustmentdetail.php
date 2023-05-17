<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Stockadjustmentdetail extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'stock_adj_det';
}
