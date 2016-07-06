<?php

namespace spec\MrKoopie\WP_ajaxfilter\Input;

use Mockery;
use PhpSpec\ObjectBehavior;

class textSpec extends ObjectBehavior
{
    private $default = [
        'label_name'     => 'label',
        'field_name'     => 'field_name',
        'value'          => 'value',
        'input_data'     => 'input_data'
    ];
    private $mockery_WP_wrapper;
    private $mockery_stub;

    public function it_is_initializable()
    {
        $this->shouldHaveType('MrKoopie\WP_ajaxfilter\Input\Text');
    }

    public function it_can_generate_the_html_code_without_input_data()
    {
        /*
         * Set the expected parameters
         */
        $text_parameter_expected_arguments = [
            'label'      => $this->default['label_name'],
            'field_name' => $this->default['field_name'],
            'value'      => '',
        ];

        /*
         * We do not need to test if MrKoopie\WP_ajaxfilter\stub works properly, as this is done in a different test
         */
        $this->mockery_stub
            ->shouldReceive('parse_stub')
            ->with('input-text', $text_parameter_expected_arguments)
            ->andReturn('end_value')
            ->once();

        $this->generate_html()
             ->shouldEqual("end_value\n");
    }

    public function it_can_generate_the_html_code_with_input_data()
    {
        /*
         * Set the expected parameters
         */
        $text_parameter_expected_arguments = [
            'label'      => $this->default['label_name'],
            'field_name' => $this->default['field_name'],
            'value'      => $this->default['input_data'],
        ];

        /*
         * We do not need to test if MrKoopie\WP_ajaxfilter\stub works properly, as this is done in a different test
         */
        $this->mockery_stub
            ->shouldReceive('parse_stub')
            ->with('input-text', $text_parameter_expected_arguments)
            ->andReturn('end_value')
            ->once();

        $input_data = [0 => $this->default['input_data']];
        $this->set_input_data($input_data);

        $this->generate_html()
             ->shouldEqual("end_value\n");
    }

    public function it_can_filter_with_input_data_with_s()
    {
        /* Prepare the query */
        $wp_query = Mockery::mock('WP_Query');

        // Set input data
        $input_data = [0 => $this->default['input_data']];
        $this->set_input_data($input_data);

        // Set expectations
        $meta_query [] = [
                        'key' => $this->default['field_name'],
                        'value' => '%'. $this->default['input_data'] .'%',
                        'compare' => 'LIKE',
                        ];

        $wp_query->meta_query = [];
        $wp_query
            ->shouldReceive('set')
            ->andReturn($wp_query)
            ->with('meta_query', $meta_query)
            ->once();

        // Run
        $this->filter($wp_query)
            ->shouldReturn($wp_query);
    }

    public function it_can_filter_withtout_input_data()
    {
        /* Prepare the query */
        $wp_query = Mockery::mock('WP_Query');

        $this->filter($wp_query)
            ->shouldReturn($wp_query);
    }

    /******************************************************************
    *                                                                 *
    *                             VARIOUS                             *
    *                                                                 *
    ******************************************************************/

    /**
     * Initialize the class.
     */
    public function let()
    {
        $this->mockery_WP_wrapper = Mockery::mock('MrKoopie\WP_wrapper\WP_wrapper');
        $this->mockery_stub = Mockery::mock('MrKoopie\WP_wrapper\stub');
        $this->beConstructedWith($this->default['label_name'], $this->default['field_name'], $this->mockery_WP_wrapper, $this->mockery_stub);
    }

    public function letGo()
    {
        Mockery::close();
    }
}
