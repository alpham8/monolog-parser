<?php

/*
 * This file is part of the monolog-parser package.
 *
 * (c) Robert Gruendler <r.gruendler@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Dubture\Monolog\Parser;

/**
 *
 * @author Robert Gruendler <r.gruendler@gmail.com>
 */
interface LogParserInterface
{
    /**
     * @param $log
     * @param $days
     * @param $pattern
     *
     * @return mixed
     */
    public function parse($log, $days, $pattern);

    /**
     * @return array
     */
    public function getPattern();

    /**
     * @param array $pattern
     *
     * @return void
     */
    public function setPattern($pattern);

    /**
     * @param string $name
     * @param string $pattern
     *
     * @return void
     */
    public function addPattern($name, $pattern);

    /**
     * @param string $name
     *
     * @return void
     */
    public function removePattern($name);
}
