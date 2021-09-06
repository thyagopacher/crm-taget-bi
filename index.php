<?php
session_start();
$conexao1 = mysqli_connect('localhost', 'admin_targetuser', 'hKTCnwne3e', 'admin_targetcrm', '3306');
mysqli_set_charset($conexao1, 'utf8');

$resusuario = mysqli_query($conexao1, "select db_name, db_user, db_pass from admin_targetcrm.db_contas_usuarios");
$usuario = mysqli_fetch_array($resusuario);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Cadastro</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

        <style>
            /* Remove the navbar's default margin-bottom and rounded borders */ 
            .navbar {
                margin-bottom: 0;
                border-radius: 0;
            }

            /* Set height of the grid so .sidenav can be 100% (adjust as needed) */
            .row.content {height: 450px}

            /* Set gray background color and 100% height */
            .sidenav {
                padding-top: 20px;
                background-color: #f1f1f1;
                height: 100%;
            }

            /* Set black background color, white text and some padding */
            footer {
                background-color: #555;
                color: white;
                padding: 15px;
            }

            /* On small screens, set height to 'auto' for sidenav and grid */
            @media screen and (max-width: 767px) {
                .sidenav {
                    height: auto;
                    padding: 15px;
                }
                .row.content {height:auto;} 
            }
        </style>
        <script>
            var username = localStorage.getItem("username");
            var password = localStorage.getItem("password");

        </script>
    </head>
    <body> 

        <div class="container">
            <div class="row">
                <div class="col-sm-6 col-md-4 col-md-offset-4">
                    <h1 class="text-center login-title">Login sistema</h1>
                    <div class="account-wall">
                        <form method="post" id="flogin" onsubmit="return false;" class="form-signin">
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                    E-mail
                                </label>
                                <input type="text" class="form-control" name="usuario" id="username" placeholder="usúario" required autofocus>
                            </div>
                            <div class="form-group">
                                <label>
                                    <i class="fa fa-key" aria-hidden="true"></i>
                                    Senha
                                </label>
                                <input type="password" class="form-control" name="senha" id="password" placeholder="senha" required>
                            </div>
                            <button id="btentrar" class="btn btn-lg btn-primary btn-block" type="button">
                                Entrar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>

        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script>
            function login(){
                $.ajax({
                    url: "IdentificaBase.php",
                    type: "POST",
                    data: $("#flogin").serialize(),
                    dataType: 'json',
                    success: function (data, textStatus, jqXHR) {
                        if (data.situacao == true) { 
                            localStorage.setItem('username', $("#username").val());
                            localStorage.setItem('password', $("#password").val());
                            localStorage.setItem('idusuario', data.id);
//                            location.href="cadastro.php";
                            window.open("cadastro.php", "_blank", "toolbar=no,scrollbars=no,resizable=yes,top=50,left=50,right=50,width=1200,height=800");
                        }else{
                            swal("Atenção", data.mensagem, "info");
                        }
                    }, error: function (jqXHR, textStatus, errorThrown) {
                        swal("Atenção", errorThrown, "error");
                    }
                });                 
            }
            
            if(username != null && username != "" && password != null && password != ""){
                $("#username").val(username);
                $("#password").val(password);
                login();
            }    
            
            $("#btentrar").click(function(){
               login();
            });

        </script>
    </body>
</html>