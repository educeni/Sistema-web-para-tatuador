google.charts.load('current', { 'packages': ['corechart'] });
google.charts.setOnLoadCallback(drawCharts);

function drawCharts() {
    drawDesempenhoMensal();
    drawEvolucaoLucro();
    drawClientesRecorrentes();
    drawDiasFaturamento();
}

function drawDesempenhoMensal() {
    var data = google.visualization.arrayToDataTable([
        ['Mês', 'Receita', 'Despesas', 'Lucro'],
        ['Out', 8000, 5000, 3000],
        ['Nov', 9500, 5500, 4000],
        ['Dez', 11000, 7000, 4000],
        ['Jan', 8500, 6000, 2500],
        ['Fev', 7500, 5500, 2000],
        ['Mar', 10000, 6500, 3500],
        ['Abr', 12000, 7500, 4500]
    ]);

    var options = {
        title: '',
        backgroundColor: '#383838',
        legend: {
            position: 'top',
            alignment: 'center',
            textStyle: {
                color: '#ffffff'
            }
        },
        colors: ['#4FC3F7', '#EF5350', '#66BB6A'],
        hAxis: {
            slantedText: false,
            textStyle: {
                color: '#ffffff'
            },
            gridlines: { color: 'transparent' }
        },
        vAxis: {
            minValue: 0,
            maxValue: 13000,
            textStyle: {
                color: '#ffffff'
            },
            gridlines: { color: '#4a4a4a', count: 6 },
            baselineColor: '#4a4a4a',
            format: 'currency',
            ticks: [
                { v: 0, f: 'R$0' },
                { v: 3000, f: 'R$3.000' },
                { v: 6000, f: 'R$6.000' },
                { v: 9000, f: 'R$9.000' },
                { v: 12000, f: 'R$12.000' }
            ]
        },
        chartArea: {
            left: 60,
            top: 30,
            width: '85%',
            height: '75%',
            backgroundColor: '#1d1d1d'
        },
        lineWidth: 3,
        pointSize: 8,
        pointShape: 'circle',
        tooltip: {
            trigger: 'focus',
            showColorCode: true,
            textStyle: { color: '#333333' }
        },
        curveType: 'none'
    };

    var chart = new google.visualization.LineChart(document.getElementById('desempenho_mensal'));
    chart.draw(data, options);
    window.addEventListener('resize', function() { chart.draw(data, options); });
}

function drawEvolucaoLucro() {
    var data = google.visualization.arrayToDataTable([
        ['Mês', 'Lucro'],
        ['Out', 3000],
        ['Nov', 4000],
        ['Dez', 4000],
        ['Jan', 2500],
        ['Fev', 2000],
        ['Mar', 3500],
        ['Abr', 4500]
    ]);

    var options = {
        title: '',
        backgroundColor: '#383838',
        legend: {
            position: 'top',
            alignment: 'center',
            textStyle: {
                color: '#ffffff'
            }
        },
        colors: ['#66BB6A'],
        hAxis: {
            slantedText: false,
            textStyle: {
                color: '#ffffff'
            },
            gridlines: { color: 'transparent' }
        },
        vAxis: {
            minValue: 0,
            maxValue: 5000,
            textStyle: {
                color: '#ffffff'
            },
            gridlines: { color: '#4a4a4a', count: 6 },
            baselineColor: '#4a4a4a',
            format: 'currency',
            ticks: [
                { v: 0, f: 'R$0' },
                { v: 1000, f: 'R$1.000' },
                { v: 2000, f: 'R$2.000' },
                { v: 3000, f: 'R$3.000' },
                { v: 4000, f: 'R$4.000' },
                { v: 5000, f: 'R$5.000' }
            ]
        },
        chartArea: {
            left: 60,
            top: 30,
            width: '85%',
            height: '75%',
            backgroundColor: '#1d1d1d'
        },
        lineWidth: 4,
        pointSize: 10,
        pointShape: 'circle',
        tooltip: {
            trigger: 'focus',
            showColorCode: true,
            textStyle: { color: '#333333' }
        },
        curveType: 'none'
    };

    var chart = new google.visualization.LineChart(document.getElementById('evolucao_lucro'));
    chart.draw(data, options);
    window.addEventListener('resize', function() { chart.draw(data, options); });
}

