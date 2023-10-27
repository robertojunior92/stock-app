<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Admin</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.15.4/css/all.css" integrity="sha384-rqn26AG5Pj86AF4SO72RK5fyefcQ/x32DNQfChxWvbXIyXFePlEktwD18fEz+kQU" crossorigin="anonymous">    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/AdminLTE.css')}}?c=1">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('css/skin-blue.css')}}">

    <!-- Google Font -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <img src="{{asset('img/logo.png')}}" style="height: 200px;">
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">Insira seu usuário para iniciar a sessão</p>

        <form action="{{route('login-admin-post')}}" method="post">

            {!! csrf_field() !!}

            <div class="form-group has-feedback">
                <input name="email" type="email" class="form-control" placeholder="Email" required>
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
            </div>
            <div class="form-group has-feedback">
                <input name="password" type="password" class="form-control" placeholder="Senha" required>
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
            </div>
            @if($error)
                <div class="form-group alert alert-danger">
                    <p class="">Usuário ou senha inválidos</p>
                </div>
            @endif
            <div class="row">
                <div class="col-xs-4 col-xs-offset-8">
                    <button type="submit" class="btn btn-primary btn-block ">Entrar</button>
                </div>
            </div>
        </form>
    </div>
    <!-- /.login-box-body -->
</div>
</body>
</html>
