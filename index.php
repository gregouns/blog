<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

if (isset($_GET['page'])) {
	var_dump($_GET['page']);
	switch ($_GET['page']) {
		case 'accueil':
			$page = './page/home.php';
			break;

		case 'post':
			$page = './page/post.php';
		
		default:
			$page = './page/home.php';
			break;
	}
}
include ('./common/header.php');
include ('./common/navigation.php');
include ('./common/footer.php');