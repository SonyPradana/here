<?php

namespace Here\Contracts;

interface DumbInterface
{
    /**
     * dump information only.
     *
     * @return void
     */
    public function info();

    /**
     * Dump information and code snapshot.
     *
     * @param string|array<int|string, mixed>|false $var Addtion information to print
     *
     * @return void
     */
    public function dump($var = false);

    /**
     * dump all registered 'here'.
     *
     * @return void
     */
    public function dumpAll();

    /**
     * Dump information and count by group name.
     *
     * @param string $group
     *
     * @return void
     */
    public function count($group);

    /**
     * Count all avilable group.
     *
     * @return void
     */
    public function countAll();
}
