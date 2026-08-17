<?php
// 1. CONEXÃO COM O BANCO DE DADOS
include 'conexao.php';
/*
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro na conexão: " . $e->getMessage());
}

// 2. CONSULTA – Busca todos os atendimentos com data e valor
$sql = "SELECT appointment_date, amount FROM appointments ORDER BY appointment_date";
$stmt = $pdo->query($sql);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 3. AGRUPAMENTO POR DATA
$days = [];
foreach ($rows as $row) {
    $date = $row['appointment_date'];
    $amount = (float) $row['amount'];
    if (!isset($days[$date])) {
        $days[$date] = [
            'total'   => 0,
            'values'  => []
        ];
    }
    $days[$date]['total'] += $amount;
    $days[$date]['values'][] = $amount;
}

// 4. ORDENAÇÃO POR TOTAL (decrescente) e pega os 10 primeiros
usort($days, function ($a, $b) {
    return $b['total'] <=> $a['total'];
});
$top10 = array_slice($days, 0, 10);

// 5. PREPARA OS DADOS PARA O GOOGLE CHARTS
$chartData = [];
$chartData[] = ['Dia', 'Faturamento (R$)']; // cabeçalho
foreach ($top10 as $date => $data) {
    $dateObj = DateTime::createFromFormat('Y-m-d', $date);
    $dateFormatted = $dateObj ? $dateObj->format('d/m/Y') : $date;
    $chartData[] = [$dateFormatted, $data['total']];
}
// Converte para JSON para usar no JavaScript
$jsonData = json_encode($chartData);*/
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/teste2.css">
    <script src="js/graficos.js"></script>
    <title>Dias com Maior Faturamento - Gráfico</title>
    <!-- Carrega a Google Charts API -->
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
</head>
<body>
<div class="container">
    <h2><img src="img/trend.png" alt=""> Dias com Maior Faturamento</h2>
    <p class="sub">Top 10 dias com melhor desempenho – gráfico e lista</p>

    <?php if (empty($top10)): ?>
        <div class="no-data">Nenhum atendimento registrado ainda.</div>
    <?php else: ?>
        <!-- GRÁFICO GOOGLE CHARTS -->
        <div class="chart-wrapper" id="chart_div"></div>

        <!-- LISTA COM BARRAS DE PROGRESSO (ESTILO IMAGEM) -->
        <div class="list-wrapper">
            <h3 style="margin-top:0; color:#1a1a2e;">📋 Lista detalhada</h3>
            <?php 
            $maxTotal = max(array_column($top10, 'total')); // para escala da barra
            $rank = 1; 
            foreach ($top10 as $date => $data): 
                $dateObj = DateTime::createFromFormat('Y-m-d', $date);
                $dateFormatted = $dateObj ? $dateObj->format('d/m/Y') : $date;
                $percent = $maxTotal > 0 ? round(($data['total'] / $maxTotal) * 100) : 0;
            ?>
                <div class="day-item">
                    <span class="rank"><?= $rank ?></span>
                    <span class="day-date"><?= htmlspecialchars($dateFormatted) ?></span>
                    <span class="day-total">R$ <?= number_format($data['total'], 2, ',', '.') ?></span>
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= $percent ?>%;">
                            <span><?= $percent ?>%</span>
                        </div>
                    </div>
                </div>
            <?php 
                $rank++; 
            endforeach; 
            ?>
        </div>
    <?php endif; ?>
</div>

<!-- SCRIPT DO GOOGLE CHARTS -->
<script type="text/javascript">
    google.charts.load('current', { packages: ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Dados passados do PHP via JSON
        var dataArray = <?= $jsonData ?>;
        var data = google.visualization.arrayToDataTable(dataArray);

        var options = {
            title: 'Faturamento por Dia (Top 10)',
            titleTextStyle: { fontSize: 18, bold: true, color: '#1a1a2e' },
            hAxis: {
                title: 'Valor (R$)',
                format: 'currency',
                currency: { code: 'BRL', symbol: 'R$' },
                minValue: 0,
            },
            vAxis: {
                title: 'Data',
                textStyle: { fontSize: 12 }
            },
            legend: { position: 'none' },
            chartArea: { width: '70%', height: '70%' },
            colors: ['#e94560'],
            bar: { groupWidth: '60%' },
            backgroundColor: 'transparent',
            tooltip: {
                textStyle: { fontSize: 12 },
                showColorCode: true,
                trigger: 'hover'
            }
        };

        var chart = new google.visualization.BarChart(document.getElementById('chart_div'));
        chart.draw(data, options);

        // Redimensiona ao redimensionar a janela
        window.addEventListener('resize', function() {
            chart.draw(data, options);
        });
    }
</script>
</body>
</html>