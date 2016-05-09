<?php

namespace MrKoopie\WP_ajaxfilter;
use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use \MrKoopie\WP_wrapper\WP_wrapper;
use \MrKoopie\WP_ajaxfilter\Exceptions\stub_not_found_exception;

/**
 * Class stub. Parses a stub template with parameters.
 *
 * @package MrKoopie\WP_ajaxfilter
 */
class stub
{
	protected $filesystem;
	protected $fly_original_filesystem;
	protected $fly_overriden_filesystem;
	protected $list_original_stubs_cache;
	protected $list_overridden_stubs_cache;

	public function __construct($WP_wrapper = null, $fly_local = null, $fly_original_filesystem = null, $fly_overridden_filesystem = null)
	{
		if($WP_wrapper != null)
            $this->WP_wrapper = $WP_wrapper;
        else
            $this->WP_wrapper = new WP_wrapper();

		$original_stubs_dir 	= __DIR__ . '/stubs/';
		$overriden_stubs_dir 	= $this->WP_wrapper->get_stylesheet_directory()."/overrides/wp_ajaxfilter_stubs/";



		if($fly_local != null)
		{
			$fly_original_local   		= $fly_local;
			$fly_overridden_local       = $fly_local;

		}
		else 
		{
			$fly_original_local       	= new Local($original_stubs_dir);
			$fly_overridden_local       = new Local($overriden_stubs_dir);
		}

		if($fly_original_filesystem != null)
		{
			$this->fly_original_filesystem  = $fly_original_filesystem;
		}
		else
			$this->fly_original_filesystem  = new Filesystem($fly_original_local);

		if($fly_overridden_filesystem != null)
			$this->fly_overriden_filesystem  = $fly_overridden_filesystem;
		else
			$this->fly_overriden_filesystem  = new Filesystem($fly_overridden_local);
	}

	/** 
	 * List all stub files
	 * @return array All stub file names (without .stub)
	 */
    public function list_original_stubs()
    {
    	// Check if we have cache
    	if($this->list_original_stubs_cache != '')
    		return $this->list_original_stubs_cache;

    	$files = $this->fly_original_filesystem->listContents();

    	// Return nothing when the array is empty
    	if(count($files) == 0)
    		return [];

    	$stubs = [];
    	foreach($files as $file)
    	{
    		// Only parse .stub files
    		if($file['extension'] != 'stub')
    			continue;

    		$stubs[$file['filename']] = true;
    	}

    	$this->list_original_stubs_cache = $stubs;

        return $stubs;
    }

    /** 
	 * List all overriden stub files
	 * @return array All stub file names (without .stub)
	 */
    public function list_overriden_stubs()
    {
    	// Check if we have cache
    	if($this->list_overridden_stubs_cache != '')
    		return $this->list_overridden_stubs_cache;

    	$files = $this->fly_overriden_filesystem->listContents();

    	// Return nothing when the array is empty
    	if(count($files) == 0)
    		return [];

    	$stubs = [];
    	foreach($files as $file)
    	{
    		// Only parse .stub files
    		if($file['extension'] != 'stub')
    			continue;

    		$stubs[$file['filename']] = true;
    	}

    	$this->list_overridden_stubs_cache = $stubs;

        return $stubs;
    }

    public function parse_stub($file_name, $parameters)
    {
		$this->check_if_original_stub_exists($file_name);


		$replace = [];
		$replace_with = [];
		foreach($parameters as $key => $value)
		{
			$replace[] 		= '{{'.$key.'}}';
			$replace_with[] = $value;
		}

		if($this->check_if_overridden_stub_exists($file_name) != false)
			$stub = $this->fly_overriden_filesystem->read($file_name . '.stub');
		else
			$stub = $this->fly_original_filesystem->read($file_name . '.stub');

		$stub = str_replace($replace, $replace_with, $stub);

        return $stub;
    }

	/**
	 * Verifies if a stub exists.
	 *
	 * @param $file_name
	 */
	private function check_if_original_stub_exists($file_name)
	{
		$stubs = $this->list_original_stubs();

		if(!isset($this->list_original_stubs_cache[$file_name]))
			throw new stub_not_found_exception($file_name);

		return true;
	}

	/**
	 * Verifies if a stub exists.
	 *
	 * @param $file_name
	 */
	private function check_if_overridden_stub_exists($file_name)
	{
		$this->list_overriden_stubs();

		if(!isset($this->list_overridden_stubs_cache[$file_name]))
			return false;

		return true;
	}
}
