<?php

ini_set('display_errors', true);
error_reporting(E_ALL);

define('BASE_DIR', dirname(__FILE__));

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

$page = str_replace('/', '\\', $page);

$_GLOBALS['page'] = $page;

require_once (BASE_DIR . '/common/header.php');
require_once (BASE_DIR . '/common/navigation.php');
require_once ( BASE_DIR . $page );
require_once (BASE_DIR . '/common/footer.php');

?>