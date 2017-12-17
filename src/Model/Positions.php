<?php
declare(strict_types=1);

namespace OurSociety\Model;

class Positions extends Model
{
    /**
     * @var self
     */
    private static $instance;

    public static function instance(): self
    {
        return self::$instance ?? self::$instance = new self();
    }
}
