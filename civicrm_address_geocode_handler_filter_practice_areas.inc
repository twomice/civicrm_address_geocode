<?php

class civicrm_address_geocode_handler_filter_practice_areas extends views_handler_filter_in_operator {
  function construct() {
    parent::construct();
    if (!civicrm_initialize()) {
      return;
    }
  }

  function get_value_options() {
    if (!isset($this->value_options)) {
      $result = civicrm_api3('CustomField', 'getsingle', array(
        'sequential' => 1,
        'id' => AREAS,
      ));
      $options= array();
      $options = $optionValues = CRM_Core_PseudoConstant::get('CRM_Core_BAO_CustomField', 'custom_' . AREAS, array(), 'get');
      unset($options['attributes']);
      $this->value_options = $options;
    }
  }

  function operators() {
    $operators = parent::operators();

    return $operators;
  }

  function op_simple() {
    if (empty($this->value)) {
      return;
    }

    $this->ensure_my_table();

    // negated operator uses AND, positive uses OR
    $op = $glue = null;
    switch ($this->operator) {
      case 'in':
        $op = ' LIKE ';
        $glue = ' OR ';
        break;
      case 'not in':
        $op = ' NOT LIKE ';
        $glue = ' AND ';
        break;
    }

    if ($op) {
      foreach ($this->value as $value) {
        $clauses[] = AREA_TABLE . '.' . AREA_COLUMN . $op . "'%" . $value . "%'";
      }
      $clause = implode($glue, $clauses);
    }

    $this->query->add_where_expression($this->options['group'], $clause);
  }
}