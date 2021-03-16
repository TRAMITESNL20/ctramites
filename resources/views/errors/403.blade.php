<!DOCTYPE html>
<html lang="en">
<head>
	<base href="{{ getenv("APP_URL") }}/">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Sessión sin Permiso | {{ Config::get("layout.args.title") }}</title>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,300;0,400;0,600;0,900;1,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="<?= assets("css/app.css")?>">
</head>
<body>
	<div id="error" class="error-403">
		<div class="error-text">
			<h1>¡Lo sentimos no tienes permisos para estar aquí!</h1>
			<p>Contacta con tu administrador si crees que esto es un error.</p>
		</div>
	</div>
</body>
</html>