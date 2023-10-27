<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Estoque APP</title>
    <link rel="shortcut icon" type="image/png" href="{{asset('img/logo.png')}}"/>

    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-rqn26AG5Pj86AF4SO72RK5fyefcQ/x32DNQfChxWvbXIyXFePlEktwD18fEz+kQU" crossorigin="anonymous">

    <!-- Theme style -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="{{asset('css/AdminLTE.css')}}">
    <link rel="stylesheet" href="https://cdn.rawgit.com/raphaelfabeni/css-loader/7090f306bff5627b5b94c3607306838db7df7396/dist/css-loader.css">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('css/skin-blue.css')}}">
    <link rel="stylesheet" href="{{asset('css/dataTable.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/jquery-te-1.4.0.css')}}">
    <link rel="stylesheet" href="{{url("css/vue-animate.css")}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/summernote/0.8.9/summernote.css" rel="stylesheet">

    <script src="https://unpkg.com/vue-multiselect@2.1.0"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>


    <link rel="stylesheet" href="https://unpkg.com/vue-multiselect@2.1.0/dist/vue-multiselect.min.css">


    <script src="{{asset('js/jquery.min.js')}}"></script>
    <script src="{{asset('js/vue.js')}}"></script>
    <script src="{{asset('js/jquery.maskMoney.js')}}"></script>

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">

    <style>
        .submenu {
            display: none;
            position: absolute;
            top: 0;
            left: 100%;
            background-color: #ff90fc;
            padding: 10px;
            border: 1px solid #ddd;
            z-index: 1;
            width: 250px;
        }
        .parent:hover .submenu {
            display: block;
        }
        .submenu li {
            list-style: none;
            margin: 20px 0;
        }
        .submenu a {
            color: #fff;
            text-decoration: none;
        }
        .submenu a:hover {
            color: #0056b3;
        }
        .menu-item {
            list-style: none;
            margin: 5px 0;
            cursor: pointer;
        }
        .menu-item .menu-item-wrapper {
            display: flex;
            align-items: center;
        }
        .menu-item:hover .submenu {
            display: block;
        }
        .menu-item-wrapper {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            text-align: left;
        }
        .menu-item-wrapper img {
            height: 25px;
            width: 25px;
            margin-right: 10px;
        }
        .sidebar {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            align-items: flex-start;
            height: 100%;
            padding-left: 10px;
        }
        .menu-item-wrapper span {
            font-size: 18px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            color: #fff
        }
    </style>
</head>
<body class="skin-blue sidebar-mini">
<div class="wrapper">
    <header class="main-header">
        <!-- Logo -->
        <a href="#" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">
                <img style="width:calc(100% - 6px); margin-left: 3px; margin-right: 3px;"
                     src="{{asset('img/logo.png')}}"></span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg"><img style="height: 50px; margin-right: 50px; margin-bottom: 50px;"
                                       src="{{asset('img/logo.png')}}"> </span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                <i class="fal fa-bars"></i>
                <span class="sr-only">Toggle navigation</span>
            </a>

            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">

                    <!-- User Account: style can be found in dropdown.less -->
                    <li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">

                        </a>

                        <ul class="dropdown-menu">
                            <li class="user-footer">
                                <a href="{{route('logout-admin')}}" class="dropdown-toggle">
                                    <span class="fal fa-sign-out-alt"></span> Sair
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar" style="height: auto;">
            <ul class="sidebar-menu tree" data-widget="tree">
                <li class="menu-item">
                    <div class="menu-item-wrapper">
                        <a href="{{route('dash')}}">
                            <i class="fas fa-home" style="color: white;"></i>
                            <span>&nbsp Home</span>
                        </a>
                    </div>
                </li>
                <li class="menu-item parent">
                    <div class="menu-item-wrapper">
                        <i class="fas fa-archive" style="color: white;"></i>
                        <span>&nbsp Produtos</span>
                    </div>
                    <ul class="submenu">
                        <li><a href="{{route('products')}}"><i class="fas fa-search" style="color: white;"></i>&nbsp Buscar Produto</a></li>
                        <li><a href="{{route('product-quick-registration')}}"><i class="fas fa-rocket" style="color: white;"></i>&nbsp Cadastro Rápido de Produtos</a></li>
                        <li><a href="{{route('product-full-registration')}}"><i class="fas fa-plus" style="color: white;"></i>&nbsp Cadastro Completo de Produtos</a></li>
                        <li><hr></li>
                        <li><a href="{{route('product-entry')}}"><i class="fas fa-arrow-left" style="color: white;"></i>&nbsp Entrada de Produtos</a></li>
                        <li><a href="{{route('product-output')}}"><i class="fas fa-arrow-right" style="color: white;"></i>&nbsp Saída de Produtos</a></li>
                        <li><hr></li>
                        <li><a href="{{route('product-quick-registration')}}"><i class="fas fa-th-large" style="color: white;"></i>&nbsp Cadastro de Categoria</a></li>
                    </ul>
                </li>
                <li class="menu-item">
                    <div class="menu-item-wrapper">
                        <a href="{{route('reports')}}">
                            <i class="fas fa-chart-pie" style="color: white;"></i>
                            <span>&nbsp Relatórios</span>
                        </a>

                    </div>
                </li>
            </ul>
        </section>
    </aside>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Main content -->
        <section class="content">
            @yield('content')
        </section>
    </div>

</div>
</body>

<!-- jQuery 3 -->

<!-- jQuery UI 1.11.4 -->
<script src="{{asset('js/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{asset('js/bootstrap.min.js')}}"></script>
<!-- Slimscroll -->
<script src="{{asset('js/jquery-te-1.4.0.min.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.15/jquery.mask.js"></script>

<!-- AdminLTE App -->
<script src="{{asset('js/adminlte.min.js')}}"></script>
