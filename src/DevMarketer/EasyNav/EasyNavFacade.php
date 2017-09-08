<?php
namespace DevMarketer\EasyNav;
/**
 * This file is part of EasyNav,
 * Easy navigation tools for Laravel.
 *
 * @license MIT
 * @package EasyNav
 */

use Illuminate\Support\Facades\Facade;

class EasyNavFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'easynav';
    }
}
