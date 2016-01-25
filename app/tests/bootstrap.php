<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

// temporary fix for the Polyfill in symfony/polyfill
// to be removed when the original polyfill is fixed.
if (PHP_VERSION_ID < 50400 && !function_exists('class_uses')) {
    function class_uses($class, $autoload = true)
    {
        if (!is_object($class) && $autoload) {
            class_exists($class, true);
        }

        return array();
    }
}

require __DIR__.'/../bootstrap.php.cache';
require __DIR__.'/WebTestCase.php';
