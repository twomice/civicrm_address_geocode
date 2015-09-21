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
      CRM_Core_BAO_CustomField::buildOption($result, $options);
      unset($options['attributes']);
      $this->value_options = $options;
    }
  }

  function operators() {
    $operators = parent::operators();
    $operators += array(
      'all' => array(
        'title' => t('Is all of'),
        'short' => t('all'),
        'method' => 'op_simple',
        'values' => 1,
        ),
      );

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
        $op = ' = ';
        $glue = ' OR ';
        break;
      case 'not in':
        $op = ' != ';
        $glue = ' AND ';
        break;
      case 'all' :
        $clause = AREA_TABLE . '.' . AREA_COLUMN . " IN ('" . implode(CRM_Core_DAO::VALUE_SEPARATOR, $this->value) . "')";
        break;
    }

    if ($op) {
      $value = implode(CRM_Core_DAO::VALUE_SEPARATOR, $this->value);
      $clause = AREA_TABLE . '.' . AREA_COLUMN . $op . "'" . $value . "'";
    }

    $this->query->add_where_expression($this->options['group'], $clause);
  }
}