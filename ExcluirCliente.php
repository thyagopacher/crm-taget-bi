<?php

session_start();
$conexao1 = mysqli_connect('localhost', 'admin_targetuser', 'hKTCnwne3e', 'admin_targetcrm', '3306');
mysqli_set_charset($conexao1, 'utf8');

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


    if (isset($_POST["ID_CLIENTE"]) && $_POST["ID_CLIENTE"] != NULL && $_POST["ID_CLIENTE"] != "") {
        

        /**
         * rotina para excluir padrão
         */
        $sql = "delete from $dbname.db_cliente where ID_CLIENTE = {$_POST["ID_CLIENTE"]}";
        $resExcluir1 = mysqli_query($conexao1, $sql);
    }
    if ($resExcluir1 != FALSE) {
        die(json_encode(array('mensagem' => 'Cliente excluido com sucesso!', 'situacao' => true)));
    } else {
        die(json_encode(array('mensagem' => 'Erro ao excluir cadastro de cliente causado por: ' . mysqli_error($conexao1), 'situacao' => false)));
    }
