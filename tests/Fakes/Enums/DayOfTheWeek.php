<?php

namespace Ireal\Tests\Fakes\Enums;

/**
 * A dummy int-backed enum that should be used for tests.
 *
 * @internal
 */
enum DayOfTheWeek: int
{
    case Monday = 1;
    case Tuesday = 2;
    case Wednesday = 3;
    case Thursday = 4;
    case Friday = 5;
    case Saturday = 6;
    case Sunday = 7;
}
