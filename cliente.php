<?php
function mascaraTelefone($numero)
{
    $numero = preg_replace('/[^0-9]/', '', $numero);
    
    if (strlen($numero) == 11) {
        return preg_replace('/^(\d{2})(\d{5})(\d{4})$/', '($1) $2-$3', $numero);
    }
    
    if (strlen($numero) == 10) {
        return preg_replace('/^(\d{2})(\d{4})(\d{4})$/', '($1) $2-$3', $numero);
    }
    
    return $numero;
}

function formatarData($data)
{
    if (!$data) {
        return 'sem data';
    }
    return date('d/m/Y', strtotime($data));
}
include 'conexao.php';
include 'valida_log.php';
// Query base sem WHERE ainda
$sql = "SELECT 
    c.id_cliente, 
    c.nome_cliente, 
    c.tel_cliente, 
    c.insta_cliente,
    c.email_cliente,
    MAX(ag.data_agen) as ultima_data,
    SUM(ag.valor_agen) AS total_gasto,
    COUNT(ag.id_agend) as total_sessoes,
    SUM(CASE WHEN ag.status_agen = 'concluido' THEN 1 ELSE 0 END) as total_sessoes_conc
    FROM clientes c
    LEFT JOIN agendamento ag ON c.id_cliente = ag.id_cliente";

// Processamento AJAX
if (isset($_GET["ajax"])) {
    $nome = $_GET["nome"] ?? "";
    $data_ini = $_GET["data_ini"] ?? "";
    $data_fim = $_GET["data_fim"] ?? "";
    $status = $_GET["status"] ?? "";
    $valor_min = $_GET["valor_min"] ?? "";
    $valor_max = $_GET["valor_max"] ?? "";
    
    $where = [];
    $params = [];
    
    if ($nome != "") {
        $where[] = "(c.nome_cliente LIKE :nome OR c.insta_cliente LIKE :nome OR c.email_cliente LIKE :nome)";
        $params[':nome'] = "%$nome%";
    }
    if ($data_ini != "") {
        $where[] = "ag.data_agen >= :data_ini";
        $params[':data_ini'] = $data_ini;
    }
    if ($data_fim != "") {
        $where[] = "ag.data_agen <= :data_fim";
        $params[':data_fim'] = $data_fim;
    }
    if ($status != "") {
        $where[] = "ag.status_agen = :status";
        $params[':status'] = $status;
    }
    if ($valor_min != "") {
        $where[] = "ag.valor_agen >= :valor_min";
        $params[':valor_min'] = $valor_min;
    }
    if ($valor_max != "") {
        $where[] = "ag.valor_agen <= :valor_max";
        $params[':valor_max'] = $valor_max;
    }
    
    $sql_final = $sql;
    if (count($where) > 0) {
        $sql_final .= " WHERE " . implode(' AND ', $where);
    }
    
    $sql_final .= " GROUP BY c.id_cliente";
    
    $stmt = $pdo->prepare($sql_final);
    $stmt->execute($params);
    $rs = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    foreach ($rs as $info):
        ?>
        <a href="vizualizar_perfil_cliente.php?id_cliente=<?php echo $info['id_cliente']; ?>">
            <div class="pai_cliente">
                <div class="filho_1_cliente">
                    <figure class="img_perfil">
                        <i class="fa-regular fa-user"></i>
                    </figure>
                    <div>
                        <h2 class="nome_cliente"><?php echo htmlspecialchars($info['nome_cliente']); ?></h2>
                        <p><?php echo ($info['total_sessoes'] ?? 0) == 0 ? 'sem sessão' : $info['total_sessoes']; ?></p>
                    </div>
                </div>
                <div class="filho_2_cliente">
                    <div class="insta">
                        <i class="fa-solid fa-phone"></i>
                        <p><?php echo mascaraTelefone($info['tel_cliente']); ?></p>
                    </div>
                    <div class="insta">
                        <i class="fa-brands fa-instagram"></i>
                        <p style="color:#3b82f6;"><?php echo htmlspecialchars($info['insta_cliente']); ?></p>
                    </div>
                    <div class="insta">
                        <i class="fa-regular fa-calendar"></i>
                        <p><?php echo formatarData($info['ultima_data']); ?></p>
                    </div>
                </div>
                <hr>
                <div class="filho_3_cliente">
                    <div>
                        <p>Receita Total</p>
                        <p class="nome_cliente">R$ <?php echo number_format($info['total_gasto'] ?? 0, 2, ',', '.'); ?></p>
                    </div>
                    <div>
                        <p>Concluídos</p>
                        <p style="color:#10b981;"><?php echo $info['total_sessoes_conc'] ?? 0; ?> sessões</p>
                    </div>
                </div>
            </div>
        </a>
        <?php
     endforeach; 
    exit;
}

