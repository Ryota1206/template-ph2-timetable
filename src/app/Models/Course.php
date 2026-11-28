<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model{
    public $timestamps =false;

    public function entries()
    {
        return $this->hasMany(TimetableEntry::class);
    }
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }
    public function users()
    {
        return $this->belongsToMany(User::class,'timetable_entries');
    }
    public function favoritedByUsers()
    {
        return $this->belongsToMany(User::class,'course_favorites');
    }
}