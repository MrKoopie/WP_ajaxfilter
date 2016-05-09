<?php

namespace spec\MrKoopie\WP_ajaxfilter\Input;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mockery;

class baseSpec extends ObjectBehavior
{
    private $default = [
        'label_name'   => 'label_name',
        'input_data' => ['some' => 'inputdata'],
        'field_name' => 'field_name',
        'taxonomy_id' => 'taxonomy_id'
    ];
    private $mockery_WP_wrapper;
    private $mockery_stub;

    function it_is_initializable()
    {
        $this->shouldHaveType('MrKoopie\WP_ajaxfilter\Input\Base');
    }

    function it_can_load_data_from_a_taxonomy()
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
        
        $taxonomy[]                     = $tmp_taxonomy;

        
        
        $this->mockery_WP_wrapper->shouldReceive('get_terms')
            ->with($this->default['taxonomy_id'])
            ->andReturn($taxonomy)
            ->once();

        $this->load_data_from_taxonomy($this->default['taxonomy_id']);

        $this->get_taxonomy_id()
            ->shouldBe($this->default['taxonomy_id']);

        // Prepare the return taxonomy
        $tmp_filter_data['slug']    = $tmp_taxonomy->slug;
        $tmp_filter_data['label']   = $tmp_taxonomy->name;
        $tmp_filter_data['term_id'] = $tmp_taxonomy->term_id;
        $return_filter_data[] = $tmp_filter_data;
        
        $this->get_filter_data()
            ->shouldBe($return_filter_data);
    }

    function it_can_load_data_from_an_array()
    {
        $tmp_input_data['slug']  = 'sample-slug';
        $tmp_input_data['label'] = 'sample-label';
        
        $input_data[] = $tmp_input_data;

        $this->load_data_from_array($input_data);

        $this->get_filter_data()
            ->shouldBe($input_data);
    }

    function it_can_load_input_data()
    {

        $this->set_input_data($this->default['input_data']);

        $this->get_input_data()
             ->shouldBe($this->default['input_data']);
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
}
