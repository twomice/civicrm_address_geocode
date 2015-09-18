<?php

class civicrm_address_geocode_handler_filter_last_name extends views_handler_filter_numeric {
  function operators() {
    $operators = parent::operators();
    // We won't be using regex in our example
    unset($operators['regular_expression']);
 
    return $operators;
  }
 
  // Helper function to return a sql expression
  // for counting words in a field.
  function last_name() {
    // Set the real field to the title of the node
    $this->real_field = 'Last Name';
 
    $field = "civicrm_contact.last_name";
    return "$field";
  }
 
  // Override the op_simple function
  // adding our field count function as parameter
  function op_simple($field) {
    $field_count = $this->last_name();
 
    $value = $this->value['value'];
 
    $this->query->add_where_expression($this->options['group'], "$field_count $this->operator '{$value}'");
  }
}