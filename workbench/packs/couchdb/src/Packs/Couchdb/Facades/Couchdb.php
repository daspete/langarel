<?php namespace Packs\Couchdb\Facades;

use Illuminate\Support\Facades\Facade;

class Couchdb extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'Couchdb'; }

}