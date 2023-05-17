<?php

namespace App;

use Jenssegers\Mongodb\Eloquent\Model;

class Module extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'modulemasters';

    public function parent()
    {
        return $this->hasOne(Module::class, 'parent_module_code','module_code');
    }
}
