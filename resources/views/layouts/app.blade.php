<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AgroVision AI')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
<nav class="nav"><div class="container nav-inner"><a class="brand" href="{{ route('home') }}"><span class="leaf">◉</span> AgroVision AI</a><div class="nav-links">
@auth
<a href="{{ route('dashboard') }}">Dashboard</a><a href="{{ route('predictions.create') }}">Scan Leaf</a><a href="{{ route('predictions.index') }}">History</a>
@if(auth()->user()->is_admin)<a href="{{ route('admin.dashboard') }}">Admin</a>@endif
<form method="POST" action="{{ route('logout') }}" class="inline">@csrf<button class="link-button">Logout</button></form>
@else<a href="{{ route('login') }}">Login</a><a class="btn btn-sm" href="{{ route('register') }}">Create account</a>@endauth
</div></div></nav>
<main>
@if(session('success'))<div class="container"><div class="alert success">{{ session('success') }}</div></div>@endif
@if($errors->any())<div class="container"><div class="alert error"><strong>Please fix the following:</strong><ul>@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div></div>@endif
@yield('content')
</main>
<footer><div class="container">AgroVision AI · SE-331 Project · AI results are preliminary screening only.</div></footer>
</body></html>
