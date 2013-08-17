<?php namespace Packs\Logger\Facades;

use Illuminate\Support\Facades\Facade;

class Logger extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'Logger'; }

}