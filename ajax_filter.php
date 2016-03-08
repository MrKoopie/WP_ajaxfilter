<?php
/**
 * Generate the filter for $query
 * 
 * @author  	Daniel Koop <mail@danielkoop.me>
 * @copyright  	Daniel Koop 2016
 * @license  	https://opensource.org/licenses/MIT MIT
 * @version  	0.1
 */

namespace MrKoopie\WP_ajaxfilter;

/**
* Process every filter
*/
class ajax_filter
{
	protected $filter_data;
	protected $mapped_columns; // key = input field, data = tech name

	/**
	 * Convert the raw filter data into an array
	 */
	public function filter_raw_data($raw_filter_data)
	{
		$split_raw_filter_data = explode('&', $raw_filter_data);

		foreach($split_raw_filter_data as $raw_filter_data)
		{
			$split_raw_filter_data = explode('=', $raw_filter_data);

			$this->filter_data[ $split_raw_filter_data[0] ][] = $split_raw_filter_data[1];
		}

		
		return $this;
	}

	/**
	 * Configure $query with the correct filter parameters.
	 * 
	 * @param  object $query The WordPress query
	 * @return  $query The WordPress query with filter.
	 */
	public function configure_query($query)
	{
		
		// Loop through every mapped columns
		foreach($this->mapped_columns as $column)
		{
			$column_name = $column['name'];

			// Only process this column when we have some input data.
			if(!isset($this->filter_data[$column_name]))
				continue;
			
			
			if($column['type']  == 'serialized')
			{
				// Loop through the array with data
				foreach($this->filter_data[$column_name] as $data)
				{
					// append meta query
			    	$meta_query[] = array(
			            'key'		=> $column_name,
			            'value' => '"' . $data . '"' ,
                        'compare' => 'LIKE'
			        );
				} // End for loop
			}
			else if ($column['type'] == 'normal')
			{
				$meta_query[] = array(
		            'key'		=> $column_name,
		            'value'		=> $this->filter_data[$column_name],
		        );
			}
			else if ($column['type'] == 'search')
			{
				$query->set('s', $this->filter_data[$column_name][0]);
			}
			else if($column['type'] == 'taxonomy')
			{
				$tmp_taxonomy['taxonomy'] 		= $column_name;
				$tmp_taxonomy['field'] 			= 'term_id';
				$tmp_taxonomy['terms'] 			= $this->filter_data[$column_name];
				$tmp_taxonomy['operator'] 		= 'AND';
				$tax_query[] 					= $tmp_taxonomy;
				unset($tmp_taxonomy);
			}
			
		} // End foreach loop

		
		if(isset($tax_query))
		{
			$tax_query['relation'] = 'AND';
			$query->set('tax_query', $tax_query);
		}
		
		if(isset($meta_query))
			$query->set('meta_query', $meta_query);


	}

	/**
	 * Map the column types. This is required because we do not want to use
	 * the user input to determine which columns we have.
	 * 
	 * @param  string $column_name The input name of the filter_data.
	 * @param  string $type Can be normal, search (will use the search function in WP), serialized (for ACF), taxonomy
	 */
	public function add_column($column_name, $type = 'normal')
	{
		$tmp_value = [
						'name' => $column_name,
						'type' => $type, 
					];

		$this->mapped_columns[] = $tmp_value;

		return $this;
	}

	
}