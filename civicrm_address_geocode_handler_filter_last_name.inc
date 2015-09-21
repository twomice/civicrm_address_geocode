<?php

class civicrm_address_geocode_handler_filter_last_name extends views_handler_filter_string {
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
 
  // Override the op_contains function
  // adding our field function as parameter
  function op_contains($field) {
    $field = $this->last_name();
    $this->query->add_where($this->options['group'], $field, '%' . db_like($this->value) . '%', 'LIKE');
  }
}