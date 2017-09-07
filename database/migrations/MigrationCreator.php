<?php

namespace App\Database\Migrations;

class MigrationCreator extends \Illuminate\Database\Migrations\MigrationCreator
{
    protected function getClassName($name)
    {
        return parent::getClassName($name) . '_' . str_replace('_', '', $this->getDatePrefix());
    }
}