<?php

namespace spec\MrKoopie\WP_ajaxfilter;

use PhpSpec\ObjectBehavior;
use Mockery;

class generatorSpec extends ObjectBehavior
{
	private $prophet;
	private $mockery_WP_wrapper;

	protected $default = [
						'form_id'		=> 'some_id',
						'field_name' 	=> 'some_field',
						'translation'	=> 'some translation',
						'taxonomy_name'	=> 'categories',
						'data_array'	=> ['key' => 'value'],
						'url'			=> 'http://some_url',
					];

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

	public function it_is_initializable()
	{
		$this->shouldHaveType('MrKoopie\WP_ajaxfilter\generator');
	}

	public function it_can_add_a_field()
	{
		// Add the field
		$return_add_field     = $this->add_field($this->default['translation'], $this->default['field_name'])
								   ->shouldBe($this);

		
		$expected_mapped_fields[]    	= [
								'name' 			=> $this->default['field_name'],
								'translation' 	=> $this->default['translation']
							  ];

		// Check if the mapped fields are set correclty
		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}


	/******************************************************************
	*                                                                 *
	*                       TESTING FIELD TYPES                       *
	*                                                                 *
	******************************************************************/

	public function it_can_set_the_field_type_checkbox()
	{
		// Preload the required fields
		$this->preload_one_field();

		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'name' 			=> $this->default['field_name'],
								'translation' 	=> $this->default['translation'],
								'type'			=> 'checkbox'
							  ];

		// Set the checkbox
		$this->set_as_checkbox()
			 ->shouldReturn($this);

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	public function it_can_set_the_field_type_radiobutton()
	{
		// Preload the required fields
		$this->preload_one_field();

		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'name' 			=> $this->default['field_name'],
								'translation' 	=> $this->default['translation'],
								'type'			=> 'radiobutton'
							  ];

		// Set the radiobutton
		$this->set_as_radiobutton()
			 ->shouldReturn($this);

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	public function it_can_set_the_field_type_dropdown()
	{
		// Preload the required fields
		$this->preload_one_field();

		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'name' 			=> $this->default['field_name'],
								'translation' 	=> $this->default['translation'],
								'type'			=> 'dropdown'
							  ];

		// Set the dropdown
		$this->set_as_dropdown()
			 ->shouldReturn($this);

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	public function it_can_set_the_field_type_text()
	{
		// Preload the required fields
		$this->preload_one_field();

		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'name' 			=> $this->default['field_name'],
								'translation' 	=> $this->default['translation'],
								'type'			=> 'text'
							  ];

		// Set the dropdown
		$this->set_as_text()
			 ->shouldReturn($this);

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	/******************************************************************
	*                                                                 *
	*                     TESTING LOADING METHODS                     *
	*                                                                 *
	******************************************************************/

	public function it_can_load_data_from_a_taxonomy()
	{
		// Preload the required fields
		$this->preload_one_field();


		// Generate the return value
		$return_value_object = new \stdClass;
		$return_value_object->id = 0;
		$return_value_object->name = 'name';

		$return_value[] = $return_value_object;

		// Generate get_terms and set the return data.
		$this->mockery_WP_wrapper
			 ->shouldReceive('get_terms')
			 ->with($this->default['taxonomy_name'])
			 ->andReturn($return_value)
			 ->once();

		// Load the data
		$this->load_data_from_a_taxonomy($this->default['taxonomy_name'])
			 ->shouldReturn($this);

		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'name'               => $this->default['field_name'],
								'translation'        => $this->default['translation'],
								'data_source'        => 'taxonomy',
								'data_taxonomy_name' => $this->default['taxonomy_name'],
								'data_array'		 => [ 0 => 'name']
							  ];

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	public function it_can_load_data_from_an_array()
	{
		// Preload the required fields
		$this->preload_one_field();

		// Load the data
		$this->load_data_from_an_array($this->default['data_array'])
			 ->shouldReturn($this);

		// Set the expected settings
		$expected_mapped_fields[]    	= [
								'name'               => $this->default['field_name'],
								'translation'        => $this->default['translation'],
								'data_source'        => 'array',
								'data_array' 		 => $this->default['data_array']
							  ];

		$this->get_mapped_fields()
			 ->shouldBe($expected_mapped_fields);
	}

	/******************************************************************
	*                                                                 *
	*                 TESTING SETTING CONFIG OPTIONS                  *
	*                                                                 *
	******************************************************************/
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

	public function it_can_set_the_url()
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
	*                   TESTING GENERATING THE FORM                   *
	*                                                                 *
	******************************************************************/
	/**
	 * @todo  this
	 */
	public function it_can_generate_the_html_code_with_one_field()
	{
		// Preload the required field
		$this->preload_one_field()
			 ->set_as_checkbox();


		// Generate the HTML
		$this->generate_html();


		
	}


	/** Used for preloading. We can not do this for every test (it_can_add_a_field does not need this) */
	public function preload_one_field()
	{
		return $this->add_field($this->default['translation'], $this->default['field_name']);
	}
}
