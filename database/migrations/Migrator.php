<?php

namespace App\Database\Migrations;

use Illuminate\Support\Str;

class Migrator extends \Illuminate\Database\Migrations\Migrator
{
    /**
     * Resolve a migration instance from a file.
     *
     * @param  string  $file
     * @return object
     */
    public function resolve($file)
    {
        $fileParts = explode('_', $file);
        $class = Str::studly(implode(' ', array_slice($fileParts, 4))) . '_' . implode('', array_slice($fileParts, 0, 4));

        if (!class_exists($class)) {
            return parent::resolve($file);
        }

        return new $class;
    }
}