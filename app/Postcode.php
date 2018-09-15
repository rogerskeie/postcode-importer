<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Postcode extends Model
{
    /**
     * @var string
     */
    protected $table = 'postcodes';

    /**
     * @var bool
     */
    public $incrementing = false;
}
