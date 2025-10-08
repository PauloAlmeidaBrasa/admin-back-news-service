<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

     protected $table = 'client';

    protected $fillable = [
        'name',
        'address'
    ];

    // If you want to define relationships later:
    public function users()
    {
        return $this->hasMany(User::class);
    }
}