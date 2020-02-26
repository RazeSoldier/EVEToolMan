<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>添加主权监视者 - {{config('app.name')}}</title>
</head>
<body>
<form action="/sov-radar/add-watcher" method="post" autofocus="autofocus">
    @csrf
    <input type="submit" value="将自己添加为主权监视者">
</form>
</body>
</html>
