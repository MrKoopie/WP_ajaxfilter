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
                        'taxonomy_id'   => 'some_taxonomy_id',
                        'field_name'    => 'some_field',
                        'translation'   => 'some translation'
					];

    function it_is_initializable()
    {
        $this->shouldHaveType('MrKoopie\WP_ajaxfilter\generator');

        // Should extend configurator
        $this->shouldBeAnInstanceOf('MrKoopie\WP_ajaxfilter\configurator');
    }

    function it_can_fail_when_rendering_without_any_data()
    {
    	$this->shouldThrow('MrKoopie\WP_ajaxfilter\Exceptions\no_data_to_render_exception')
    		 ->during('render');
    }

    function it_can_render()
    {
        $this->add_checkbox($this->default['translation'], $this->default['taxonomy_id'], $this->default['field_name']);

        // Call three times add_action
        $this->mockery_WP_wrapper
             ->shouldReceive('add_action')
             ->times(3);

        $this->render();
    }

    // function it_can_generate_html()
    // {
    //     $this->add_checkbox($this->default['translation'], $this->default['taxonomy_id'], $this->default['field_name']);
        
    //     $this->mockery_WP_wrapper
    //         ->shouldReceive('get_site_url')
    //         ->withNoArgs()
    //         ->once()
    //         ->shouldReceive('get_terms')
    //         ->with($this->default['taxonomy_id']);

    //     $this->html();
    // }

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

    /**
     * Process Mockery
     */
    public function letGo()
    {
        Mockery::close();
    }
}
