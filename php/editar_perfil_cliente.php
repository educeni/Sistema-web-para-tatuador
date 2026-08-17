<?php
$mat = (int)$_GET['id_cliente'];
$con = mysqli_connect("sql309.infinityfree.com", "if0_42374528", "tattoolc0907", "if0_42374528_projeto_extensivo");
include 'valida_log.php';
$sql = "SELECT 
    c.id_cliente,
    c.nome_cliente, 
    c.tel_cliente, 
    c.insta_cliente,
    c.email_cliente, 
    MAX(ag.data_agen),
    MAX(ag.status_agen),
    SUM(ag.valor_agen) AS total_gasto,
    count(*) as total_sessoes,
    ic.alergias,
    ic.tipo_pele,
    ic.medicamentos,
    ic.cond_saude
    FROM clientes c
    left JOIN agendamento ag ON c.id_cliente = ag.id_cliente
    INNER JOIN infor_cliente ic ON ic.id_cliente = c.id_cliente
    WHERE c.id_cliente = '$mat'
    GROUP BY
    c.id_cliente,
    c.nome_cliente, 
    c.tel_cliente, 
    c.insta_cliente,
    ic.alergias,
    ic.tipo_pele,
    ic.medicamentos,
    ic.cond_saude";

$rs = mysqli_query($con, $sql);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/editar_perfil_cliente.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Editar Cliente - TattooManager</title>
</head>
<body>
	<?php include 'navbar.php'?>
    <?php while ($info = mysqli_fetch_array($rs)) { ?>
        
    <div class="form-container">
        <h3>Editar Cliente</h3>
        <form action="salvar_edicao_cliente.php?id_cliente=<?php echo $info['id_cliente']?>" method="post">
            
            <div class="form-grid">
                <div class="input-group full-width">
                    <label>Nome do Cliente</label>
                    <input type="text" name="nome_cliente" value="<?php echo $info['nome_cliente']?>">
                </div>

                <div class="input-group">
                    <label>Telefone</label>
                    <input type="number" name="telefone" value="<?php echo $info['tel_cliente']?>">
                </div>

                <div class="input-group">
                    <label>Email</label>
                    <input type="text" name="email" value="<?php echo $info['email_cliente']?>">
                </div>

                <div class="input-group">
                    <label>Instagram</label>
                    <input type="text" name="insta" value="<?php echo $info['insta_cliente']?>">
                </div>

                <div class="input-group">
                    <label>Tipo de Pele</label>
                    <input type="text" name="tipo_pele" value="<?php echo $info['tipo_pele']?>">
                </div>

                <div class="input-group full-width">
                    <label>Alergias</label>
                    <input type="text" name="alergias" value="<?php echo $info['alergias']?>">
                </div>

                <div class="input-group full-width">
                    <label>Medicamentos</label>
                    <input type="text" name="medicamentos" value="<?php echo $info['medicamentos']?>">
                </div>

                <div class="input-group full-width">
                    <label>Condição de Saúde</label>
                    <input type="text" name="cond_saude" value="<?php echo $info['cond_saude']?>">
                </div>

                <input type="submit" class="btn-submit" value="Salvar Alterações">
            </div>

        </form>
    </div>

    <?php } ?>

</body>
</html>