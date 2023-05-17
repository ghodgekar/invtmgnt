<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Stockadjustmentheader extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'stock_adj_hdr';
}
