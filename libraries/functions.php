<?php

function slugify($string, $replace = array(), $delimiter = '-') {
  // https://github.com/phalcon/incubator/blob/master/Library/Phalcon/Utils/Slug.php
  if (!extension_loaded('iconv')) {
    throw new Exception('iconv module not loaded');
  }
  // Save the old locale and set the new locale to UTF-8
  $oldLocale = setlocale(LC_ALL, '0');
  setlocale(LC_ALL, 'en_US.UTF-8');
  $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $string);
  if (!empty($replace)) {
    $clean = str_replace((array) $replace, ' ', $clean);
  }
  $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
  $clean = strtolower($clean);
  $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);
  $clean = trim($clean, $delimiter);
  // Revert back to the old locale
  setlocale(LC_ALL, $oldLocale);
  return $clean;
}


function toUL ($arr, $pass = 0) {
  $html = '<ul>' . PHP_EOL;
  foreach ( $arr as $v ) {
    $html.= '<li>';
    $html .= str_repeat("--", $pass); // use the $pass value to create the --
    $html .= $v['name'] . '</li>' . PHP_EOL;

    if ( array_key_exists('children', $v) ) {
      $html.= toUL($v['children'], $pass+1);
    }
  }
  $html.= '</ul>' . PHP_EOL;

  return $html;
}

function toSELECT ($arr, $pass = 0) {
  $html = '';
  if ($pass == 0) {
    $html = '<select name="category_parent" class="form-control">' . PHP_EOL;
    $html .= '<option value="-1">Sélectionnez</option>';
  }
  foreach ( $arr as $v ) {
    $html .= '<option value="'.$v['id'].'">';
    $html .= str_repeat("--", $pass); // use the $pass value to create the --
    $html .= $v['name'] . '</option>' . PHP_EOL;

    if ( array_key_exists('children', $v) ) {
      $html .= toSELECT($v['children'], $pass+1);
    }
  }
  if ($pass == 0) $html.= '</select>' . PHP_EOL;

  return $html;
}

?>