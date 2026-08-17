<!DOCTYPE html>
<html>

<head>
    <title>TattooManager - Dashboard</title>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <link rel="stylesheet" href="css/graficos.css">
    <script src="js/graficos.js"></script>

    <style>
        #desempenho_mensal svg,
        #evolucao_lucro svg {
            background-color: #383838 !important;
        }

        #desempenho_mensal svg rect,
        #evolucao_lucro svg rect {
            fill: #1d1d1d !important;
        }

        /* Estilo para os novos gráficos */
        #clientes_recorrentes svg,
        #dias_faturamento svg {
            background-color: #383838 !important;
        }

        #clientes_recorrentes svg rect,
        #dias_faturamento svg rect {
            fill: #1d1d1d !important;
        }
         .chart-header{
            color: white;
        }
        .chart_div > div > p{
            color: var(--texto_secundario);
        }

    </style>
</head>

<body>
    <div class="charts_grid">
        <!-- GRÁFICO 1: DESEMPENHO MENSAL -->
        <div class="chart-card">
            <div class="chart-header">
                <h2 class="chart-title">Desempenho Mensal</h2>
                <p class="chart-subtitle">Receitas, despesas e lucro nos últimos meses</p>
            </div>
            <div id="desempenho_mensal" class="chart-container"></div>
        </div>

        <!-- GRÁFICO 2: EVOLUÇÃO DO LUCRO -->
        <div class="chart-card">
            <div class="chart-header">
                <h2 class="chart-title">Evolução do Lucro</h2>
                <p class="chart-subtitle">Tendência de lucro ao longo do tempo</p>
            </div>
            <div id="evolucao_lucro" class="chart-container"></div>
        </div>

        <!-- GRÁFICO 3: CLIENTES RECORRENTES -->
        <div class="chart-card">
            <div class="chart-header">
                <h2 class="chart-title">Clientes Recorrentes</h2>
                <p class="chart-subtitle">Top 10 clientes com mais sessões</p>
            </div>
            <div id="clientes_recorrentes" class="chart-container"></div>
        </div>

        <!-- GRÁFICO 4: DIAS COM MAIOR FATURAMENTO -->
        <div class="chart-card">
            <div class="chart-header">
                <h2 class="chart-title">Dias com Maior Faturamento</h2>
                <p class="chart-subtitle">Top 10 dias com melhor desempenho</p>
            </div>
            <div id="dias_faturamento" class="chart-container"></div>
        </div>
    </div>
</body>

</html>