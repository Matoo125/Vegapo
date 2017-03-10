<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Veg Produkty</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/bower_components/bootstrap/dist/css/bootstrap.min.css" />
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="/css/admin/theme.css">


</head>
<body >
<nav class="navbar navbar-fixed-top navbar-toggleable-sm navbar-inverse bg-primary mb-3">
    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#collapsingNavbar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand pb-2" href="/admin">VegaPo</a>
    <div class="navbar-collapse collapse" id="collapsingNavbar">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" href="/admin/users/update">Môj účet</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/users/">Užívatelia</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/admin/home/settings/">Nastavenia</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <a class="nav-link" target="_blank" href="/">Web</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="/users/logout">Odhlásiť sa</a>
            </li>
        </ul>
    </div>
</nav>

<div class="container-fluid" id="main">
    <div class="row row-offcanvas row-offcanvas-left">
        <div class="col-md-3 col-lg-2 sidebar-offcanvas" id="sidebar" role="navigation">
            <ul class="nav flex-column">
                <li class="nav-item"><a class="nav-link" href="/admin/produkty/ziadosti">Žiadosti</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/produkty/trash">Kôš</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/produkty">Produkty</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/obchody">Obchody</a></li>
                <li class="nav-item"><a class="nav-link" href="/admin/kategorie">Kategórie</a></li>
            </ul>
        </div>
        <!--/col-->
        <div class="col-md-9 col-lg-10 main">
