<?php

namespace think\bit\facade;

use think\Facade;

/**
 * Class Hash
 * @method static bool|string create(string $password, array $options = [])
 * @method static bool check(string $password, string $hashPassword)
 * @package think\bit\facade
 */
final class Hash extends Facade
{
    protected static function getFacadeClass()
    {
        return 'hash';
    }
}