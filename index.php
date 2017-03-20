<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('BASE_DIR', dirname(__FILE__));

require_once (BASE_DIR . '/config/config.php');
require_once (BASE_DIR . '/libraries/functions.php');

if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case 'accueil':
			$page  = '/page/home.php';
			$title = "home";
			break;

		case 'post':
			$page  = '/page/post.php';
			$title = "post";
			break;

		case 'single':
			$page  = '/page/single.php';
			$title = "single";
			break;

		case 'tags':
			$page  = '/page/tags.php';
			$title = "tags";
			break;

		case 'formulaire':
			$page  = '/page/formulaire.php';
			$title = "formulaire";
			break;

		case 'categorie':
			$page  = '/page/categorie.php';
			$title = "categorie";
			break;

		case 'edit':
			$page  = '/page/edit.php';
			$title = "edit";
			break;

		case 'edit_tag':
			$page  = '/page/edit_tag.php';
			$title = "edit_tag";
			break;

		default:
			$title = "Erreur";
			$page  = '/page/erreur404.php';
			break;
	}
}
else {
	$page  = '/page/home.php';
	$title = "home";
	$_GET['page'] = '';
}

// $page = str_replace('/', '\\', $page);

$_GLOBALS['page'] = $page;

$cnt = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS, MYSQL_DB);
mysqli_query($cnt, "SET NAMES 'utf8'");

require_once (BASE_DIR . '/common/header.php');
require_once (BASE_DIR . '/common/navigation.php');
require_once (BASE_DIR . $page );
require_once (BASE_DIR . '/common/footer.php');

mysqli_close($cnt);
?>