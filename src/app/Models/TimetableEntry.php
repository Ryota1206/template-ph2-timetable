<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimetableEntry extends Model
{
    protected $fillable =[
        'day',
        'period',
        'title',
        'room'
    ];
}
