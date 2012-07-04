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

/**
 * adds classes on li tags based on titles (for menu icons).
 * 
 */
function oc_omega_links($variables) {
  $links = $variables['links'];
  $attributes = $variables['attributes'];
  $heading = $variables['heading'];
  global $language_url;
  $output = '';

  if (count($links) > 0) {
    $output = '';

    // Treat the heading first if it is present to prepend it to the
    // list of links.
    if (!empty($heading)) {
      if (is_string($heading)) {
        // Prepare the array that will be used when the passed heading
        // is a string.
        $heading = array(
          'text' => $heading,
          // Set the default level of the heading.
          'level' => 'h2',
        );
      }
      $output .= '<' . $heading['level'];
      if (!empty($heading['class'])) {
        $output .= drupal_attributes(array('class' => $heading['class']));
      }
      $output .= '>' . check_plain($heading['text']) . '</' . $heading['level'] . '>';
    }

    $output .= '<ul' . drupal_attributes($attributes) . '>';

    $num_links = count($links);
    $i = 1;

    foreach ($links as $key => $link) {
      $class = array($key);

      // Add first, last and active classes to the list of links to help out themers.
      if ($i == 1) {
        $class[] = 'first';
      }
      if ($i == $num_links) {
        $class[] = 'last';
      }
      if (isset($link['href']) && ($link['href'] == $_GET['q'] || ($link['href'] == '<front>' && drupal_is_front_page()))
          && (empty($link['language']) || $link['language']->language == $language_url->language)) {
        $class[] = 'active';
      }
      
      if (isset($attributes['id']) && $attributes['id'] == 'main-menu') {
  $custom_class = str_replace(' ','-',strtolower(check_plain($link['title'])));
  $class[] = $custom_class;
  //$link['attributes']['id'] = array($custom_class);
}
      $output .= '<li' . drupal_attributes(array('class' => $class)) . '>';

      if (isset($link['href'])) {
        // Pass in $link as $options, they share the same keys.
        $output .= l($link['title'], $link['href'], $link);
      }
      elseif (!empty($link['title'])) {
        // Some links are actually not links, but we wrap these in <span> for adding title and class attributes.
        if (empty($link['html'])) {
          $link['title'] = check_plain($link['title']);
        }
        $span_attributes = '';
        if (isset($link['attributes'])) {
          $span_attributes = drupal_attributes($link['attributes']);
        }
        $output .= '<span' . $span_attributes . '>' . $link['title'] . '</span>';
      }

      $i++;
      $output .= "</li>\n";
    }

    $output .= '</ul>';
  }

  return $output;
}



function oc_omega_form_alter(&$form, &$form_state, $form_id) {
	switch ($form_id) {
   case 'search_block_form':
     
	  $form['search_block_form']['#attributes']['placeholder'] = t('Search');
      $form['actions']['#suffix'] = '<div class="clearfix"></div>';
	  break;
    case 'user_login_block':
      $form['name']['#prefix'] = '<span class="login-text">'.t('Log in:').'</span>';
      unset($form['name']['#title']);
      $form['name']['#attributes']['placeholder'] = t('cpr. or card no.');
      unset($form['pass']['#title']);
      $form['links']['#markup'] = "";
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