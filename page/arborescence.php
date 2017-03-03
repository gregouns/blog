<?php

$cnt = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
mysqli_query($cnt, "SET NAMES 'utf8'");

$cat = array();
$query = "SELECT * FROM categories ORDER BY name ASC";
$rst = mysqli_query($cnt,$query);

while ($arr = mysqli_fetch_array($rst)) {
	$cat[] = array(
		'id'        => $arr['id'],
		'id_parent' => $arr['id_parent'],
		'category'  => $arr['name']
	);
}
function afficher_menu($id_parent, $array) {
	$html = "";
	foreach ($array AS $lien) {
		if ($id_parent == $lien['id_parent']) {
			$html .= '<option id ="'.$lien['id'].'">' . $lien['category'] . '</option>';
			$html .= afficher_menu($lien['id'], $array);
		}
	}	 
	return $html; 
}
echo afficher_menu(0, $cat);