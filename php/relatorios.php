<?php include 'conexao.php';
include 'valida_log.php';
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Relatórios</title>
    <link rel="stylesheet" href="css/relatorios.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    <?php include 'navbar.php'; ?>

    <main>


        <header>
            <div class="conteudo">
                <h1>Relatórios Financeiros</h1>
                <p class="descricao"> Análise completa do seu desempenho financeiro</p>
            </div>
        </header>


        <div class="resumo">
            <div><img src="img/calendar.png" width="14px" class="espaco"> Junho 2026</div>
            <p class="descricao">Resumo mensal</p>
        </div>


        <div class="cards_relata">
            <!-- Card 1 - Receita -->
            <div class="card-resumo">
                <div class="card-cabecalho">
                    <span class="card-titulo">Receita <strong>Junho</strong></span>
                    <i class="fa-solid fa-dollar-sign icone-verde"></i>
                </div>
                <div class="card-corpo">
                    <h3 class="card-valor">R$ 0</h3>
                    <div class="card-tendencia positiva">
                        <i class="fa-solid fa-arrow-up"></i> 0.0%
                        <span class="texto-tendencia">vs mês anterior</span>
                    </div>
                </div>
            </div>

            <!-- Card 2 - Despesas -->
            <div class="card-resumo">
                <div class="card-cabecalho">
                    <span class="card-titulo">Despesas <strong>Junho</strong></span>
                    <i class="fa-solid fa-credit-card icone-azul"></i>
                </div>
                <div class="card-corpo">
                    <h3 class="card-valor">R$ 0</h3>
                    <div class="card-tendencia negativa">
                        <i class="fa-solid fa-arrow-down"></i> 0.0%
                        <span class="texto-tendencia">vs mês anterior</span>
                    </div>
                </div>
            </div>

            <!-- Card 3 - Lucro -->
            <div class="card-resumo">
                <div class="card-cabecalho">
                    <span class="card-titulo">Lucro <strong>Junho</strong></span>
                    <i class="fa-solid fa-chart-line icone-roxo"></i>
                </div>
                <div class="card-corpo">
                    <h3 class="card-valor">R$ 0</h3>
                    <div class="card-tendencia positiva">
                        <i class="fa-solid fa-arrow-up"></i> 0.0%
                        <span class="texto-tendencia">vs mês anterior</span>
                    </div>
                </div>
            </div>

            <!-- Card 4 - Agendamentos -->
            <div class="card-resumo">
                <div class="card-cabecalho">
                    <span class="card-titulo">Agendamentos <strong>Junho</strong></span>
                    <i class="fa-solid fa-calendar-check icone-amarelo"></i>
                </div>
                <div class="card-corpo">
                    <h3 class="card-valor">0</h3>
                    <div class="card-tendencia positiva">
                        <i class="fa-solid fa-arrow-up"></i> 0.0%
                        <span class="texto-tendencia">vs mês anterior</span>
                    </div>
                </div>
            </div>
        </div>


        <div class="estatisticas_graficos">
              <?php include 'teste.php';?>
        </div>
    </main>
</body>

</html>


 