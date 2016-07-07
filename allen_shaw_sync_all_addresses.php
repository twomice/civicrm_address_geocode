<?php

/**
 * To sync all CiviCRM addresses (having geo_codes already) to Drupal
 * 'civicrm_address' entities, call this script from Drush like so:
 *    $ cd [this directory]
 *    $ drush scr allen_shaw_sync_all_addresses
 */

civicrm_initialize();
$address = new CRM_Core_DAO_Address();
$address->whereAdd("ifnull(geo_code_1, '') > ''");
$address->whereAdd("ifnull(geo_code_2, '') > ''");
$address->find();
while ($address->fetch()) {
  updateGeoCode($address);
}