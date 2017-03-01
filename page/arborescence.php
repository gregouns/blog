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
echo '<h2>choose your categories</h2>';
function afficher_menu($id_parent, $niveau, $array) {
 
$html = "";
 
foreach ($array AS $lien) {
 
	if ($id_parent == $lien['id_parent']) {
 
	for ($i = 0; $i < $niveau; $i++) $html .= "--";
 
	$html .= '<label><select id="cat'.$lien['id'].'"><option>' . $lien['category'] . '<option/></select></label><br />';
 
	$html .= afficher_menu($lien['id'], ($niveau + 1), $array);
 
	}
 
}
 
return $html;
 
}
echo afficher_menu(0, 0, $cat);
