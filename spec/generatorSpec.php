<?php

namespace spec\MrKoopie\WP_ajaxfilter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class generatorSpec extends ObjectBehavior
{
	protected $default = [
						'field_name' 	=> 'some_field',
						'translation'	=> 'some translation',
					];

	public function it_is_initializable()
	{
		$this->shouldHaveType('MrKoopie\WP_ajaxfilter\generator');
	}

	public function it_can_add_a_field()
	{
		// Add the field
		$return_add_field 	= $this->add_field($this->default['translation'], $this->default['field_name'])
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

		// Set the checkbox via the general setter
		$this->set_as('checkbox')
			 ->shouldReturn($this);

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

		// Set the radiobutton via the general setter
		$this->set_as('radiobutton')
			 ->shouldReturn($this);

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
		
		// Set the dropdown via the general setter
		$this->set_as('dropdown')
			 ->shouldReturn($this);

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
		
		// Set the text via the general setter
		$this->set_as('text')
			 ->shouldReturn($this);

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

	// public function it_can_load_data_from_a_taxonomy()
	// {
	// 	// @todo
	// }

	// public function it_can_load_data_from_an_array()
	// {
	// 	// @todo
	// }


	/** Used for preloading. We can not do this for every test (it_can_add_a_field does not need this) */
	public function preload_one_field()
	{
		return $this->add_field($this->default['translation'], $this->default['field_name']);
	}
}
