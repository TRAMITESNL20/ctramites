<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Página no Encontrada | {{ Config::get("layout.args.title") }}</title>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100;0,300;0,400;0,600;0,900;1,700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="{{ assets("../css/app.css") }}">
</head>
<body>
	<div id="error" class="error-500">
		<div class="error-text">
			<h1>¡Algo salió mal!</h1>
			<p>Hubo un error en servidor. Por favor intenta más tarde.</p>
		</div>
	</div>
</body>
</html>