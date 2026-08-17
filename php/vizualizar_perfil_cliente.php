<?php
// vizualizar_perfil_cliente.php
$mat = (int)$_GET['id_cliente'];
$con = mysqli_connect("sql309.infinityfree.com", "if0_42374528", "tattoolc0907", "if0_42374528_projeto_extensivo");
include 'valida_log.php';
$sql = "SELECT 
    c.id_cliente,
    c.nome_cliente, 
    c.tel_cliente, 
    c.email_cliente,
    c.insta_cliente, 
    MAX(ag.data_agen) as data_total,
    MAX(ag.status_agen),
    SUM(ag.valor_agen) AS total_gasto,
    COUNT(*) as total_sessoes,
    SUM(CASE WHEN ag.status_agen = 'Concluído' THEN 1 ELSE 0 END) as sessoes_concluidas,
    ic.alergias,
    ic.tipo_pele,
    ic.medicamentos,
    ic.cond_saude
    FROM clientes c
    LEFT JOIN agendamento ag ON c.id_cliente = ag.id_cliente
    INNER JOIN infor_cliente ic ON ic.id_cliente = c.id_cliente
    WHERE c.id_cliente = '$mat'
    GROUP BY
    c.id_cliente,
    c.nome_cliente, 
    c.tel_cliente, 
    c.email_cliente,
    c.insta_cliente,
    ic.alergias,
    ic.tipo_pele,
    ic.medicamentos,
    ic.cond_saude";

$rs = mysqli_query($con, $sql);

// Buscar histórico de agendamentos
$sql_agendamentos = "SELECT 
    ag.id_agend,
    ag.data_agen,
    ag.hora_agen,
    ag.status_agen,
    ag.valor_agen
    FROM agendamento ag
    WHERE ag.id_cliente = '$mat'
    ORDER BY ag.data_agen DESC, ag.hora_agen DESC";

$rs_agendamentos = mysqli_query($con, $sql_agendamentos);
$total_agendamentos = mysqli_num_rows($rs_agendamentos);
?>

