<?php

namespace spec\MrKoopie\WP_ajaxfilter;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Mockery;


class stubSpec extends ObjectBehavior
{
	// Set up the mocks
	protected $mockery_fileystem;
	protected $mockery_local;

	// Set up the default data
	protected $default = [
						'filename'              => 'some_filename',
						'non_existing_filename' => 'non_existing_filename',
						];
    function it_is_initializable()
    {
        $this->shouldHaveType('MrKoopie\WP_ajaxfilter\stub');
    }

    function it_can_list_stubs()
    {
		$return_list_contents[] = [
			'filename' => $this->default['filename'],
			'extension' => 'stub'
		];

    	$this->mockery_fileystem
    		 ->shouldReceive('listContents')
			->andReturn($return_list_contents)
			->once();

    	$this->list_stubs()
    		 ->shouldBeArray();
    }

    function it_can_parse_a_stub()
    {
		$return_list_contents[] = [
									'filename' => $this->default['filename'],
									'extension' => 'stub'
									];
		$this->mockery_fileystem
			->shouldReceive('listContents')
			->andReturn($return_list_contents)
			->once()
			->shouldReceive('read')
			->andReturn('<{{replace_this}}>')
			->once();

    	$parameters = [
						'replace_this' => 'replacement'
		];
    	$this->parse_stub($this->default['filename'], $parameters)
    		 ->shouldBe('<replacement>');
    }

    function it_can_return_an_exception_when_a_stub_does_not_exist()
    {
		$return_list_contents[] = [
									'filename' => $this->default['filename'],
									'extension' => 'stub'
									];
		$this->mockery_fileystem
			->shouldReceive('listContents')
			->andReturn($return_list_contents)
			->once();

    	$parameters = [
						'replace_this' => 'replacement'
		];
    	$this->shouldThrow('MrKoopie\WP_ajaxfilter\Exceptions\stub_not_found_exception')
    		->during('parse_stub',[$this->default['non_existing_filename'], $parameters]);
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
		$this->mockery_fileystem   	= Mockery::mock('League\Flysystem\Filesystem');
		$this->mockery_local   		= Mockery::mock('League\Flysystem\Adapter\Local');
		$this->beConstructedWith($this->mockery_fileystem, $this->mockery_local);
	}

	public function letGo()
	{
		Mockery::close();
	}
}
