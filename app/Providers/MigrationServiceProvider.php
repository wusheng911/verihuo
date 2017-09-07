<?php

namespace App\Providers;

use App\Database\Migrations\Migrator;
use App\Database\Migrations\MigrationCreator;

class MigrationServiceProvider extends \Illuminate\Database\MigrationServiceProvider
{

    /**
     * Register the migration creator.
     *
     * @return void
     */
    protected function registerCreator()
    {
        $this->app->singleton('migration.creator', function($app)
        {
            return new MigrationCreator($app['files']);
        });
    }

    
    /**
     * Register the migrator service.
     *
     * @return void
     */
    protected function registerMigrator()
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $this->app->singleton('migrator', function ($app) {
            $repository = $app['migration.repository'];

            return new Migrator($repository, $app['db'], $app['files']);
        });
    }
}


