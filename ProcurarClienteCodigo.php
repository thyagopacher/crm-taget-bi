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

if(isset($_POST["codcliente"]) && $_POST["codcliente"] != NULL && $_POST["codcliente"] != ""){
    $and = " and db_cliente.ID_CLIENTE = '{$_POST["codcliente"]}'";
}

$sql = "SELECT * 
FROM  $dbname.db_cliente 
inner join $dbname.db_endereco on db_endereco.id_cliente = db_cliente.ID_CLIENTE 
where 1 = 1 {$and}";
$rescliente = mysqli_query($conexao1, $sql);
$cliente = mysqli_fetch_array($rescliente);
echo json_encode($cliente);
