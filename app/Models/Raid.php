<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Raid extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function encounters()
    {
        return $this->hasMany(Encounter::class);
    }
}
