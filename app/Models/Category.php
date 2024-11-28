<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\ApiTrait;

class Category extends Model
{
    use HasFactory, ApiTrait;

    protected $allowIncluded = ['posts', 'posts.user', 'posts.images', 'posts.tags'];
    protected $allowFilter = ['id', 'name', 'slug'];
    protected $allowSort = ['id', 'name', 'slug'];

    //habilitar asignacion masiva
    protected $fillable = [
        'name',
        'slug'
    ];

    //relacion uno a muchos
    public function posts()
    {
        return $this->hasMany(Post::class);
    }
}
