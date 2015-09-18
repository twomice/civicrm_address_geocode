<?php
define('AREA_FIELD', 3);

$params = array(
  'contact_id' => $row->civicrm_address_contact_id,
  'api.Phone.get' => array(
    'location_type_id' => 2,
    'phone_type_id' => 1,
  ),
  'api.CustomValue.get' => 1,
);
$contact = civicrm_api3('Contact', 'get', $params);
$phoneDiv = '';
if ($contact['count'] != 0) {
  $displayName = $contact['values'][$row->civicrm_address_contact_id]['display_name'];
  $contactLink = "/civicrm/contact/view?reset=1&cid={$row->civicrm_address_contact_id}";
  if ($contact['values'][$row->civicrm_address_contact_id]['api.Phone.get']['count'] != 0) {
    foreach ($contact['values'][$row->civicrm_address_contact_id]['api.Phone.get']['values'] as $phones) {
      $phoneDiv .= "<a href='tel:" . $phones['phone'] . "'>" . $phones['phone'] . "</a><br/>";
    }
  }
  // Areas of interest
  $areas = $contact['values'][$row->civicrm_address_contact_id]['api.CustomValue.get']['values'][AREA_FIELD]['latest'];
  foreach ($areas as $area) {
    $result = civicrm_api3('CustomField', 'getsingle', array(
                'sequential' => 1,
                'id' => AREA_FIELD,
              ));
    $options = array();
    CRM_Core_BAO_CustomField::buildOption($result, $options[AREA_FIELD]);
    $tempArray[] = $options[AREA_FIELD][$area];                             
  }
  $areas = implode(', ', $tempArray);
  // Drupal Image
  $uid = CRM_Core_BAO_UFMatch::getUFId($row->civicrm_address_contact_id);
  $user = user_load($uid);          
}
?>
<div class="views-field views-field-display-name">
  <span class="field-content"><a href=<?php echo $contactLink; ?>><?php echo $displayName; ?></a></span>
</div>  
<div class="views-field views-field-state">
  <span class="field-content"><?php echo CRM_Core_PseudoConstant::stateProvinceAbbreviation($row->civicrm_address_state_province_id); ?></span>
</div>  
<div class="views-field views-field-phone">
  <span class="field-content"><?php echo $phoneDiv; ?></span>
</div>  
<div class="views-field views-field-area">
  <span class="field-content"><?php echo $areas; ?></span>
</div>  
<div class="views-field views-field-picture">        
<div class="field-content"><a href="/mediator-profile/198">  
<div class="user-picture">
<?php if (empty($user->picture)) {
 echo '<img typeof="foaf:Image" class="image-style-none" src="http://mcfm.hoster904.com/sites/default/files/user_pictures/filler_profile_pic.jpg" alt="yourfamilymatters\'s picture" title="yourfamilymatters\'s picture">';   
 } 
 else {
  echo theme('user_picture', array('account' => $user));
 } 
?> 
</div>
</a>
</div>
</div> 