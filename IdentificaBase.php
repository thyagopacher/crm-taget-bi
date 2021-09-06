<?php

$conexao1 = mysqli_connect('localhost', 'admin_targetuser', 'hKTCnwne3e', 'admin_targetcrm', '3306');
mysqli_set_charset($conexao1, 'utf8');

if(!isset($_POST["usuario"]) || $_POST["usuario"] == NULL || $_POST["usuario"] == ""){
    die(json_encode(array('mensagem' => 'Por favor preencha usuário', 'situacao' => false)));
}
if(!isset($_POST["senha"]) || $_POST["senha"] == NULL || $_POST["senha"] == ""){
    die(json_encode(array('mensagem' => 'Por favor preencha senha', 'situacao' => false)));
}

$sql = "select db_name, db_user, db_pass, id 
from admin_targetcrm.db_contas_usuarios 
where usuario = '{$_POST["usuario"]}' 
and senha = '".md5($_POST["senha"])."'";

$resusuario = mysqli_query($conexao1, $sql);
$usuario = mysqli_fetch_array($resusuario);

if(!isset($usuario) || $usuario["db_name"] == NULL || $usuario["db_name"] == ""){
    $sql = "select id
    from admin_targetcrm.db_usuario 
    where usuario = '{$_POST["usuario"]}' 
    and senha = '".md5($_POST["senha"])."'";
    $resusuario = mysqli_query($conexao1, $sql);
    $usuario = mysqli_fetch_array($resusuario);    
    if(isset($usuario["id"]) && $usuario["id"] != NULL && $usuario["id"] != ""){
        die(json_encode(array('id' => $usuario["id"], 'situacao' => true)));
    }else{
        die(json_encode(array('mensagem' => 'Não pode achar o usuário!', 'situacao' => false)));
    }
}else{
    die(json_encode(array('id' => $usuario["id"], 'situacao' => true)));
}

