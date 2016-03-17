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


	/** Used for preloading. We can not do this for every test (it_can_add_a_field does not need this) */
	public function preload_one_field()
	{
		return $this->add_field($this->default['translation'], $this->default['field_name']);
	}
}
