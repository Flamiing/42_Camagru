<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="/public/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="/public/css/normalize.css"/>
	<link rel="stylesheet" href="/public/css/pico.css"/>
	<link rel="stylesheet" href="/public/css/styles.css">
	<title>Camagru</title>
</head>
<body>

	<!-- Header -->
	<?php include '/var/www/html/app/Views/shared/header.php'; ?>

	<!-- Main -->
	<?php include $data['content']; ?>

	<!-- Footer -->
	<?php include '/var/www/html/app/Views/shared/footer.php'; ?>

	<!-- Shared Scripts -->
	<script src="/public/js/sidebarActions.js"></script>
	<?php
		if (isset($data['scripts'])) {
			foreach ($data['scripts'] as $script) {
				if ($script['isModule']) {
					echo '<script type="module" src="/public/js/' . $script['script'] . '"></script>';
				} else{
					echo '<script src="/public/js/' . $script['script'] . '"></script>';
				}
			}
		}
	?>
</body>
</html>