<?php
    $con = mysqli_connect("sql309.infinityfree.com", "if0_42374528", "tattoolc0907", "if0_42374528_projeto_extensivo");
	include 'valida_log.php';
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);

    $nome_cliente = $_POST["nome_cliente"];
    $telefone = $_POST["telefone"];
    $email = $_POST["email"];
    $insta = $_POST["insta"]; 
    $alergias = $_POST["alergias"];
    $tipo_pele = $_POST["tipo_pele"];
    $medicamentos = $_POST["medicamentos"];
    $cond_saude = $_POST["cond_saude"];

    $sql = "INSERT INTO clientes (nome_cliente, tel_cliente, email_cliente, insta_cliente) VALUES ('$nome_cliente', '$telefone', '$email', '$insta')";

    $rs =  mysqli_query($con, $sql);
    $id_cliente = mysqli_insert_id($con);
    $sql2 = "INSERT INTO infor_cliente (id_cliente, alergias, tipo_pele, medicamentos, cond_saude) VALUES ('$id_cliente', '$alergias', '$tipo_pele', '$medicamentos', '$cond_saude')";
    $rs = mysqli_query($con, $sql2);   

    if($rs){
        echo"Cliente salvo com sucesso, volte para tela inicial:";
        echo "<a href='cliente.php'>Clique aqui</a>";
    }
    else{
        echo"ERRO, volte agr";
        echo "<a href='cliente.php'>Clique aqui</a>";
    }
?>