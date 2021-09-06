<?php

session_start();
$conexao1 = mysqli_connect('localhost', 'admin_targetuser', 'hKTCnwne3e', 'admin_targetcrm', '3306');
mysqli_set_charset($conexao1, 'utf8');

if (strpos($_POST['DATA_NASCIMENTO'], '/')) {
    $_POST['DATA_NASCIMENTO'] = implode("-", array_reverse(explode('/', $_POST['DATA_NASCIMENTO'])));
}

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

if (!isset($_POST["NOME"]) || $_POST["NOME"] == NULL || $_POST["NOME"] == "") {
    die(json_encode(array('mensagem' => 'Por favor digite nome!', 'situacao' => false)));
} else {
    if (isset($_POST["ID_CLIENTE"]) && $_POST["ID_CLIENTE"] != NULL && $_POST["ID_CLIENTE"] != "") {
        /**
         * atualiza cliente
         */
        $setar = "";
        if (isset($_POST["EMAIL"]) && $_POST["EMAIL"] != NULL && $_POST["EMAIL"] != "") {
            $setar = ", EMAIL = '{$_POST["EMAIL"]}'";
        }
        if (isset($_POST["TELEFONE_PRINCIPAL"]) && $_POST["TELEFONE_PRINCIPAL"] != NULL && $_POST["TELEFONE_PRINCIPAL"] != "") {
            $setar = ", TELEFONE_PRINCIPAL = '{$_POST["TELEFONE_PRINCIPAL"]}'";
        }
        if (isset($_POST["CELULAR_PRINCIPAL"]) && $_POST["CELULAR_PRINCIPAL"] != NULL && $_POST["CELULAR_PRINCIPAL"] != "") {
            $setar = ", CELULAR_PRINCIPAL = '{$_POST["CELULAR_PRINCIPAL"]}'";
        }
        if (isset($_POST["DATA_NASCIMENTO"]) && $_POST["DATA_NASCIMENTO"] != NULL && $_POST["DATA_NASCIMENTO"] != "") {
            $setar = ", DATA_NASCIMENTO = '{$_POST["DATA_NASCIMENTO"]}'";
        }
        $sql = "update $dbname.db_cliente set NOME = '{$_POST["NOME"]}' {$setar} where ID_CLIENTE = {$_POST["ID_CLIENTE"]}";
        $resSalvar = mysqli_query($conexao1, $sql);
        
        if ($resSalvar != FALSE) {
            $setarEndereco = '';
            if (isset($_POST["cep"]) && $_POST["cep"] != NULL && $_POST["cep"] != "") {
                $setarEndereco = ", cep = '{$_POST["cep"]}'";
            }
            if (isset($_POST["tipo_logradouro"]) && $_POST["tipo_logradouro"] != NULL && $_POST["tipo_logradouro"] != "") {
                $setarEndereco = ", tipo_logradouro = '{$_POST["tipo_logradouro"]}'";
            }
            if (isset($_POST["endereco"]) && $_POST["endereco"] != NULL && $_POST["endereco"] != "") {
                $setarEndereco = ", endereco = '{$_POST["endereco"]}'";
            }
            if (isset($_POST["numero_endereco"]) && $_POST["numero_endereco"] != NULL && $_POST["numero_endereco"] != "") {
                $setarEndereco = ", numero_endereco = '{$_POST["numero_endereco"]}'";
            }
            if (isset($_POST["bairro"]) && $_POST["bairro"] != NULL && $_POST["bairro"] != "") {
                $setarEndereco = ", bairro = '{$_POST["bairro"]}'";
            }
            if (isset($_POST["cod_uf"]) && $_POST["cod_uf"] != NULL && $_POST["cod_uf"] != "") {
                $setarEndereco = ", cod_uf = '{$_POST["cod_uf"]}'";
            }
            if (isset($_POST["cod_cidade"]) && $_POST["cod_cidade"] != NULL && $_POST["cod_cidade"] != "") {
                $setarEndereco = ", cod_cidade = '{$_POST["cod_cidade"]}'";
            }
            $sql = " update $dbname.db_endereco set cep  = '{$_POST["cep"]}' {$setarEndereco} where id_cliente = '{$_POST["ID_CLIENTE"]}'";
            $resSalvarEnd = mysqli_query($conexao1, $sql);

            if ($resSalvarEnd == FALSE) {
                die(json_encode(array('mensagem' => 'Erro ao salvar endereço causado por: ' . mysqli_error($conexao1), 'situacao' => false)));
            }
        }
    } else {
        $and = "";
        if (isset($_POST["EMAIL"]) && $_POST["EMAIL"] != NULL && $_POST["EMAIL"] != "") {
            /**
             * verifica se o cliente não está duplicando com mesmo nome e e-mail evitando inserir ele
             */
            $sql = "select ID_CLIENTE from $dbname.db_cliente where NOME = '{$_POST["NOME"]}' and EMAIL = '{$_POST["EMAIL"]}'";
            $rescliente = mysqli_query($conexao1, $sql);
            $clientep = mysqli_fetch_array($rescliente);

            if (isset($clientep["ID_CLIENTE"]) && $clientep["ID_CLIENTE"] != NULL && $clientep["ID_CLIENTE"] != "") {
                die(json_encode(array('mensagem' => 'Por favor reveja cadastro esse cliente já foi cadastrado no ID: ' . $clientep["ID_CLIENTE"], 'situacao' => false)));
            }
        }

        /**
         * rotina para inserir padrão
         */
        $resSalvar = mysqli_query($conexao1, "insert into $dbname.db_cliente(DATA_CADASTRO, NOME, EMAIL, 
        TELEFONE_PRINCIPAL, CELULAR_PRINCIPAL, DATA_NASCIMENTO, TIPO_PESSOA) 
        values('".date("Y-m-d")."', '{$_POST["NOME"]}', '{$_POST["EMAIL"]}', '{$_POST["TELEFONE_PRINCIPAL"]}', '{$_POST["CELULAR_PRINCIPAL"]}', '{$_POST["DATA_NASCIMENTO"]}', '{$_POST["TIPO_PESSOA"]}')");
        $codigo = mysqli_insert_id($conexao1);
    }
    if ($resSalvar != FALSE) {
        if (isset($codigo) && $codigo != NULL && $codigo > 0) {
            $sql = "insert into $dbname.db_endereco(id_cliente, cep, tipo_logradouro, endereco, numero_endereco, bairro, cod_uf, cod_cidade) values
					($codigo, '{$_POST["cep"]}', '{$_POST["tipo_logradouro"]}', '{$_POST["endereco"]}', '{$_POST["numero_endereco"]}', 
					'{$_POST["bairro"]}', '{$_POST["cod_uf"]}', '{$_POST["cod_cidade"]}')";
            $resSalvarEnd = mysqli_query($conexao1, $sql);

            if ($resSalvarEnd == FALSE) {
                die(json_encode(array('mensagem' => 'Erro ao salvar endereço causado por: ' . mysqli_error($conexao1), 'situacao' => false)));
            }
        }
        die(json_encode(array('mensagem' => 'Cadastro de cliente salvo com sucesso!', 'situacao' => true)));
    } else {
        die(json_encode(array('mensagem' => 'Erro ao salvar cadastro de cliente causado por: ' . mysqli_error($conexao1), 'situacao' => false)));
    }
}