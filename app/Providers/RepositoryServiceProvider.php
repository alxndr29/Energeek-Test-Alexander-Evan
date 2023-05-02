<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Interfaces;
use App\Repositories;
use Dotenv\Repository\RepositoryInterface;

class RepositoryServiceProvider extends ServiceProvider
{

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Interfaces\SkillsInterface::class, Repositories\SkiilsRepository::class);
        $this->app->bind(Interfaces\JobsInterface::class, Repositories\JobsRepository::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
};
