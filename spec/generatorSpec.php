<?php

namespace spec\MrKoopie\WP_ajaxfilter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mockery;

class generatorSpec extends ObjectBehavior
{
	private $mockery_WP_wrapper;

	protected $default = [
						'form_id'		=> 'some_id',
					];

    function it_is_initializable()
    {
        $this->shouldHaveType('MrKoopie\WP_ajaxfilter\generator');

        // Should extend configurator
        $this->shouldBeAnInstanceOf('MrKoopie\WP_ajaxfilter\configurator');
    }

    /******************************************************************
    *                                                                 *
    *                             VARIOUS                             *
    *                                                                 *
    ******************************************************************/
	/**
	 * Initialize the class
	 */
	public function let()
	{
		$this->mockery_WP_wrapper   = Mockery::mock('MrKoopie\WP_wrapper\WP_wrapper');
		$this->beConstructedWith($this->default['form_id'], $this->mockery_WP_wrapper);
	}
}