<?php
while ($info = mysqli_fetch_array($rs)) {
    $total_sessoes = (int)$info['total_sessoes'];
    $sessoes_concluidas = (int)$info['sessoes_concluidas'];
    $percentual_concluido = $total_sessoes > 0 ? round(($sessoes_concluidas / $total_sessoes) * 100) : 0;
    $total_gasto = (float)$info['total_gasto'];
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($info['nome_cliente']);?> | TattooManager</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/vizu_cliente.css">
     <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
</head>

<body>
    <?php include 'navbar.php';?>

    <div class="conteudo">
        <div class="conteudo_info_cliente">
         
            <div>
                <div class="img_perfil2">
                    <i class="fa-regular fa-user"></i>
                </div>
                <div>
                    <h2><?php echo htmlspecialchars($info['nome_cliente']); ?></h2>
                    <div class="contato_clientes">
                        <p style="color: #a8a8a8;"><i class="fa-solid fa-phone"></i> <?php echo htmlspecialchars($info['tel_cliente']); ?></p>
                        <p style="color: #3b82f6;"><i class="fa-brands fa-instagram"></i> <?php echo htmlspecialchars($info['insta_cliente']); ?></p>
                        <p style="color: #a8a8a8;"><i class="fa-regular fa-calendar"></i> <?php echo date('d/m/Y', strtotime($info['data_total'])); ?></p>
                    </div>
                </div>
            </div>

            <a href="editar_perfil_cliente.php?id_cliente=<?php echo $mat; ?>" class="btn_editar">
                <i class="fa-solid fa-pen-to-square"></i>
                <p>Editar</p>
            </a>
        </div>

        <section class="cards_felx">
            <div class="cards_felx_div" style="background-color:#1a2a3a;">
                <div>
                    <p style="color: #4a9eff;"><strong>Total de sessões</strong></p>
                    <h2 style="color: white;"><?php echo $total_sessoes; ?></h2>
                </div>
                <figure style="background-color: #1c5cf257;"><i class="fa-regular fa-calendar" style="color: #4a9eff;"></i></figure>
            </div>

            <div class="cards_felx_div" style="background-color:#1a2e1a;">
                <div>
                    <p style="color: #00c853;"><strong>Concluídas</strong></p>
                    <h2 style="color: white;"><?php echo $sessoes_concluidas; ?></h2>
                    <p style="color: #a8a8a8;"><?php echo $percentual_concluido; ?>% do total</p>
                </div>
                <figure style="background-color: #00ff003b;"><i class="fa-regular fa-calendar-check" style="color: #00c853;"></i></figure>
            </div>

            <div class="cards_felx_div" style="background-color:#2a1a3a;">
                <div>
                    <p style="color: #9c27b0;"><strong>Receita Total</strong></p>
                    <h2 style="color: white;"><?php echo number_format($total_gasto, 0, ',', '.'); ?></h2>
                    <p style="color: #a8a8a8;">R$ <?php echo number_format($total_gasto, 2, ',', '.'); ?></p>
                </div>
                <figure style="background-color: #5c1d9b75;"><i class="fa-solid fa-dollar-sign" style="color: #9c27b0;"></i></figure>
            </div>

            <div class="cards_felx_div" style="background-color:#2e1a0a;">
                <div>
                    <p style="color: #ff6d00;"><strong>Ticket Médio</strong></p>
                    <h2 style="color: white;"><?php echo $total_sessoes > 0 ? number_format($total_gasto / $total_sessoes, 0, ',', '.') : 0; ?></h2>
                    <p style="color: #a8a8a8;">por sessão</p>
                </div>
                <figure style="background-color: #ad52075e;"><i class="fa-regular fa-chart-line" style="color: #ff6d00;"></i></figure>
            </div>
        </section>

        <section class="cards_flexs2">
            <div class="cards_flexs2_d1">
                <h2>Dados Pessoais</h2>
            </div>
            <div class="sep"></div>

            <div class="cards_flexs2_d2">
                <div class="d2_1">
                    <p>
                        <label><i class="fa-regular fa-user"></i> Nome completo</label>
                        <input type="text" value="<?php echo htmlspecialchars($info['nome_cliente']); ?>" disabled class="d2_input">
                    </p>
                    <p>
                        <label><i class="fa-solid fa-phone"></i> Telefone</label>
                        <input type="text" value="<?php echo htmlspecialchars($info['tel_cliente']); ?>" disabled class="d2_input">
                    </p>
                    <p>
                        <label><i class="fa-regular fa-envelope"></i> E-mail</label>
                        <input type="text" value="<?php echo htmlspecialchars($info['email_cliente']); ?>" disabled class="d2_input">
                    </p>
                    <p>
                        <label><i class="fa-brands fa-instagram"></i> Instagram</label>
                        <input type="text" value="<?php echo htmlspecialchars($info['insta_cliente']); ?>" disabled class="d2_input">
                    </p>
                </div>

                <div class="d2_2">
                    <h2>Anamnese</h2>
                    <div>
                        <p>
                            <label><i class="fa-solid fa-allergies"></i> Alergias</label>
                            <input type="text" value="<?php echo htmlspecialchars($info['alergias'] ?: 'Nenhuma'); ?>" disabled class="d2_input">
                        </p>
                        <p>
                            <label><i class="fa-regular fa-droplet"></i> Tipo de pele</label>
                            <input type="text" value="<?php echo htmlspecialchars($info['tipo_pele'] ?: 'Não informado'); ?>" disabled class="d2_input">
                        </p>
                        <p>
                            <label><i class="fa-solid fa-capsules"></i> Medicamentos</label>
                            <input type="text" value="<?php echo htmlspecialchars($info['medicamentos'] ?: 'Nenhum'); ?>" disabled class="d2_input">
                        </p>
                        <p>
                            <label><i class="fa-solid fa-heartbeat"></i> Condições de saúde</label>
                            <input type="text" value="<?php echo htmlspecialchars($info['cond_saude'] ?: 'Nenhuma'); ?>" disabled class="d2_input">
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- HISTÓRICO DE AGENDAMENTOS - Conforme image.png -->
        <section class="cards_flexs3">
                 <div class="flexs3_div1">
                    <div class="div1_1">
                        <p><i></i><h2>Histórico de agendamento</h2></p>
                         <p>1 sessão • Clique para ver detalhes</p>
                    </div>
                    <a href="novo_agendamento.php"><button>+ Novo agendamento</button></a>
                 </div>


        </section>

    </div>
</body>

</html>

<?php
}
?>