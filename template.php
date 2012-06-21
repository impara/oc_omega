<?php

/**
 * @file
 * This file is empty by default because the base theme chain (Alpha & Omega) provides
 * all the basic functionality. However, in case you wish to customize the output that Drupal
 * generates through Alpha & Omega this file is a good place to do so.
 * 
 * Alpha comes with a neat solution for keeping this file as clean as possible while the code
 * for your subtheme grows. Please read the README.txt in the /preprocess and /process subfolders
 * for more information on this topic.
 */
function block_render($module, $block_id) {  
$block = block_load($module, $block_id); 
$block_content = _block_render_blocks(array($block));  
$build = _block_get_renderable_array($block_content);  
$block_rendered = drupal_render($build);  
print $block_rendered;
}

function oc_omega_form_alter(&$form, &$form_state, $form_id) {
	switch ($form_id) {
   case 'search_block_form':
     
	  $form['search_block_form']['#attributes']['placeholder'] = t('Søgning');
	  break;
	   case 'comment_node_ding_news_form':
      $form['actions']['submit']['#prefix'] = '<div>';
      $form['actions']['submit']['#suffix'] = '</div>';
      $form['actions']['preview']['#prefix'] = '<div>';
      $form['actions']['preview']['#suffix'] = '</div>';
      $form['subject']['#type'] = 'hidden';
      break;
	}

}


function oc_omega_preprocess_search_result(&$variables) {
	unset($variables['sidebar_first'],$variables['sidebar_second']); 
  if ($variables['module'] == 'node') {	 
    $n = node_load($variables['result']['node']->nid);
    $view = node_view($n, 'teaser');
    $variables['search_result_body'] = isset($view['field_ding_body']) ? $view['field_ding_body'] : FALSE;
    $variables['search_result_type'] = isset($n->type) ? check_plain(node_type_get_name($n)) : FALSE;
    $variables['search_result_image'] = isset($view['field_list_image']) ? $view['field_list_image'] : FALSE;
    $variables['theme_hook_suggestions'][] = 'search_result__node_' . strtolower($variables['result']['type']);
  }
}

function oc_omega_preprocess_ting_object(&$variables) {
  $places = array(
    'ting_cover' => 'left',
    'ting_title' => 'right',
    'ting_abstract' => 'right',
    'ting_author' => 'right',
    'ting_type' => 'right',
    'ting_subjects' => 'right',
    'ting_series' => 'right',
    'ding_availability_item' => 'right',
  );
  $variables['content']['left'] = array();
  $variables['content']['right'] = array();

  foreach ($variables['content'] as $name => $render) {
    if (isset($places[$name])) {
      $variables['content'][$places[$name]][] = $render;
      unset($variables['content'][$name]);
    }
  }
}