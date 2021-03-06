<?php

namespace spec\MrKoopie\WP_ajaxfilter\Input;

use Mockery;
use PhpSpec\ObjectBehavior;

class checkboxSpec extends ObjectBehavior
{
    private $default = [
        'label_name'     => 'label',
        'field_name'     => 'field_name',
        'taxonomy_id'    => 'taxonomy_id',
    ];
    private $mockery_WP_wrapper;
    private $mockery_stub;

    public function it_is_initializable()
    {
        $this->shouldHaveType('MrKoopie\WP_ajaxfilter\Input\Checkbox');
    }

    public function it_can_generate_the_html_code_without_input_data()
    {
        $tmp_taxonomy = $this->setup_taxonomy();

        /*
         * Set the expected parameters
         */
        $checkbox_parameter_expected_arguments = [
            'field_name' => $this->default['field_name'],
            'value'      => $tmp_taxonomy->slug,
            'label'      => $tmp_taxonomy->name,
            'checked'    => '',
        ];
        $expected_return_checkbox_parameter = 'expected_return_checkbox_parameter';

        /*
         * We do not need to test if MrKoopie\WP_ajaxfilter\stub works properly, as this is done in a different test
         */
        $this->mockery_stub
            ->shouldReceive('parse_stub')
            ->with('input-checkbox-parameter', $checkbox_parameter_expected_arguments)
            ->andReturn($expected_return_checkbox_parameter)
            ->once();

        $this->mockery_stub
            ->shouldReceive('parse_stub')
            ->with('input-checkbox', \Mockery::any())
            ->andReturn('end_value')
            ->once();

        $this->load_data_from_taxonomy($this->default['taxonomy_id']);

        $this->generate_html()
             ->shouldEqual("end_value\n");
    }

    public function it_can_generate_the_html_code_with_input_data()
    {
        $tmp_taxonomy = $this->setup_taxonomy();

        /*
         * Set the expected parameters
         */
        $checkbox_parameter_expected_arguments = [
            'field_name' => $this->default['field_name'],
            'value'      => $tmp_taxonomy->slug,
            'label'      => $tmp_taxonomy->name,
            'checked'    => ' checked',
        ];
        $expected_return_checkbox_parameter = 'expected_return_checkbox_parameter';

        /*
         * We do not need to test if MrKoopie\WP_ajaxfilter\stub works properly, as this is done in a different test
         */
        $this->mockery_stub
            ->shouldReceive('parse_stub')
            ->with('input-checkbox-parameter', $checkbox_parameter_expected_arguments)
            ->andReturn($expected_return_checkbox_parameter)
            ->once();

        $this->mockery_stub
            ->shouldReceive('parse_stub')
            ->with('input-checkbox', \Mockery::any())
            ->andReturn('end_value')
            ->once();

        $input_data = [0 => $tmp_taxonomy->slug];
        $this->set_input_data($input_data);

        $this->load_data_from_taxonomy($this->default['taxonomy_id']);

        $this->generate_html()
             ->shouldEqual("end_value\n");
    }

    public function it_can_filter_with_input_data()
    {
        $tmp_taxonomy = $this->setup_taxonomy();

        /* Prepare the query */
        $wp_query = Mockery::mock('WP_Query');

        // Set the expected return data
        $tax_query[] = [
                            'taxonomy' => $this->default['taxonomy_id'],
                            'field'    => 'id',
                            'terms'    => [$tmp_taxonomy->term_id],
                        ];
        $wp_query->shouldReceive('set')
            ->andReturn($wp_query)
            ->with('tax_query', $tax_query)
            ->once();

        $input_data[] = $tmp_taxonomy->slug;
        $this->set_input_data($input_data);

        /* Load the taxonomy data */
        $this->load_data_from_taxonomy($this->default['taxonomy_id']);

        $this->filter($wp_query)
            ->shouldReturn($wp_query);
    }

    public function it_can_filter_withtout_input_data()
    {
        $tmp_taxonomy = $this->setup_taxonomy();

        /* Prepare the query */
        $wp_query = Mockery::mock('WP_Query');

        /* Load the taxonomy data */
        $this->load_data_from_taxonomy($this->default['taxonomy_id']);

        $this->filter($wp_query)
            ->shouldReturn($wp_query);
    }

    /******************************************************************
    *                                                                 *
    *                             VARIOUS                             *
    *                                                                 *
    ******************************************************************/
    protected function setup_taxonomy()
    {
        /*
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

        return $tmp_taxonomy;
    }

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
