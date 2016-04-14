<?php

namespace spec\MrKoopie\WP_ajaxfilter;

use PhpSpec\ObjectBehavior;
use Mockery;

class configuratorSpec extends ObjectBehavior
{
	private $mockery_WP_wrapper;

	protected $default = [
						'form_id'		=> 'some_id',
						'field_name' 	=> 'some_field',
						'translation'	=> 'some translation',
						'taxonomy_id'	=> 'categories',
						'data_array'	=> ['key' => 'value'],
						'url'			=> 'http://some_url',
						'template'		=> 'your_ajax_template',
						'post_type'		=> 'some_post_type',
						'filter_data_array' => [ 'slug' => 'slug', 'label' => 'label'],
					];

	public function it_is_initializable()
	{
		$this->shouldHaveType('MrKoopie\WP_ajaxfilter\configurator');
	}

	/******************************************************************
	*                                                                 *
	*                       TESTING FIELD TYPES                       *
	*                                                                 *
	******************************************************************/

	public function it_can_add_the_field_type_checkbox()
	{
		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'translation' 	=> $this->default['translation'],
								'field_name'	=> $this->default['field_name'],
								'type'			=> 'checkbox'
							  ];

		// Set the checkbox
		$this->add_checkbox($this->default['translation'], $this->default['field_name'])
			 ->shouldReturn($this);

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	public function it_can_add_the_field_type_radiobutton()
	{
		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'translation' 	=> $this->default['translation'],
								'field_name'	=> $this->default['field_name'],
								'type'			=> 'radiobutton'
							  ];

		// Set the radiobutton
		$this->add_radiobutton($this->default['translation'], $this->default['field_name'])
			 ->shouldReturn($this);

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	public function it_can_add_the_field_type_dropdown()
	{
		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'translation' 	=> $this->default['translation'],
								'field_name'	=> $this->default['field_name'],
								'type'			=> 'dropdown'
							  ];

		// Set the dropdown
		$this->add_dropdown($this->default['translation'], $this->default['field_name'])
			 ->shouldReturn($this);

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	public function it_can_add_the_field_type_textfield()
	{
		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'translation' 	=> $this->default['translation'],
								'field_name'			=> $this->default['field_name'],
								'type'			=> 'text'
							  ];

		// Set the dropdown
		$this->add_textfield($this->default['translation'], $this->default['field_name'])
			 ->shouldReturn($this);

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	/******************************************************************
	*                                                                 *
	*                 TESTING SETTING CONFIG OPTIONS                  *
	*                                                                 *
	******************************************************************/

	public function it_can_load_data_from_taxonomy()
	{
		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'translation' 	=> $this->default['translation'],
								'field_name'	=> $this->default['field_name'],
								'type'			=> 'checkbox',
								'data_source'	=> 'taxonomy',
								'taxonomy_id'	=> $this->default['taxonomy_id']
							  ];

		// Set the checkbox
		$this->add_checkbox($this->default['translation'], $this->default['field_name'])
			 ->shouldReturn($this)
			 ->load_data_from_taxonomy($this->default['taxonomy_id']);

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	public function it_can_load_data_from_an_array()
	{
		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'translation' 	=> $this->default['translation'],
								'field_name'	=> $this->default['field_name'],
								'type'			=> 'checkbox',
								'data_source'	=> 'array',
								'filter_data'	=> $this->default['filter_data_array']
							  ];

		// Set the checkbox
		$this->add_checkbox($this->default['translation'], $this->default['field_name'])
			 ->shouldReturn($this)
			 ->load_data_from_an_array($this->default['filter_data_array']);

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	public function it_can_set_the_post_type()
	{
		$this->set_post_type($this->default['post_type']);

		$this->get_post_type()
			->shouldBe($this->default['post_type']);
	}

	public function it_can_set_the_post_method()
	{
		$this->set_method('post')
			 ->shouldReturn($this);

		// Set the expected configuration
		$expected_config = [
							'form_id' => $this->default['form_id'],
							'method' => 'post'
							];

		// Check if the configuration matches
		$this->get_config_settings()
			 ->shouldBe($expected_config);
	}

	public function it_can_set_the_get_method()
	{
		$this->set_method('get')
			 ->shouldReturn($this);

		// Set the expected configuration
		$expected_config = [
							'form_id' => $this->default['form_id'],
							'method' => 'get'
							];

		// Check if the configuration matches
		$this->get_config_settings()
			 ->shouldBe($expected_config);
	}

	public function it_can_not_set_an_invalid_method()
	{
		$this->shouldThrow('MrKoopie\WP_ajaxfilter\Exceptions\no_such_method_exists_exception')
			 ->during('set_method', ['invalid_method']);
			 
	}

	public function it_can_set_the_action()
	{
		$this->set_action($this->default['url'])
			 ->shouldReturn($this);

		// Set the expected configuration
		$expected_config = [
							'form_id' 	=> $this->default['form_id'],
							'action'	=> $this->default['url']
							];

		// Check if the configuration matches
		$this->get_config_settings()
			 ->shouldBe($expected_config);
	}

	/******************************************************************
	*                                                                 *
	*                   TESTING SETTING THE TEMPLATE                  *
	*                                                                 *
	******************************************************************/
	public function it_can_set_the_ajax_template()
	{
		$this->set_ajax_template($this->default['template']);


		$expected_config = [
								'form_id' 	=> $this->default['form_id'],
								'template'  => $this->default['template']
							];
		
		// Generate the HTML
		$this->get_config_settings()
			 ->shouldBe($expected_config);

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

	public function letGo()
	{
		Mockery::close();
	}

	/**
	 * Used for preloading a field. We do not need this for every test.
	 * 
	 * @return object $this object
	 */
	public function preload_one_checkbox()
	{
		return $this->add_checkbox($this->default['translation'], $this->default['field_name']);
	}
}
