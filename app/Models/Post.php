<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Traits\ApiTrait;

class Post extends Model
{
    use HasFactory, ApiTrait;

    protected $allowIncluded = ['user', 'category', 'tags', 'image'];
    protected $allowFilter = ['id', 'name', 'slug', 'status', 'category_id', 'status', 'user_id'];
    protected $allowSort = ['id', 'name', 'slug'];

    const BORRADOR = 1;
    const PUBLICADO = 2;

    //asignacion masiva
    protected $fillable = [
        'name',
        'slug',
        'stract',
        'body',
        'status',
        'category_id',
        'user_id'
    ];

    //relacion uno a muchos inversa
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }    

    //relacion muchos a muchos
    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    //relacion uno a muchos polimorfica
    public function image()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
