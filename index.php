<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

if (isset($_GET['page'])) {
	switch ($_GET['page']) {
		case 'accueil':
			$page = './pages/home.php';
			$title = "home";
			break;

		case 'post':
			$page = './pages/post.php';
			break;

		default:
			$title = "Erreur";
			$page  = './pages/erreur404.php';
			break;

	}
}
else { 
	$page = './pages/home.php';
	$title = "home";

}
	include ('./common/header.php');
	include ('./common/navigation.php');
	include ($page);
	include ('./common/footer.php');
	
