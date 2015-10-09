<?php
$params = array(
  'contact_id' => $row->civicrm_address_contact_id,
  'api.Phone.get' => array(
    'location_type_id' => 2,
    'phone_type_id' => 1,
  ),
  'api.CustomValue.get' => 1,
  'api.Membership.get' => 1,
);
$contact = civicrm_api3('Contact', 'get', $params);
$phoneDiv = '';
if ($contact['count'] != 0) {
  $displayName = '';
  if ($contact['values'][$row->civicrm_address_contact_id]['api.Membership.get']['count'] != 0) {
    foreach ($contact['values'][$row->civicrm_address_contact_id]['api.Membership.get']['values'] as $key => $memberships) {
      if ($memberships['membership_type_id'] == 5) {
        $displayName .= "<div class='mcfm_certified'><a href='certified-mediator'><img src='/sites/default/files/icons/mcfm-certified-member_38h.png'></a></div>";
        break;
      }
    }
  }
  $contactLink = "/civicrm/contact/view?reset=1&cid={$row->civicrm_address_contact_id}";
  $displayName .= "<a href='" . $contactLink . "'>" . $contact['values'][$row->civicrm_address_contact_id]['display_name'] . "</a>";
  if ($contact['values'][$row->civicrm_address_contact_id]['api.Phone.get']['count'] != 0) {
    foreach ($contact['values'][$row->civicrm_address_contact_id]['api.Phone.get']['values'] as $phones) {
      $phoneDiv .= "<a href='tel:" . $phones['phone'] . "'>" . $phones['phone'] . "</a><br/>";
    }
  }
  $tempArray = $areas = array();
  // Areas of interest
  if (!empty($contact['values'][$row->civicrm_address_contact_id]['api.CustomValue.get']['values']) && !empty($contact['values'][$row->civicrm_address_contact_id]['api.CustomValue.get']['values'][2]['latest'])) {
    $areas = array_filter($contact['values'][$row->civicrm_address_contact_id]['api.CustomValue.get']['values'][2]['latest']);
  } 
  // Display Name
  if (!empty($contact['values'][$row->civicrm_address_contact_id]['api.CustomValue.get']['values']) && !empty($contact['values'][$row->civicrm_address_contact_id]['api.CustomValue.get']['values'][8]['latest'])) {
    $disName = $contact['values'][$row->civicrm_address_contact_id]['api.CustomValue.get']['values'][8]['latest'];
    $disName .= "<a href='" . $contactLink . "'>" . $disName . "</a>";
  }
  if (!empty($areas)) {
    foreach ($areas as $area) {
      $result = civicrm_api3('CustomField', 'getsingle', array(
                  'sequential' => 1,
                  'id' => AREAS,
                ));
      $options[AREAS] = array();
      CRM_Core_BAO_CustomField::buildOption($result, $options[AREAS]);
      $tempArray[] = $options[AREAS][$area];                             
    }
    $areas = implode(', ', $tempArray);
  }
  // Drupal Image
  $uid = CRM_Core_BAO_UFMatch::getUFId($row->civicrm_address_contact_id);
  if (!empty($uid)) {
    $user = user_load($uid);
  }
  $state = $row->civicrm_address_city;
  if (isset($row->civicrm_address_state_province_id)) {
    $state .= ', ' . CRM_Core_PseudoConstant::stateProvinceAbbreviation($row->civicrm_address_state_province_id);
  }
}
?>
<div class="views-field views-field-dis-name">
  <span class="field-content"><?php echo $disName; ?></span>
</div> 
<!-- <div class="views-field views-field-display-name">
  <span class="field-content"><?php /* echo $displayName; */ ?></span>
</div> -->  
<div class="views-field views-field-state">
  <span class="field-content"><?php echo $state; ?></span>
</div>  
<div class="views-field views-field-phone">
  <span class="field-content"><?php echo $phoneDiv; ?></span>
</div>  
<div class="views-field views-field-area">
  <span class="field-content"><?php if (!empty($areas)) {echo $areas;} ?></span>
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
