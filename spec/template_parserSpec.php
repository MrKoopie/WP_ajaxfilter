<?php

namespace spec\MrKoopie\WP_ajaxfilter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class template_parserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('MrKoopie\WP_ajaxfilter\template_parser');
    }

    function it_can_load_a_template_file()
    {
    	$this->load_template_file('checkbox');
    }

    /**
     * - Load a template file
     * - Replace strings in template file
     * - Return the parsed data
     */
}
