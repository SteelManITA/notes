<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SharedNotes extends Model
{
    protected $table = 'shared-notes';
    protected $fillable = ['user_id', 'note_id'];
}
