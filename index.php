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
	$page  = './pages/home.php';
	$title = "home";
}

include ('./common/header.php');
include ('./common/navigation.php');
require_once ( BASE_DIR . $page );
include ('./common/footer.php');
	
