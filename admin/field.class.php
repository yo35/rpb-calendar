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
	public $allow_empty   = false  ; // Option to allow empty fields


	// Constructor
	public function __construct($key, $label, $type)
	{
		$this->key   = $key  ;
		$this->label = $label;
		$this->type  = $type ;
	}

	// Rendering function
	public function print_field($in_table, $elem=NULL)
	{
		// Special case for hidden fields
		if($this->type=='hidden') {
			$this->print_hidden_field();
			return;
		}

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
			$value = $this->retrieve_value($elem);
		} elseif(isset($this->default_value)) {
			$value = $this->default_value;
		}
		$this->print_actual_field($value);
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

	// Convert the data read from the database for editing
	protected function retrieve_value($elem)
	{
		$field = $this->key;
		return htmlspecialchars($elem->$field);
	}

	// Rendering the actual field
	private function print_actual_field($value)
	{
		// Textarea
		if($this->type=='textarea') {
			echo '<textarea ';
			$this->print_option_attributes();
			echo '>'.$value.'</textarea>';

		// Select
		} elseif($this->type=='select') {
			echo '<select ';
			$this->print_option_attributes();
			echo '>';
			foreach($this->options['choices'] as $choice) {
				echo '<option value="'.$choice['key'].'"';
				if($choice['key']==$value) {
					echo ' selected="1"';
				}
				echo '>'.$choice['value'].'</option>';
			}
			echo '</select>';

		// General input field
		} else {
			echo '<input type="'.$this->type.'" ';
			$this->print_option_attributes();
			echo ' value="'.$value.'" />';
		}
	}

	// Rendering function for hidden fields
	private function print_hidden_field()
	{
		$value = '';
		if(isset($this->default_value)) {
			$value = $this->default_value;
		}
		echo '<input type="hidden" ';
		$this->print_option_attributes();
		echo ' value="'.$value.'" />';
	}

	// Print the option attributes and the name associated to the field
	private function print_option_attributes()
	{
		echo 'name="'.$this->key.'"';
		foreach($this->options as $opt_key => $opt_val) {
			if($opt_key=='choices') {
				continue;
			}
			echo ' '.$opt_key.'="'.$opt_val.'"';
		}
	}

	// Validation function
	public function validation(&$values)
	{
		$this->prepare_validation($values);
		if(!isset($values[$this->key]) || empty($values[$this->key])) {
			if($this->allow_empty) {
				$values[$this->key] = NULL;
				return true;
			} else {
				rpbcalendar_admin_error_message(sprintf(
					__('Empty or undefined field: &quot;%s&quot;', 'rpbcalendar'), $this->label));
				return false;
			}
		}
		return $this->additional_validation($values);
	}

	// Additional validation function (exist for sub-classing purposes)
	protected function additional_validation(&$values)
	{
		return true;
	}

	// Pre-validation operation
	protected function prepare_validation(&$values)
	{
		return;
	}
}

?>
