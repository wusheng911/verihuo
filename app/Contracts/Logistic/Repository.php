<?php namespace App\Contracts\Logistic;

interface Repository {

    /**
     * Get the logistic info.
     * @param string $code logistic's company code see Logistic Model
     * @param string $no the logistic's serials number
     * @param bool $force force get from remote server
     */
    public function get($code, $no, $force = false);
}