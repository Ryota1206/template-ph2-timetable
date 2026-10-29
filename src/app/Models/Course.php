<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model{
    public $timestamps =false;

    public function entries()
    {
        return $this->hasMany(TimetableEntry::class);
    }
}