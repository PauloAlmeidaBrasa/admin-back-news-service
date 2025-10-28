<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'subtitle', 
        'category',
        'text',
        'client_id'
    ];

    /**
     * Define relationship with Client model
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}