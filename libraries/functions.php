<?php
/**
 * Slugify - Transform a string to an url friendly word
 * @param  string $string
 * @param  array  $replace
 * @return string
 */
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

/**
 * isCategoryExists Check if category exists
 * @param  string  $cat
 * @param  integer $parent_id
 * @return boolean or id
 */
function isCategoryExists ($cat, $parent_id = 0) {
  global $cnt;
  $query = "SELECT * FROM categories WHERE name = '{$cat}' AND id_parent = '{$parent_id}' LIMIT 1";
  $rst = mysqli_query($cnt,$query);
  if (mysqli_num_rows($rst) > 0) {
    $arr = mysqli_fetch_array($rst);
    return $arr['id'];
  }
  return false;
}

/**
 * buildTree - To construct a multidimensional array for categories
 * @param  integer $cat_begin
 * @return array
 */
function buildTree ($cat_begin = 0) {
  global $cnt;

  if ($cat_begin > 0) {
    $query = "SELECT * FROM categories WHERE id_parent = '{$cat_begin}' ORDER BY name ASC";
  }
  else {
    $query = "SELECT * FROM categories WHERE id_parent = 0 ORDER BY name ASC";
  }
  $rst = mysqli_query($cnt,$query);

  if (mysqli_num_rows($rst) > 0) {
    while ($arr = mysqli_fetch_array($rst)) {
      $cat[slugify($arr['name'])] = array(
        'id'        => $arr['id'],
        'name'      => $arr['name'],
        'children' => buildTree($arr['id']),
      );
    }
  }
  else {
    return array();
  }

  return $cat;
}

/**
 * toUL - Generate a ul+li of categories
 * @param  array  $arr of categories
 * @param  integer $pass
 * @return string HTML
 */
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

/**
 * toSELECT - Generate a select+option form of categories
 * @param  array  $arr
 * @param  integer $pass
 * @param  string  $nameSelect
 * @return string HTML
 */
function toSELECT ($arr, $pass = 0, $nameSelect = 'category_parent') {
  $html = '';
  if ($pass == 0) {
    $html = '<select name="'.$nameSelect.'" class="form-control">' . PHP_EOL;
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