// Query normal (sem filtros)
$sql_final = $sql . " GROUP BY c.id_cliente";
$stmt = $pdo->prepare($sql_final);
$stmt->execute();
$rs = $stmt->fetchAll(PDO::FETCH_ASSOC);

$sql2 = "SELECT COUNT(*) as total_cliente FROM clientes";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$info2 = $stmt2->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Clientes</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
    <?php include 'navbar.php';?>

    <div class="conteudo">
        <header>
            <div>
                <h1>Clientes</h1>
                <p class="descricao"><strong><?php echo $info2['total_cliente']; ?> clientes cadastrados</strong></p>
            </div>
            <a href="novo_agendamento.php">+ Novo Agendamento</a>
        </header>

        <!-- FILTROS -->
        <form id="filtro">
            <div class="volta_pesquisa">
                <input class="pesquisa" type="text" id="nome" placeholder="Buscar por nome, instagram ou email">
            </div>
            <div class="filtro_avançado">
                <div class="grupo_campo">
                    <label class="data1e2">Data Início</label>
                    <input type="date" id="data_ini">
                </div>
                <div class="grupo_campo">
                    <label class="data1e2">Data Fim</label>
                    <input type="date" id="data_fim">
                </div>
                <div class="grupo_campo">
                    <label class="data1e2">Receita Mínima</label>
                    <input type="number" id="valor_min" placeholder="R$ 0">
                </div>
                <div class="grupo_campo">
                    <label class="data1e2">Receita Máxima</label>
                    <input type="number" id="valor_max" placeholder="R$ 99999">
                </div>
                <div class="grupo_campo">
                    <label class="data1e2">Status</label>
                    <select id="status">
                        <option value="">Todos</option>
                        <option value="concluido">Concluído</option>
                        <option value="em andamento">Em andamento</option>
                        <option value="cancelado">Cancelado</option>
                    </select>
                </div>
            </div>
        </form>
        <br><br>

        <!-- RESULTADO -->
        <div id="resultado">
            <?php foreach ($rs as $info): ?>
                <a href="vizualizar_perfil_cliente.php?id_cliente=<?php echo $info['id_cliente']; ?>">
                    <div class="pai_cliente">
                        <div class="filho_1_cliente">
                            <figure class="img_perfil">
                                <i class="fa-regular fa-user"></i>
                            </figure>
                            <div>
                                <h2 class="nome_cliente"><?php echo $info['nome_cliente']; ?></h2>
                                <p><?php echo ($info['total_sessoes'] ?? 0) == 0 ? 'sem sessão' : $info['total_sessoes']; ?></p>
                            </div>
                        </div>
                        <div class="filho_2_cliente">
                            <div class="insta">
                                <i class="fa-solid fa-phone"></i>
                                <p><?php echo mascaraTelefone($info['tel_cliente']); ?></p>
                            </div>
                            <div class="insta">
                                <i class="fa-brands fa-instagram"></i>
                                <p style="color:#3b82f6;"><?php echo $info['insta_cliente']; ?></p>
                            </div>
                            <div class="insta">
                                <i class="fa-regular fa-calendar"></i>
                                <p><?php echo formatarData($info['ultima_data']); ?></p>
                            </div>
                        </div>
                        <hr>
                        <div class="filho_3_cliente">
                            <div>
                                <p>Receita Total</p>
                                <p class="nome_cliente">R$ <?php echo number_format($info['total_gasto'] ?? 0, 2, ',', '.'); ?></p>
                            </div>
                            <div>
                                <p>Concluídos</p>
                                <p style="color:#10b981;"><?php echo $info['total_sessoes_conc'] ?? 0; ?> sessões</p>
                            </div>
                        </div>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function buscar() {
            let nome = document.querySelector("#nome").value;
            let data_ini = document.querySelector("#data_ini").value;
            let data_fim = document.querySelector("#data_fim").value;
            let status = document.querySelector("#status").value;
            let valor_min = document.querySelector("#valor_min").value;
            let valor_max = document.querySelector("#valor_max").value;
            
            fetch(`?ajax=1&nome=${encodeURIComponent(nome)}&data_ini=${data_ini}&data_fim=${data_fim}&status=${status}&valor_min=${valor_min}&valor_max=${valor_max}`)
            .then(res => res.text())
            .then(data => {
                document.querySelector("#resultado").innerHTML = data;
            });
        }
        
        document.querySelector("#nome").addEventListener("input", buscar);
        document.querySelector("#data_ini").addEventListener("change", buscar);
        document.querySelector("#data_fim").addEventListener("change", buscar);
        document.querySelector("#status").addEventListener("change", buscar);
        document.querySelector("#valor_min").addEventListener("input", buscar);
        document.querySelector("#valor_max").addEventListener("input", buscar);
    </script>
</body>
</html>