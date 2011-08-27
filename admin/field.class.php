<?php

// Field object for category / event / holiday ... forms
class RpbcField
{
	public $key  ;                   // Key to identify the field (must correspond to the SQL key)
	public $label;                   // Field label;
	public $type ;                   // Type of field (text, checkbox, ...)
	public $options       = array(); // Field options (ex: array('maxlength' => 30))
	public $legend        = NULL   ; // Optional field legend
	public $default_value = NULL   ; // Default value for the field


	// Constructor
	public function __construct($key, $label, $type)
	{
		$this->key   = $key  ;
		$this->label = $label;
		$this->type  = $type ;
	}

	// Rendering function
	public function print_field($in_table, $elem)
	{
		// Prefix + label
		if($in_table) {
			echo '<tr class="form-field">';
			echo '<th scope="row">';
		} else {
			echo '<div class="form-field">';
		}
		echo '<label for="'.$this->key.'">'.$this->label.'</label>';
		if($in_table) {
			echo '</th><td>';
		}

		// Field
		$value = '';
		if(isset($elem)) {
			$field = $this->key;
			$value = htmlspecialchars($elem->$field);
		} elseif(isset($this->default_value)) {
			$value = $this->default_value;
		}
		echo '<input type="'.$this->type.'" name="'.$this->key.'" value="'.$value.'" ';
		foreach($this->options as $opt_key => $opt_val) {
			echo $opt_key.'="'.$opt_val.'" ';
		}
		echo '/>';
		if(isset($this->legend)) {
			echo '<p class="description">'.$this->legend.'</p>';
		}

		// Suffix
		if($in_table) {
			echo '</td></tr>';
		} else {
			echo '</div>';
		}
	}
}

?>
