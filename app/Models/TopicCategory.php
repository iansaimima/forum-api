<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TopicCategory extends Model
{
    protected $fillable = ['name'];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
