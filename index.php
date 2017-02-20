<?php
ini_set('display_errors', true);
error_reporting(E_ALL);

if (isset($_GET['page'])) {
	var_dump($_GET['page']);
	switch ($_GET['page']) {
		case 'accueil':
			$page = './page/home.php';
			$title = 'home';
			break;

		case 'post':
			$page = './page/post.php';
			$title = 'post';
			break;

		default:
			$page = './page/home.php';
			$title = 'home';
			break;
	}
}
include ('./common/header.php');
include ('./common/navigation.php');
include ('./common/footer.php');