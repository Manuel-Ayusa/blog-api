<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\ApiTrait;

class Tag extends Model
{
    use HasFactory, ApiTrait;

    protected $allowIncluded = ['posts', 'posts.user', 'posts.image', 'posts.tags'];
    protected $allowFilter = ['id', 'name'];
    protected $allowSort = ['id', 'name'];

    //habilitar asignacion masiva
    protected $fillable = [
        'name',
        'slug',
        'color'
    ];

    //relacion muchos a muchos
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
}
