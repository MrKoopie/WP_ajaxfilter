<?php

namespace spec\MrKoopie\WP_ajaxfilter\Input;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mockery;

class checkboxSpec extends ObjectBehavior
{
    private $default = [
        'label_name'    => 'label',
        'field_name'     => 'field_name',
        'taxonomy_id'   => 'taxonomy_id'
    ];
    private $mockery_WP_wrapper;
    private $mockery_stub;

    function it_is_initializable()
    {
        $this->shouldHaveType('MrKoopie\WP_ajaxfilter\Input\Checkbox');
    }

    function it_can_generate_the_html_code()
    {
        /**
         * Prepare the taxonomy data
         */
        $tmp_taxonomy = new \stdClass();
        $tmp_taxonomy->term_id = 1;
        $tmp_taxonomy->name = 'Geen categorie';
        $tmp_taxonomy->slug = 'geen-categorie';
        $tmp_taxonomy->term_group = 0;
        $tmp_taxonomy->term_taxonomy_id = 1;
        $tmp_taxonomy->taxonomy = 'category';
        $tmp_taxonomy->description = null;
        $tmp_taxonomy->parent = 0;
        $tmp_taxonomy->count = 2;
        $tmp_taxonomy->filter = 'raw';

        $taxonomy[] = $tmp_taxonomy;

        
        
        $this->mockery_WP_wrapper->shouldReceive('get_terms')
            ->with($this->default['taxonomy_id'])
            ->andReturn($taxonomy)
            ->once();

        /**
         * Set the expected parameters
         */
        $checkbox_parameter_expected_arguments = [
            'field_name' => $this->default['field_name'],
            'value' => $tmp_taxonomy->slug,
            'label' => $tmp_taxonomy->name
        ];
        $expected_return_checkbox_parameter = 'expected_return_checkbox_parameter';

        /**
         * We do not need to test if MrKoopie\WP_ajaxfilter\stub works properly, as this is done in a different test
         */
        $this->mockery_stub
            ->shouldReceive('parse_stub')
            ->with('checkbox-parameter', $checkbox_parameter_expected_arguments)
            ->andReturn($expected_return_checkbox_parameter)
            ->once();

        $this->mockery_stub
            ->shouldReceive('parse_stub')
            ->with('checkbox', \Mockery::any())
            ->andReturn('end_value')
            ->once();

        $this->load_data_from_taxonomy($this->default['taxonomy_id']);

        $this->generate_html()
             ->shouldEqual("end_value\n");
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
        $this->mockery_stub = Mockery::mock('MrKoopie\WP_wrapper\stub');
        $this->beConstructedWith($this->default['label_name'], $this->default['field_name'], $this->mockery_WP_wrapper, $this->mockery_stub);
    }

    public function letGo()
    {
        Mockery::close();
    }
    
}

