<?php

/**
 * Implements hook_panels_layouts().
 */
function oc_omega_omega_12_threecol_6_3_3_panels_layouts() {
  $items['omega_12_threecol_6_3_3'] = array(
    'title' => t('Three column 6_3_3'),
    'category' => t('Omega: 12 Column Grid'),
    'icon' => 'omega_12_threecol_6_3_3.png',
    'theme' => 'omega_12_threecol_6_3_3',
    'admin css' => 'omega_12_threecol_6_3_3.css',
    'regions' => array(
      'left' => t('Left side'),
      'middle' => t('Middle column'),
      'right' => t('Right side')
    ),
  );

  return $items;
}
