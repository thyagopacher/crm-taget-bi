<?php


session_start();
$conexao1 = mysqli_connect('localhost', 'admin_targetuser', 'hKTCnwne3e', 'admin_targetcrm', '3306');
mysqli_set_charset($conexao1, 'utf8');
$and = "";

$sql = "select db_name, db_user, db_pass, id 
from admin_targetcrm.db_contas_usuarios 
where id = '{$_POST["idusuario"]}'";
$resusuario = mysqli_query($conexao1, $sql);
$usuario = mysqli_fetch_array($resusuario);
if (!isset($usuario) || $usuario["db_name"] == NULL || $usuario["db_name"] == "") {
    $sql = "select id
    from admin_targetcrm.db_usuario 
    where id = '{$_POST["idusuario"]}'";
    $resusuario = mysqli_query($conexao1, $sql);
    $usuario = mysqli_fetch_array($resusuario);
    if (isset($usuario["id"]) && $usuario["id"] != NULL && $usuario["id"] != "") {
        $dbname = "admin_targetcrm";
    }
} else {
    $dbname = $usuario["db_name"];
}

if(isset($_POST["DATA_CADASTRO1"]) && $_POST["DATA_CADASTRO1"] != NULL && $_POST["DATA_CADASTRO1"] != ""){
    if(strpos($_POST['DATA_CADASTRO1'], '/')){
        $data = implode("-",array_reverse(explode('/', $_POST['DATA_CADASTRO1'])));
    }else{
        $data = $_POST['DATA_CADASTRO1'];
    } 
    $and .= " and c.DATA_CADASTRO >= '{$data}'";
}

if(isset($_POST["DATA_CADASTRO2"]) && $_POST["DATA_CADASTRO2"] != NULL && $_POST["DATA_CADASTRO2"] != ""){
    if(strpos($_POST['DATA_CADASTRO2'], '/')){
        $data = implode("-",array_reverse(explode('/', $_POST['DATA_CADASTRO2'])));
    }else{
        $data = $_POST['DATA_CADASTRO2'];
    } 
    $and .= " and c.DATA_CADASTRO <= '{$data}'";
}
if(isset($_POST["NOME"]) && $_POST["NOME"] != NULL && $_POST["NOME"] != ""){
    $and .= " and c.NOME like '%{$_POST["NOME"]}%'";
}
$sql = "SELECT c.* 
FROM  $dbname.db_cliente as c where 1 = 1 {$and}";
$rescliente = mysqli_query($conexao1, $sql);
$qtdcliente = mysqli_num_rows($rescliente);
if($qtdcliente > 0){
    echo '<table id="tcliente" class="table table-striped table-bordered dt-responsive nowrap" cellspacing="0" width="100%">';
    echo '<thead>';
    echo '<tr>';
    echo '<th>Nome</th>';
    echo '<th>E-mail</th>';
    echo '<th>Telefone</th>';
    echo '<th>Celular</th>';
    echo '<th>Dt. Cadastro</th>';
    echo '<th>Opções</th>';
    echo '</tr>';
    echo '</thead>';
    while($clientep = mysqli_fetch_array($rescliente)){
        echo '<tr>';
        echo '<td>',$clientep["NOME"],'</td>';
        echo '<td>',$clientep["EMAIL"],'</td>';
        echo '<td>',$clientep["TELEFONE_PRINCIPAL"],'</td>';
        echo '<td>',$clientep["CELULAR_PRINCIPAL"],'</td>';
        echo '<td data-order="',$clientep["DATA_CADASTRO"],'">';
        if(isset($clientep["DATA_CADASTRO"]) && $clientep["DATA_CADASTRO"] != NULL && $clientep["DATA_CADASTRO"] != ""){
            echo date("d/m/Y", strtotime($clientep["DATA_CADASTRO"]));
        }
        echo '</td>';
        echo '<td>';
        echo '<a title="clique aqui para editar o cliente" href="javascript: procurarClienteCodigo(',$clientep["ID_CLIENTE"],')"><i class="fa fa-pencil" aria-hidden="true"></i></a> ';
        echo '<a title="clique aqui para excluir o cliente" href="javascript: excluirCliente(',$clientep["ID_CLIENTE"],')"><i class="fa fa-trash-o" aria-hidden="true"></i></a> ';
        echo '</td>';
        echo '</tr>';
    }
    echo '</table>';
}
