<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Jenssegers\Mongodb\Eloquent\Model;

class Reason extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'reasonmasters';
}
