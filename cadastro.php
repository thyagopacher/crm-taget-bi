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
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.1.1/css/responsive.bootstrap.min.css">

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

            if (username == null || username == "" || password == null || password == "") {
                alert("Não pode acessar cadastro sem estar logado, por favor verifique!");
                setTimeout('location.href="/cadastro.php";', 1500);
            }
        </script>
    </head>
    <body>
        <div id="elemento" class="container-fluid text-center">    
            <div class="row content">
                <div class="col-sm-1">
                </div>
                <div class="col-sm-10 text-left"> 
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#home">Cadastro</a></li>
                        <li><a data-toggle="tab" href="#menu1">Procurar</a></li>
                    </ul>

                    <div class="tab-content">
                        <div id="home" class="tab-pane fade in active">
                            <h3>Cadastro</h3>
                            <form method="post" name="fcadastro" id="fcadastro" action="SalvarCliente.php">
                                <input type="hidden" name="idusuario" id="idusuario" value=""/>
                                <input type="hidden" name="ID_CLIENTE" id="ID_CLIENTE" value=""/>
                                <div class="form-group col-md-4">
                                    <label>Nome:</label>
                                    <select name="TIPO_PESSOA" id="TIPO_PESSOA" class="form-control">
                                        <option value="FISICA">FISICA</option>
                                        <option value="JURIDICA">JURIDICA</option>
                                    </select>
                                </div>		
                                <div class="form-group col-md-4">
                                    <label>Nome:</label>
                                    <input required maxlength="45" type="text" class="form-control" name="NOME" id="NOME">
                                </div>		
                                <div class="form-group col-md-4">
                                    <label>
                                        <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                        E-mail:
                                    </label>
                                    <input maxlength="100" type="email" class="form-control" name="EMAIL" id="EMAIL">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                        Telefone:
                                    </label>
                                    <input maxlength="100" type="text" class="form-control telefone" name="TELEFONE_PRINCIPAL" id="TELEFONE_PRINCIPAL">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>
                                        <i class="fa fa-mobile" aria-hidden="true"></i>
                                        Celular:
                                    </label>
                                    <input maxlength="100" type="text" class="form-control celular" name="CELULAR_PRINCIPAL" id="CELULAR_PRINCIPAL">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                        Data de nascimento:
                                    </label>
                                    <input type="date" max="<?= date("Y-m-d") ?>" class="form-control" name="DATA_NASCIMENTO" id="DATA_NASCIMENTO">
                                </div>
                                <div class="form-group col-md-3">
                                    <label>CEP:</label>
                                    <input maxlength="8" type="text" class="form-control inteiro" name="cep" id="cep">
                                </div>	
                                <div class="form-group col-md-2">
                                    <label>Tipo Logradouro:</label>
                                    <input maxlength="100" type="text" class="form-control" name="tipo_logradouro" id="tipo_logradouro">
                                </div>		  
                                <div class="form-group col-md-8">
                                    <label>Endereço:</label>
                                    <input maxlength="100" type="text" class="form-control" name="endereco" id="endereco">
                                </div>
                                <div class="form-group col-md-2">
                                    <label>Número:</label>
                                    <input maxlength="100" type="text" class="form-control" name="numero_endereco" id="numero_endereco">
                                </div>	
                                <div class="form-group col-md-3">
                                    <label>Bairro:</label>
                                    <input maxlength="100" type="text" class="form-control" name="bairro" id="bairro">
                                </div>		  
                                <div class="form-group col-md-3">
                                    <label>UF:</label>
                                    <select name="cod_uf" id="cod_uf" class="form-control">
                                        <?php
                                        $resestado = mysqli_query($conexao1, "select cod_estados, nome from admin_targetcrm.db_estados order by nome");
                                        $qtdestado = mysqli_num_rows($resestado);
                                        if ($qtdestado > 0) {
                                            echo "<option value=''>--Selecione--</option>";
                                            while ($estado = mysqli_fetch_array($resestado)) {
                                                echo '<option value="' . $estado["cod_estados"] . '">' . $estado["nome"] . '</option>';
                                            }
                                        } else {
                                            echo "<option value=''>--Nada encontrado--</option>";
                                        }
                                        ?>			
                                    </select>
                                </div>	
                                <div class="form-group col-md-6">
                                    <label>Cidade:</label>
                                    <select name="cod_cidade" id="cod_cidade" class="form-control">
                                        <?php
                                        $rescidade = mysqli_query($conexao1, "select estados_cod_estados, cod_cidades, nome from admin_targetcrm.db_cidades order by nome");
                                        $qtdcidade = mysqli_num_rows($rescidade);
                                        if ($qtdcidade > 0) {
                                            echo "<option value=''>--Selecione--</option>";
                                            while ($cidade = mysqli_fetch_array($rescidade)) {
                                                echo '<option value="' . $cidade["cod_cidades"] . '">' . $cidade["nome"] . '</option>';
                                            }
                                        } else {
                                            echo "<option value=''>--Nada encontrado--</option>";
                                        }
                                        ?>
                                    </select>
                                </div>	
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-default">Salvar</button>
                                    <a href="javascript: btNovo();" class="btn btn-default">Novo</a>
                                </div>		  

                            </form>
                        </div>
                        <div id="menu1" class="tab-pane fade">
                            <h3>Procurar</h3>
                            <form method="post" name="fprocurar" id="fprocurar">
                                <input type="hidden" name="idusuario" id="idusuario2" value=""/>
                                <div class="form-group col-md-3">
                                    <label>
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                        Data Inicio:
                                    </label>
                                    <input type="date" class="form-control" name="DATA_CADASTRO1">
                                </div>		
                                <div class="form-group col-md-3">
                                    <label>
                                        <i class="fa fa-calendar" aria-hidden="true"></i>
                                        Data Fim:
                                    </label>
                                    <input type="date" class="form-control" name="DATA_CADASTRO2">
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Nome:</label>
                                    <input maxlength="100" type="text" class="form-control" name="NOME">
                                </div>
                                <div class="col-md-12">
                                    <button onclick="procurarCliente()" type="button" class="btn btn-default">
                                        <i class="fa fa-search" aria-hidden="true"></i>
                                        Procurar
                                    </button>
                                </div>		  


                            </form>

                            <div id="listagem_clientes" class="col-md-12"></div>
                        </div>

                    </div>		
                </div>
                <div class="col-sm-1">
                </div>
            </div>
        </div>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.css" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css" />

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-migrate/3.0.0/jquery-migrate.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.2.1/jquery.form.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.10/jquery.mask.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.15/js/jquery.dataTables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>

        <script src="https://cdn.datatables.net/responsive/2.1.1/js/dataTables.responsive.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

        <script>
                                        if (username != null && username != "" && password != null && password != "") {
                                            $.ajax({
                                                url: "IdentificaBase.php",
                                                type: "POST",
                                                data: {usuario: username, senha: password},
                                                dataType: 'json',
                                                success: function (data, textStatus, jqXHR) {
                                                    if (data.situacao == true) {
                                                        $("#idusuario").val(data.id);
                                                        $("#idusuario2").val(data.id);
                                                    }
                                                }, error: function (jqXHR, textStatus, errorThrown) {
                                                    console.log(errorThrown);
                                                }
                                            });

                                        }
                                        $(".celular").mask('(99)99999-9999');
                                        $(".telefone").mask('(99)9999-9999');
                                        if ($(".inteiro").length) {
                                            $('.inteiro').keypress(function (event) {
                                                var tecla = (window.event) ? event.keyCode : event.which;
                                                if ((tecla > 47 && tecla < 58)) {
                                                    return true;
                                                } else {
                                                    if (tecla !== 8) {
                                                        return false;
                                                    } else {
                                                        return true;
                                                    }
                                                }
                                            });
                                        }


                                        if (!Modernizr.inputtypes.date) {
                                            if ($("input[type='date']").length) {
                                                $("input[type='date']").datepicker({/**usado para input text*/
                                                    dateFormat: 'dd/mm/yy',
                                                    dayNames: ['Domingo', 'Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado'],
                                                    dayNamesMin: ['D', 'S', 'T', 'Q', 'Q', 'S', 'S', 'D'],
                                                    dayNamesShort: ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
                                                    monthNames: ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'],
                                                    monthNamesShort: ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'],
                                                    nextText: 'Próximo',
                                                    prevText: 'Anterior',
                                                    maxDate: "2099-12-30"
                                                });
                                                $("input[type='date']").mask("99/99/9999");
                                            }
                                        }

                                        function btNovo() {
                                            location.href = '/cadastro.php';
                                        }

                                        function excluirCliente(codigo) {
                                            $.ajax({
                                                url: "ExcluirCliente.php",
                                                type: "POST",
                                                data: {idusuario: $("#idusuario").val(), ID_CLIENTE: codigo},
                                                dataType: 'json',
                                                success: function (data, textStatus, jqXHR) {
                                                    if (data.situacao == true) {
                                                        swal("Cliente", data.mensagem, "success");
                                                        procurarCliente();
                                                    } else {
                                                        swal("Atenção", data.mensagem, "info");
                                                    }
                                                }, error: function (jqXHR, textStatus, errorThrown) {
                                                    console.log(errorThrown);
                                                }
                                            });
                                        }

                                        function procurarClienteCodigo(codigo) {
                                            $.ajax({
                                                url: "ProcurarClienteCodigo.php",
                                                type: "POST",
                                                data: {idusuario: $("#idusuario").val(), codcliente: codigo},
                                                dataType: 'json',
                                                success: function (data, textStatus, jqXHR) {
                                                    console.log(data);
                                                    for (var k in data) {

                                                        if (!Number.isInteger(parseInt(k))) {
                                                            console.log('ID: ' + k + ' - VL: ' + data[k]);
                                                            $("#" + k).val(data[k]);
                                                        }
                                                    }
                                                    $('.nav-tabs a[href="#home"]').tab('show');
                                                }, error: function (jqXHR, textStatus, errorThrown) {
                                                    swal("Erro", errorThrown, "error");
                                                }
                                            });
                                        }

                                        function procurarCliente() {
                                            $.ajax({
                                                url: "ProcurarCliente.php",
                                                type: "POST",
                                                data: $("#fprocurar").serialize(),
                                                dataType: 'text',
                                                success: function (data, textStatus, jqXHR) {
                                                    $("#listagem_clientes").html(data);
                                                    $('#tcliente').DataTable();
                                                }, error: function (jqXHR, textStatus, errorThrown) {
                                                    swal("Erro", errorThrown, "error");
                                                }
                                            });
                                        }

                                        $(function () {
                             
                                            $("#fcadastro").submit(function () {
                                                $(".progress").css("visibility", "visible");
                                            });

                                            var progress = $(".progress");
                                            var progressbar = $("#progressbar");
                                            var sronly = $("#sronly");

                                            $('#fcadastro').ajaxForm({
                                                beforeSend: function () {
                                                    progress.show();
                                                    var percentVal = '0%';
                                                    progressbar.width(percentVal);
                                                    sronly.html(percentVal + ' Completo');
                                                },
                                                uploadProgress: function (event, position, total, percentComplete) {
                                                    var percentVal = percentComplete + '%';
                                                    progressbar.width(percentVal);
                                                    sronly.html(percentVal + ' Completo');
                                                },
                                                success: function () {
                                                    var percentVal = '100%';
                                                    progressbar.width(percentVal);
                                                    sronly.html(percentVal + ' Completo');
                                                },
                                                complete: function (xhr) {
                                                    var data = JSON.parse(xhr.responseText);
                                                    if (data.situacao === true) {
                                                        swal("Cliente", data.mensagem, "success");
                                                        procurarCliente(true);
                                                        progress.hide();

                                                        $("input[type='text'], select, input[type='date'], input[type='email']").val("");
                                                    } else if (data.situacao === false) {
                                                        swal("Erro", data.mensagem, "error");
                                                    }
                                                }
                                            });
                                        });
        </script>
    </body>
</html>