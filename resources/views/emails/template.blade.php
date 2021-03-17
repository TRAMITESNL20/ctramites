<!DOCTYPE html>
<html lang="en">
<head>
	<base href="{{ getenv("APP_URL") }}">
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>#_EMAIL_PREHEADER_#</title>
	<link rel="stylesheet" href="{{ assets("css/app.css") }}">
</head>
<body>
<body id="nl-email-body">
	<div class="preheader" style="display: block; opacity: 0; font-size: 0px; color: #F2F3F7;">#_EMAIL_PREHEADER_#</div>
	<div id="nl-email">
		<header>
			<img src="{{ assets("images/logo.png") }}" alt="">
		</header>
		<content>
			<div class="card">
				<div class="card-header">#_EMAIL_HEADER_#</div>
				<div class="card-body">#_EMAIL_CONTENT_#</div>
			</div>
		</content>
	</div>
	<footer>
		{{ date("Y") }} &copy; Todos los Derechos Reservados | Gobierno del Estado de Nuevo León
	</footer>
</body>
</html>