// ========== NOVO GRÁFICO 3: CLIENTES RECORRENTES ==========
function drawClientesRecorrentes() {
    var data = google.visualization.arrayToDataTable([
        ['Cliente', 'Sessões', { role: 'style' }],
        ['Carlos Mendes', 10, '#4FC3F7'],
        ['Juliano Santos', 8, '#4FC3F7'],
        ['Ana Paula', 7, '#4FC3F7'],
        ['Marcos Silva', 6, '#4FC3F7'],
        ['Fernanda Lima', 5, '#4FC3F7'],
        ['Roberto Alves', 5, '#4FC3F7'],
        ['Patrícia Souza', 4, '#4FC3F7'],
        ['Lucas Oliveira', 4, '#4FC3F7'],
        ['Camila Rocha', 3, '#4FC3F7'],
        ['Thiago Santos', 3, '#4FC3F7']
    ]);

    var options = {
        title: '',
        backgroundColor: '#383838',
        legend: { position: 'none' },
        hAxis: {
            textStyle: {
                color: '#ffffff',
                fontSize: 11
            },
            gridlines: { color: '#4a4a4a' },
            baselineColor: '#4a4a4a',
            minValue: 0
        },
        vAxis: {
            textStyle: {
                color: '#ffffff',
                fontSize: 11
            },
            gridlines: { color: 'transparent' }
        },
        chartArea: {
            left: 100,
            top: 20,
            width: '75%',
            height: '80%',
            backgroundColor: '#1d1d1d'
        },
        bars: 'horizontal',
        bar: { 
            groupWidth: '70%' 
        },
        // 🔥 FORÇA O PREENCHIMENTO TOTAL DAS BARRAS
        isStacked: false,
        tooltip: {
            trigger: 'focus',
            textStyle: { color: '#333333' }
        },
        annotations: {
            alwaysOutside: true,
            textStyle: {
                color: '#ffffff',
                fontSize: 11
            }
        }
    };

    var chart = new google.visualization.BarChart(document.getElementById('clientes_recorrentes'));
    chart.draw(data, options);
    window.addEventListener('resize', function() { chart.draw(data, options); });
}

// ========== NOVO GRÁFICO 4: DIAS COM MAIOR FATURAMENTO ==========
function drawDiasFaturamento() {
    var data = google.visualization.arrayToDataTable([
        ['Data', 'Faturamento', { role: 'style' }],
        ['20/03/2026', 1200, '#66BB6A'],
        ['15/04/2026', 1200, '#66BB6A'],
        ['10/04/2026', 800, '#66BB6A'],
        ['18/04/2026', 650, '#66BB6A'],
        ['25/03/2026', 500, '#66BB6A'],
        ['05/04/2026', 400, '#66BB6A'],
        ['20/02/2026', 350, '#66BB6A']
    ]);

    var options = {
        title: '',
        backgroundColor: '#383838',
        legend: { position: 'none' },
        hAxis: {
            textStyle: {
                color: '#ffffff',
                fontSize: 10,
                slantedText: true,
                slantedTextAngle: 30
            },
            gridlines: { color: 'transparent' }
        },
        vAxis: {
            textStyle: {
                color: '#ffffff',
                fontSize: 11
            },
            gridlines: { color: '#4a4a4a' },
            baselineColor: '#4a4a4a',
            format: 'currency',
            minValue: 0,
            maxValue: 1400,
            ticks: [
                { v: 0, f: 'R$0' },
                { v: 400, f: 'R$400' },
                { v: 800, f: 'R$800' },
                { v: 1200, f: 'R$1.200' }
            ]
        },
        chartArea: {
            left: 60,
            top: 20,
            width: '80%',
            height: '80%',
            backgroundColor: '#1d1d1d'
        },
        bar: { groupWidth: '60%' },
        tooltip: {
            trigger: 'focus',
            textStyle: { color: '#333333' },
            showColorCode: true
        },
        annotations: {
            alwaysOutside: true,
            textStyle: {
                color: '#ffffff',
                fontSize: 10
            }
        }
    };

    var chart = new google.visualization.ColumnChart(document.getElementById('dias_faturamento'));
    chart.draw(data, options);
    window.addEventListener('resize', function() { chart.draw(data, options); });
}