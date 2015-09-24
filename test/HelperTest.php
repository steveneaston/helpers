<?php

use Carbon\Carbon;

class HelperTest extends PHPUnit_Framework_TestCase
{
    /**
     * @test
     */
    public function it_formats_a_date_period()
    {
        // Single day
        $from = new DateTime('2015-1-1 00:00:00');
        $to   = new DateTime('2015-1-1 05:00:00');
        $this->assertEquals('1st January 2015 12am &ndash; 5am', format_period($from, $to));

        $from = new DateTime('2015-1-1 00:35:00');
        $to   = new DateTime('2015-1-1 05:50:00');
        $this->assertEquals('1st January 2015 12:35am &ndash; 5:50am', format_period($from, $to));

        $from = new DateTime('2015-1-1 19:30:00');
        $to   = new DateTime('2015-1-2 03:30:00');
        $this->assertEquals('1st January 2015 7:30pm &ndash; 3:30am', format_period($from, $to));

        // Several Years
        $from = new DateTime('2000-1-1 00:00:00');
        $to   = new DateTime('2015-1-1 00:00:00');

        $this->assertEquals('1st January 2000 &ndash; 1st January 2015', format_period($from, $to));
        $this->assertEquals('1st January 2000 12am &ndash; 1st January 2015 12am', format_period($from, $to, true));

        // Several Months
        $from = new DateTime('2015-1-1 00:00:00');
        $to   = new DateTime('2015-2-1 00:00:00');
        $this->assertEquals('1st January &ndash; 1st February 2015', format_period($from, $to));
        $this->assertEquals('1st January 12am &ndash; 1st February 2015 12am', format_period($from, $to, true));

        // Single Month
        $from = new DateTime('2015-1-1 00:00:00');
        $to   = new DateTime('2015-1-5 00:00:00');
        $this->assertEquals('1st &ndash; 5th January 2015', format_period($from, $to));
        $this->assertEquals('1st January 12am &ndash; 5th January 2015 12am', format_period($from, $to, true));
    }
}
