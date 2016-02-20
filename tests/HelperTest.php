<?php

use Carbon\Carbon;

class HelperTest extends \PHPUnit_Framework_TestCase
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

        $from = new DateTime('2016-02-13 17:33:00.000000');
        $to   = new DateTime('2016-02-22 04:59:00.000000');
        $this->assertEquals('13th February 5:33pm &ndash; 22nd February 2016 4:59am', format_period($from, $to, true));
    }

    /**
     * @test
     */
    public function it_checks_an_attribute_is_set_and_not_empty()
    {
        $data = [
            'filled' => 'biscuit',
            'empty'  => '',
            'null'   => null
        ];

        $this->assertTrue(set_not_empty($data, 'filled'));
        $this->assertFalse(set_not_empty($data, 'empty'));
        $this->assertFalse(set_not_empty($data, 'null'));
        $this->assertFalse(set_not_empty($data, 'non existant'));
    }

    /**
     * @test
     */
    public function it_gets_environmental_variables()
    {
        $this->assertNull(env('biscuit'));
        $this->assertEquals('hobnob', env('biscuit', 'hobnob'));

        $this->assertFalse(env('ENV_FALSE'));
        $this->assertTrue(env('ENV_TRUE'));
        $this->assertNull(env('ENV_NULL'));
        $this->assertEquals('', env('ENV_EMPTY'));
    }

    /**
     * @test
     */
    public function it_converts_strings_to_booleans()
    {
        $this->assertFalse(str_to_bool('false'));
        $this->assertFalse(str_to_bool('FALSE'));
        $this->assertFalse(str_to_bool(false));
        $this->assertTrue(str_to_bool('true'));
        $this->assertTrue(str_to_bool('TRUE'));
        $this->assertTrue(str_to_bool(true));

        $this->assertFalse(str_to_bool(['biscuits']));
    }

    /**
     * @test
     */
    public function it_gets_the_tax_week_from_a_date()
    {
        $this->assertEquals(1, tax_week(new DateTime('2016-4-6 00:00:00')));
        $this->assertEquals(1, tax_week(new DateTime('2016-4-12 00:00:00')));
        $this->assertEquals(2, tax_week(new DateTime('2016-4-13 00:00:00')));
        $this->assertEquals(53, tax_week(new DateTime('2016-4-5 00:00:00')));
    }
}
