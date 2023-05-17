<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Itembarcode extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'itembarcodes';
}
