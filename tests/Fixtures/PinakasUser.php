<?php

namespace Mimisk\Pinakas\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class PinakasUser extends Model
{
    protected $table = 'pinakas_users';

    protected $guarded = [];

    public $timestamps = false;
}
