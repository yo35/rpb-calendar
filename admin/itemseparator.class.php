<?php

/**
 * Class to use to provide nice separations whithin a long list of items
 */
abstract class RpbcItemSepator
{
	private $category_changed = false;
	private $current_hash     = null ;
	private $current_label    = null ;
	
	/**
	 * Set the current item
	 */
	public function update_current_item($obj)
	{
		$new_hash = $this->compute_hash($obj);
		if($new_hash!=$this->current_hash) {
			$this->category_changed = true;
			$this->current_hash     = $new_hash;
			$this->current_label    = $this->compute_label($obj);
		}
		else {
			$this->category_changed = false;
		}
	}
	
	/**
	 * Check whether the current item initiates a new category
	 */
	public function has_category_changed()
	{
		return $this->category_changed;
	}
	
	/**
	 * Return the label of the current category
	 */
	public function get_current_label()
	{
		return $this->current_label;
	}
	
	/**
	 * Function to implement to extract a hash value that describe to which category
	 * the current item belongs to
	 */
	abstract protected function compute_hash($obj);
	
	/**
	 * Function to implement to extract label that describe the categoy to which
	 * the current item belongs to
	 */
	abstract protected function compute_label($obj);
}
