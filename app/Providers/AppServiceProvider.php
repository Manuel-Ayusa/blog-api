<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Laravel\Passport\Passport;
use App\Models\Post;
use App\Observers\PostObserver;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Passport::enablePasswordGrant();

        Post::observe(PostObserver::class);

        Passport::tokensCan([
            'create-post' => 'Crear un nuevo post', 
            'read-post' => 'Leer un post', 
            'update-post' => 'Actualizar un post',
            'delete-post' => 'Eliminar un post',
            'create-category' => 'Crear una nuevo categoria',
            'read-category' => 'Leer una categoria',
            'update-category' => 'Actualizar una categoria',
            'delete-category' => 'Eliminar una categoria',
            'create-tag' => 'Crear una nuevo etiqueta',
            'read-tag' => 'Leer una etiqueta',
            'update-tag' => 'Actualizar una etiqueta',
            'delete-tag' => 'Eliminar una etiqueta'
        ]);

        Passport::setDefaultScope([
            'read-post',
            'read-category',
            'read-tag'
        ]);
    }
}
