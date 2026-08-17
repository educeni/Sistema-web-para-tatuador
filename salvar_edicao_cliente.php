<?php
$mat = $_GET['id_cliente'];

$con = mysqli_connect("sql309.infinityfree.com", "if0_42374528", "tattoolc0907", "if0_42374528_projeto_extensivo");
include 'valida_log.php';

$nome_cliente = $_POST["nome_cliente"];
$telefone = $_POST["telefone"];
$email = $_POST["email"];
$insta = $_POST["insta"];
$alergias = $_POST["alergias"];
$tipo_pele = $_POST["tipo_pele"];
$medicamentos = $_POST["medicamentos"];
$cond_saude = $_POST["cond_saude"];


if (!$con) {
    echo "erro, banco nao encontrado";
} else {
    $sql ="UPDATE `clientes` SET `nome_cliente`='$nome_cliente',
         `tel_cliente`='$telefone',`email_cliente`='$email',`insta_cliente`='$insta'
          WHERE id_cliente ='$mat'";

    $rs = mysqli_query($con, $sql);
    if ($rs) {
        $sql2 = "UPDATE `infor_cliente` SET `alergias`='$alergias',`tipo_pele`='$tipo_pele',
                `medicamentos`='$medicamentos',`cond_saude`='$cond_saude' 
                 WHERE id_cliente = '$mat'";

        $rs = mysqli_query($con, $sql2);
          if($rs){
        echo"<h1 class='teste'>Edição salva com sucesso!</h1>";
        echo "<a class='teste' href='cliente.php'>Volte aqui</a>";
    }
    else{
        echo"ERRO, volte agora";
        echo "<a href='cliente.php'>Clique aqui</a>";
    }

    } else {
        echo "ERRO AO ATUALIZAR PERFIL";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvar Edição Cliente</title>
</head>
<body>
    <?php include 'navbar.php';?>
</body>
</html>