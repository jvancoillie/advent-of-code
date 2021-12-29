<?php

namespace App\Puzzle\Year2020\Day20;

class Position
{
    public const FRONT = 'front';
    public const FRONT_ROTATE90 = 'front-rotate-90';
    public const FRONT_ROTATE180 = 'front-rotate-180';
    public const FRONT_ROTATE270 = 'front-rotate-270';

    public const BACK = 'back';
    public const BACK_ROTATE90 = 'back-rotate-90';
    public const BACK_ROTATE180 = 'back-rotate-180';
    public const BACK_ROTATE270 = 'back-rotate-270';

    public static function getPositions(): array
    {
        return [
            self::FRONT,
            self::FRONT_ROTATE90,
            self::FRONT_ROTATE180,
            self::FRONT_ROTATE270,
            self::BACK,
            self::BACK_ROTATE90,
            self::BACK_ROTATE180,
            self::BACK_ROTATE270,
        ];
    }
}
