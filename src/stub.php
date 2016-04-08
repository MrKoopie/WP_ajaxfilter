<?php

namespace MrKoopie\WP_ajaxfilter;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use \MrKoopie\WP_ajaxfilter\Exceptions\stub_not_found_exception;

/**
 * Class stub. Parses a stub template with parameters.
 *
 * @package MrKoopie\WP_ajaxfilter
 */
class stub
{
	protected $filesystem;
	protected $fly_filesystem;
	protected $fly_local;
	protected $list_stubs_cache;

	public function __construct($fly_filesystem = null, $fly_local = null)
	{
		$stubs_dir = __DIR__ . '/stubs/';

		if($fly_local != null)
			$this->fly_local       = $fly_local;
		else 
			$this->fly_local       = new Local($stubs_dir);

		if($fly_filesystem != null)
			$this->fly_filesystem  = $fly_filesystem;
		else
			$this->fly_filesystem  = new Filesystem($this->fly_local);
	}

	/** 
	 * List all stub files
	 * @return array All stub file names (without .stub)
	 */
    public function list_stubs()
    {
    	// Check if we have cache
    	if($this->list_stubs_cache != '')
    		return $this->list_stubs_cache;

    	$files = $this->fly_filesystem->listContents();

    	// Return nothing when the array is empty
    	if(count($files) == 0)
    		return [];

    	$stubs = [];
    	foreach($files as $file)
    	{
    		// Only parse .stub files
    		if($file['extension'] != 'stub')
    			continue;

    		$stubs[] = $file['filename'];
    	}

    	$this->list_stubs_cache = $stubs;

        return $stubs;
    }

    public function parse_stub($file_name, $parameters)
    {
		if(!$this->check_if_stub_exists($file_name))
			return false;


		$replace = [];
		$replace_with = [];
		foreach($parameters as $key => $value)
		{
			$replace[] 		= '{{'.$key.'}}';
			$replace_with[] = $value;
		}

		$stub = $this->fly_filesystem->read($file_name . '.stub');

		$stub = str_replace($replace, $replace_with, $stub);

        return $stub;
    }

	/**
	 * Verifies if a stub exists.
	 *
	 * @param $file_name
	 */
	private function check_if_stub_exists($file_name)
	{
		$stubs = $this->list_stubs();

		foreach ($stubs as $stub) {
			if ($stub == $file_name) {
				$found_stub = true;
				break;
			}
		}

		if(!isset($found_stub) || $found_stub != true)
			throw new stub_not_found_exception($file_name);

		return true;
	}
}
