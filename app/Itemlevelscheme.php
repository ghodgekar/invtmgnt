<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Itemlevelscheme extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'itemlevelschememasters';
}
