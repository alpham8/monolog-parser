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
 * Class LineLogParser
 * @package Dubture\Monolog\Parser
 */
class LineLogParser implements LogParserInterface
{
    /**
     * @var array
     */
    protected $pattern = array(
        'default' => '/\[(?P<date>.*)\]\s(?P<logger>[\w-]+)\.(?P<level>\w+):\s(?P<message>[^\[\{]+)\s(?P<context>[\[\{].*[\]\}])\s(?P<extra>[\[\{].*[\]\}])/',
        'error'   => '/\[(?P<date>.*)\]\s(?P<logger>[\w-]+).(?P<level>\w+):\s(?P<message>(.*)+)\s(?P<context>[^\s]+)\s(?P<extra>[^\s]+)/'
    );


    /**
     * @param string $log
     * @param int    $days
     * @param string $pattern
     *
     * @return array
     */
    public function parse($log, $days = 1, $pattern = 'default')
    {
        if (!is_string($log) || strlen($log) === 0) {
            return array();
        }

        preg_match($this->pattern[$pattern], $log, $data);

        if (!isset($data['date'])) {
            return array();
        }

        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $data['date']);

        $array = array(
            'date'    => $date,
            'logger'  => $data['logger'],
            'level'   => $data['level'],
            'message' => $data['message'],
            'context' => json_decode($data['context'], true),
            'extra'   => json_decode($data['extra'], true)
        );

        if (0 === $days) {
            return $array;
        }

        if (isset($date) && $date instanceof \DateTime) {
            $d2 = new \DateTime('now');

            if ($date->diff($d2)->days < $days) {
                return $array;
            }
        }

        return array();
    }

    /**
     * @param string $name
     * @param string $pattern
     *
     * @throws \RuntimeException
     */
    public function registerPattern($name, $pattern)
    {
        if (!isset($this->pattern[$name])) {
            $this->pattern[$name] = $pattern;
        } else {
            throw new \RuntimeException("Pattern $name already exists");
        }
    }

    /**
     * @inheritdoc
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @inheritdoc
     */
    public function setPattern($pattern)
    {
        $this->pattern = $pattern;
    }

    /**
     * @inheritdoc
     */
    public function addPattern($name, $pattern)
    {
        $this->pattern[$name] = $pattern;
    }

    /**
     * @inheritdoc
     */
    public function removePattern($name)
    {
        unset($this->pattern[$name]);
    }
}
