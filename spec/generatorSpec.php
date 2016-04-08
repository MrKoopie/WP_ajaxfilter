<?php

namespace spec\MrKoopie\WP_ajaxfilter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mockery;

class generatorSpec extends ObjectBehavior
{
	private $mockery_WP_wrapper;

	protected $default = [
                        'ajax_template' => 'ajax_template_file',
						'form_id'		=> 'some_id',
                        'taxonomy_id'   => 'some_taxonomy_id',
                        'field_name'    => 'some_field',
                        'translation'   => 'some translation'
					];
    protected $mockery_stub;

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

            // Register the ajax calls
             ->shouldReceive('add_action')
             ->with('wp_ajax_wpf_' . $this->default['form_id'], Mockery::any())
             ->once()
             ->shouldReceive('add_action')
             ->with('wp_ajax_nopriv_wpf_' . $this->default['form_id'], Mockery::any())
             ->once()

             // Enqueue the scripts
             ->shouldReceive('add_action')
             ->with('wp_enqueue_scripts', Mockery::any())
             ->once()

             // Set the query filter
             ->shouldReceive('add_action')
             ->with('pre_get_posts', Mockery::any())
             ->once();

        $this->render()
            ->shouldBe($this);
    }

    function it_can_enqueue_scripts()
    {
        $this->mockery_WP_wrapper
            // It should register the script
            ->shouldReceive('wp_register_script')
            ->with('MRK-ajax-filter', 'http/vendor/mrkoopie/wp_ajaxfilter/assets/js/wp_ajaxfilter.js', ['jquery'])
            ->once()

            // // Fake the url
            ->shouldReceive('get_stylesheet_directory_uri')
            ->andReturn('http')
            ->once()

            // Fix the translation
            ->shouldReceive('esc_html__')
            ->with('Loading...', 'mrka')
            ->once()

            // // Localize
            ->shouldReceive('wp_localize_script')
            ->with('MRK-ajax-filter', 'mrka', Mockery::any())
            ->once()

            // // Enqueue
            ->shouldReceive('wp_enqueue_script')
            ->with('MRK-ajax-filter')
            ->once();

        $this->enqueue_scripts();
    }

    function it_can_handle_ajax_requests()
    {
        $this->mockery_WP_wrapper
            // Mock the query
            ->shouldReceive('query_posts')
            ->with(['post_type' => 'post'])
            ->once()

            // Mock the get_template_part
            ->shouldReceive('get_template_part')
            ->with($this->default['ajax_template'])
            ->once()

            // Mock the exit
            ->shouldReceive('exit')
            ->once();

        $this->set_ajax_template($this->default['ajax_template']);

        $this->ajax();
    }

    function it_does_not_add_a_filter_query_when_no_input_data_is_provided()
    {
        // Mock the query
        $query = Mockery::mock('WP_Query');

        $this->filter_query($query)
            ->shouldBe($query);
    }

    function it_can_add_a_filter_query()
    {
        // Mock the wp_query
        $query = Mockery::mock('WP_Query');

        // Simulate that input data is provided
        $_GET['mrka_id']  = $this->default['form_id'];

        // Set everything up
        $tmp_taxonomy = $this->setup_taxonomy_with_tests();

        $_GET['mrka_val'] = urlencode($this->default['field_name'].'='.$tmp_taxonomy->slug);

        // Set the expected return data
        $tax_query[] = [ 
                            'taxonomy' => $this->default['taxonomy_id'],
                            'field'    => 'id', 
                            'terms'    => [$tmp_taxonomy->term_id]
                        ]; 

        // Set the expected query function, this ensures that the filter is being set.
        $query->shouldReceive('set')
            ->with('tax_query', $tax_query)
            ->once();

        $this->filter_query($query)
            ->shouldBe($query);
    }

    function it_can_generate_html()
    {
        $tmp_taxonomy = $this->setup_taxonomy_with_tests();

        /**
         * Set the expected parameters
         */
        $checkbox_parameter_expected_arguments = [
            'field_name' => $this->default['field_name'],
            'value'      => $tmp_taxonomy->slug,
            'label'      => $tmp_taxonomy->name,
            'checked'    => ''
        ];
        $expected_return_checkbox_parameter = 'expected_return_checkbox_parameter';

        /**
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

        $this->mockery_stub
            ->shouldReceive('parse_stub')
            ->with('form', \Mockery::any())
            ->andReturn('end_value')
            ->once();
        
        $this->mockery_WP_wrapper
            ->shouldReceive('get_site_url')
            ->withNoArgs()
            ->once()
            ->shouldReceive('get_terms')
            ->with($this->default['taxonomy_id']);



        $this->html()
            ->shouldBeString();
    }

    /******************************************************************
    *                                                                 *
    *                             VARIOUS                             *
    *                                                                 *
    ******************************************************************/
    protected function setup_taxonomy_with_tests()
    {
        /**
         * Prepare the taxonomy data
         */
        $tmp_taxonomy                   = new \stdClass();
        $tmp_taxonomy->term_id          = 1;
        $tmp_taxonomy->name             = 'Geen categorie';
        $tmp_taxonomy->slug             = 'geen-categorie';
        $tmp_taxonomy->term_group       = 0;
        $tmp_taxonomy->term_taxonomy_id = 1;
        $tmp_taxonomy->taxonomy         = 'category';
        $tmp_taxonomy->description      = null;
        $tmp_taxonomy->parent           = 0;
        $tmp_taxonomy->count            = 2;
        $tmp_taxonomy->filter           = 'raw';
        
        $taxonomy[] = $tmp_taxonomy;

        $this->mockery_WP_wrapper->shouldReceive('get_terms')
            ->with($this->default['taxonomy_id'])
            ->andReturn($taxonomy)
            ->once();


        $this->add_checkbox($this->default['translation'], $this->default['taxonomy_id'], $this->default['field_name']);

        return $tmp_taxonomy;
    }

	/**
	 * Initialize the class
	 */
	public function let()
	{
		$this->mockery_WP_wrapper = Mockery::mock('MrKoopie\WP_wrapper\WP_wrapper');
        $this->mockery_stub       = Mockery::mock('MrKoopie\WP_wrapper\stub');
		$this->beConstructedWith($this->default['form_id'], $this->mockery_WP_wrapper, $this->mockery_stub);
	}

    /**
     * Process Mockery
     */
    public function letGo()
    {
        Mockery::close();
        unset($_GET);
    }
}
