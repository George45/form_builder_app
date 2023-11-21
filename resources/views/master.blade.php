<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Form Builder App</title>

		@vite(['resources/css/app.css'])
	</head>
	<body>
		@yield('body')
	</body>
	@vite(['resources/js/app.js'])
</html>
