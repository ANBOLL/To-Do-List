<!DOCTYPE html>
<html lang="ru">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>To-Do List API — Swagger</title>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5/swagger-ui.css">
</head>

<body>
	<div id="swagger-ui"></div>
	<script src="https://cdn.jsdelivr.net/npm/swagger-ui-dist@5/swagger-ui-bundle.js"></script>
	<script>
		SwaggerUIBundle({
			url: '/swagger.json',
			dom_id: '#swagger-ui',
			presets: [SwaggerUIBundle.presets.apis],
			layout: 'BaseLayout',
		});
	</script>
</body>

</html>