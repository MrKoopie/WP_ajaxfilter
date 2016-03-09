<?php
/**
 * Generate the HTML form.
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
class ajax_filter_generator extends ajax_filter
{
	protected $mapped_columns_sort;
	protected $mapped_columns; // key = input field, data = tech name
	protected $currently_working_on;

	/**
	 * Map the column types, like connecting the field to the correct ACF field.
	 * 
	 * @param  string $column_input_name The input name of the filter_data.
	 * @param  string $tech_column_name Optional: the technical name of the column.
	 * @return  object Returns this object.
	 */
	public function add_column($translation, $column_name)
	{
		$this->mapped_columns_sort[]                       = $column_name;
		$this->mapped_columns[$column_name]['translation'] = $translation;
		$this->currently_working_on                        = $column_name;

		return $this;
	}


	
	/**
	 * Generate the filter form
	 * 
	 * @param  string $html_target The identifier #$html_target
	 */
	public function generate_form($html_target, $class = '')
	{
		?>
		<form class="MRK_ajax_filter" data-MRK-ajax-filter="<?php echo $html_target;?>">
		<?php
			foreach($this->mapped_columns_sort as $column_name)
			{
				// Find the column data
				$column    = $this->mapped_columns[$column_name];
				$column_id = $html_target . '_' . $column_name;

				// Show the label
				?>
				<label for="<?php echo $column_id;?>" class="filter_name">
	    			<?php echo $column['translation'];?>
	    		</label>
				<?php

				// Show the configured html code.
				if($column['type'] == 'checkbox')
				{
					// Allow the user to deselect all boxes
					echo '<a href="#" class="deselect" data-MRK-deselect-target="' . $column_name . '">'.__('Deselect all', 'WP_ajaxfilter').'</a>';


					$terms = get_terms($column['taxonomy']);
					foreach($terms as $term)
					{
						$checkbox_id = $column_id . '_' . $term->term_id;
					?>
				<label for="<?php echo $checkbox_id;?>"><input type="checkbox" name="<?php echo $column_name;?>" id="<?php echo $checkbox_id;?>" value="<?php echo $term->term_id; ?>"<?php
						if(isset($this->filter_data[$column_name]) && array_search($term->term_id, $this->filter_data[$column_name] ) !== false)
							echo ' checked';
						?>> <?php echo $term->name;?></label>
					<?php
					}
				}
				else if($column['type'] == 'radio_button')
				{
					// Allow the user to deselect all boxes
					echo '<a href="#" class="deselect" data-MRK-deselect-target="' . $column_name . '">'.__('Deselect all', 'WP_ajaxfilter').'</a>';

					$terms = get_terms($column['taxonomy']);
					foreach($terms as $term)
					{
						$radio_button_id = $column_id . '_' . $term->term_id;
					?>
				<label for="<?php echo $radio_button_id;?>"><input type="radio" name="<?php echo $column_name;?>" id="<?php echo $radio_button_id;?>" value="<?php echo $term->term_id; ?>"<?php
						if(isset($this->filter_data[$column_name]) && array_search($term->term_id, $this->filter_data[$column_name] ) !== false)
							echo ' checked';
						?>> <?php echo $term->name;?></label>
					<?php
					}
				}
				else if ($column['type'] == 'text')
				{
					?>
					<input type="text" name="<?php echo $column_name;?>"<?php
					if(isset($this->filter_data[ $column_name]))
						echo ' value="' . htmlentities( $this->filter_data[ $column_name ][0] ).'"'; ?><br>
					<?php
				}
			} // End foreach
		?>
			<input type="submit" value="<?php _e('Filter', 'WP_ajaxfilter');?>" class="<?php echo $class;?>">
		</form>
		<?php
		
	}

	/**
	 * Set the taxonomy name and the input type to checkbox.
	 * 
	 * @param  string $taxonomy_name The technical taxonomy name
	 * @param  string $column_name Optional: the column_name. Else we will use $this->currently_working_on
	 */
	public function set_text($column_name = null)
	{
		if($column_name == null)
			$column_name = $this->currently_working_on;

		$this->mapped_columns[$column_name]['taxonomy'] = $taxonomy_name;
		$this->mapped_columns[$column_name]['type'] 	= 'text';

		return $this;
	}

	/**
	 * Set the taxonomy name and the input type to checkbox.
	 * 
	 * @param  string $taxonomy_name The technical taxonomy name
	 * @param  string $column_name Optional: the column_name. Else we will use $this->currently_working_on
	 */
	public function set_checkbox($taxonomy_name, $column_name = null)
	{
		if($column_name == null)
			$column_name = $this->currently_working_on;

		$this->mapped_columns[$column_name]['taxonomy'] = $taxonomy_name;
		$this->mapped_columns[$column_name]['type'] 	= 'checkbox';

		return $this;
	}

	/**
	 * Set the taxonomy name and the input type to radiobutton.
	 * 
	 * @param  string $taxonomy_name The technical taxonomy name
	 * @param  string $column_name Optional: the column_name. Else we will use $this->currently_working_on
	 */
	public function set_radio_button($taxonomy_name, $column_name = null)
	{
		if($column_name == null)
			$column_name = $this->currently_working_on;

		$this->mapped_columns[$column_name]['taxonomy'] = $taxonomy_name;
		$this->mapped_columns[$column_name]['type'] 	= 'radio_button';

		return $this;
	}
}