<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Encounter extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'raid_id'];

    public function raid()
    {
        return $this->belongsTo(Raid::class);
    }
}
