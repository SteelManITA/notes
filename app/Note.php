<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Note extends Model
{
    protected $fillable = ['note', 'title', 'user_id'];
}
