<?php

namespace Tests\Unit\Elo;

use App\Services\Elo\EloCalculator;
use PHPUnit\Framework\TestCase;

class EloCalculatorTest extends TestCase
{
    
    public function test_exchanged_points_is_null_in_same_rating_draw(): void
    {
        $elo = new EloCalculator();
        $this->assertEquals(0, $elo->getExchangedPoints(1000, 1000, 0));
    }

    public function test_exchanged_points_is_half_k_in_same_rating_win(): void
    {
        $elo = new EloCalculator();
        $this->assertEquals(40, $elo->getExchangedPoints(1000, 1000, 2));
    }

    public function test_exchanged_points_do_not_decrease_after_400_elo_diff(): void
    {
        $elo = new EloCalculator();
        $this->assertSame($elo->getExchangedPoints(1400, 1000, 2), $elo->getExchangedPoints(1800, 1000, 2));
    }
}
