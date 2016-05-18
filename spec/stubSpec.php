<?php

namespace spec\MrKoopie\WP_ajaxfilter;

use Mockery;
use PhpSpec\ObjectBehavior;

class stubSpec extends ObjectBehavior
{
    // Set up the mocks
    protected $mockery_original_fileystem;
    protected $mockery_overridden_fileystem;
    protected $mockery_local;
    protected $mockery_WP_wrapper;

    // Set up the default data
    protected $default = [
                        'filename'              => 'some_filename',
                        'non_existing_filename' => 'non_existing_filename',
                        ];

    public function it_is_initializable()
    {
        // @todo Should add a verification if the correct arguments for __construct are provided.

        $this->shouldHaveType('MrKoopie\WP_ajaxfilter\stub');
    }

    public function it_can_list_original_stubs()
    {
        $return_list_contents[] = [
            'filename'  => $this->default['filename'],
            'extension' => 'stub',
        ];

        $this->mockery_WP_wrapper
            ->shouldReceive('get_stylesheet_directory')
            ->once();

        $this->mockery_original_fileystem
             ->shouldReceive('listContents')
            ->andReturn($return_list_contents)
            ->once();

        $this->list_original_stubs()
             ->shouldBeArray();
    }

    public function it_can_list_overriden_stubs()
    {
        $return_list_contents[] = [
            'filename'  => $this->default['filename'],
            'extension' => 'stub',
        ];

        $this->mockery_WP_wrapper
            ->shouldReceive('get_stylesheet_directory')
            ->once();

        $this->mockery_overridden_fileystem
             ->shouldReceive('listContents')
            ->andReturn($return_list_contents)
            ->once();

        $this->list_overriden_stubs()
            ->shouldBeArray();
    }

    public function it_can_parse_a_stub()
    {
        $return_list_contents[] = [
                                    'filename'  => $this->default['filename'],
                                    'extension' => 'stub',
                                    ];
        $this->mockery_original_fileystem
            ->shouldReceive('listContents')
            ->andReturn($return_list_contents)
            ->once()
            ->shouldReceive('read')
            ->andReturn('<{{replace_this}}>')
            ->once();

        $this->mockery_overridden_fileystem
             ->shouldReceive('listContents')
            ->andReturn([])
            ->once();

        $parameters = [
                        'replace_this' => 'replacement',
        ];

        $this->mockery_WP_wrapper
            ->shouldReceive('get_stylesheet_directory')
            ->once();

        $this->parse_stub($this->default['filename'], $parameters)
             ->shouldBe('<replacement>');
    }

    public function it_can_return_an_exception_when_a_stub_does_not_exist()
    {
        $return_list_contents[] = [
                                    'filename'  => $this->default['filename'],
                                    'extension' => 'stub',
                                    ];
        $this->mockery_original_fileystem
            ->shouldReceive('listContents')
            ->andReturn($return_list_contents)
            ->once();

        $parameters = [
                        'replace_this' => 'replacement',
        ];

        $this->mockery_WP_wrapper
            ->shouldReceive('get_stylesheet_directory')
            ->once();

        $this->shouldThrow('MrKoopie\WP_ajaxfilter\Exceptions\stub_not_found_exception')
            ->during('parse_stub', [$this->default['non_existing_filename'], $parameters]);
    }

    public function it_can_handle_an_overriden_stub()
    {
        $return_list_contents[] = [
                                    'filename'  => $this->default['filename'],
                                    'extension' => 'stub',
                                    ];
        $this->mockery_original_fileystem
            ->shouldReceive('listContents')
            ->andReturn($return_list_contents)
            ->once();

        $this->mockery_overridden_fileystem
             ->shouldReceive('listContents')
            ->andReturn($return_list_contents)
            ->once()
            ->shouldReceive('read')
            ->andReturn('<{{replace_this_override}}>')
            ->once();

        $parameters = [
                        'replace_this_override' => 'replacement',
        ];

        $this->mockery_WP_wrapper
            ->shouldReceive('get_stylesheet_directory')
            ->once();

        $this->parse_stub($this->default['filename'], $parameters)
             ->shouldBe('<replacement>');
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
        $this->mockery_original_fileystem = Mockery::mock('League\Flysystem\Filesystem');
        $this->mockery_overridden_fileystem = Mockery::mock('League\Flysystem\Filesystem');
        $this->mockery_local = Mockery::mock('League\Flysystem\Adapter\Local');
        $this->mockery_WP_wrapper = Mockery::mock('MrKoopie\WP_wrapper\WP_wrapper');
        $this->beConstructedWith($this->mockery_WP_wrapper, $this->mockery_local, $this->mockery_original_fileystem, $this->mockery_overridden_fileystem);
    }

    public function letGo()
    {
        Mockery::close();
    }
}
