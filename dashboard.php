<?php
include 'conexao.php';
include 'valida_log.php';
// TOTAL DE CLIENTES
try {
    $sqlClientes = "SELECT COUNT(*) as total FROM clientes";
    $totalClientes = $pdo->query($sqlClientes)->fetch()['total'] ?? 0;
} catch(PDOException $e) {
    $totalClientes = 0;
    error_log("Erro ao buscar total de clientes: " . $e->getMessage());
}

// RECEITA TOTAL (apenas concluídos - com acento)
try {
    $sqlReceita = "SELECT SUM(valor_agen) as total FROM agendamento WHERE status_agen = 'concluído'";
    $receitaTotal = $pdo->query($sqlReceita)->fetch()['total'] ?? 0;
} catch(PDOException $e) {
    $receitaTotal = 0;
    error_log("Erro ao buscar receita: " . $e->getMessage());
}

// TOTAL DE ATENDIMENTOS
try {
    $sqlAtendimentos = "SELECT COUNT(*) as total FROM agendamento";
    $totalAtendimentos = $pdo->query($sqlAtendimentos)->fetch()['total'] ?? 0;
} catch(PDOException $e) {
    $totalAtendimentos = 0;
    error_log("Erro ao buscar atendimentos: " . $e->getMessage());
}

// DESPESAS (fixo por enquanto ou cria tabela depois)
$despesas = 910;

// LUCRO
$lucro = $receitaTotal;

// PRÓXIMOS AGENDAMENTOS (contador)
try {
    $sqlProxCount = "SELECT COUNT(*) as total FROM agendamento WHERE data_agen >= CURDATE() AND status_agen != 'cancelado'";
    $proximosCount = $pdo->query($sqlProxCount)->fetch()['total'] ?? 0;
} catch(PDOException $e) {
    $proximosCount = 0;
    error_log("Erro ao buscar próximos agendamentos: " . $e->getMessage());
}

// EVENTOS PARA O CALENDÁRIO - CORRIGIDO PARA SUA ESTRUTURA
try {
    $sqlEventos = "SELECT 
        DAY(ag.data_agen) as dia,
        MONTH(ag.data_agen) as mes,
        YEAR(ag.data_agen) as ano,
        ag.status_agen as status,
        c.nome_cliente as cliente
    FROM agendamento ag
    INNER JOIN clientes c ON c.id_cliente = ag.id_cliente
    WHERE ag.data_agen IS NOT NULL
    AND ag.status_agen != 'cancelado'
    ORDER BY ag.data_agen ASC";

    $stmt = $pdo->prepare($sqlEventos);
    $stmt->execute();
    $eventos = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Debug - verificar se retornou dados
    error_log("Eventos encontrados: " . count($eventos));
    
    // Se não houver eventos, usa dados de exemplo
    if (empty($eventos)) {
        $eventos = [
            ['dia' => 4, 'mes' => 7, 'ano' => 2026, 'status' => 'em andamento', 'cliente' => 'Maria Silva'],

            ['dia' => 15, 'mes' => 7, 'ano' => 2026, 'status' => 'em andamento', 'cliente' => 'Ana Oliveira'],
            ['dia' => 22, 'mes' => 7, 'ano' => 2026, 'status' => 'em andamento', 'cliente' => 'Pedro Costa'],
        ];
    }
} catch(PDOException $e) {
    // Em caso de erro, usa dados de exemplo
    $eventos = [
        ['dia' => 4, 'mes' => 7, 'ano' => 2026, 'status' => 'em andamento', 'cliente' => 'Maria Silva'],

        ['dia' => 15, 'mes' => 7, 'ano' => 2026, 'status' => 'em andamento', 'cliente' => 'Ana Oliveira'],
        ['dia' => 22, 'mes' => 7, 'ano' => 2026, 'status' => 'em andamento', 'cliente' => 'Pedro Costa'],
    ];
    error_log("Erro ao buscar eventos: " . $e->getMessage());
}

// NOTIFICAÇÕES (últimos 5)
try {
    $sqlNotif = "SELECT ag.*, c.nome_cliente 
                 FROM agendamento ag 
                 INNER JOIN clientes c ON c.id_cliente = ag.id_cliente 
                 WHERE ag.status_agen != 'cancelado'
                 ORDER BY ag.data_agen DESC LIMIT 5";
    $notificacoes = $pdo->query($sqlNotif)->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $notificacoes = [];
    error_log("Erro ao buscar notificações: " . $e->getMessage());
}

// PRÓXIMOS AGENDAMENTOS (lista)
try {
    $sqlProximos = "SELECT 
        c.id_cliente, 
        c.nome_cliente, 
        ag.data_agen,
        ag.status_agen
    FROM agendamento ag
    INNER JOIN clientes c ON c.id_cliente = ag.id_cliente
    WHERE ag.data_agen >= CURDATE() 
    AND ag.status_agen != 'cancelado'
    ORDER BY ag.data_agen ASC
    LIMIT 5";

    $stmt = $pdo->prepare($sqlProximos);
    $stmt->execute();
    $proximos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $proximos = [];
    error_log("Erro ao buscar próximos agendamentos: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard</title>
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="css/dashboard.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

  <?php include 'navbar.php'; ?>

  <div class="dashboard">

    <div class="dashboard-header">
      <h1>Dashboard</h1>
      <p>Visão geral do seu negócio</p>
    </div>

    <div class="dashboard-top">

      <div class="calendario-card">

        <div class="titulo-card">
          <h2>Calendário de Agendamentos</h2>
        </div>

        <!-- Calendário Personalizado -->
        <div class="calendario-container">
          <div class="mes-navegacao">
            <button class="btn-mes" onclick="mesAnterior()">
              <i class="fas fa-chevron-left"></i>
            </button>
            <span class="mes-atual" id="mesAtual">Julho 2026</span>
            <button class="btn-mes" onclick="proximoMes()">
              <i class="fas fa-chevron-right"></i>
            </button>
          </div>

          <div class="dias-semana">
            <span>DOM</span>
            <span>SEG</span>
            <span>TER</span>
            <span>QUA</span>
            <span>QUI</span>
            <span>SEX</span>
            <span>SÁB</span>
          </div>

          <div class="calendario-grid" id="calendarioGrid">
            <!-- Gerado pelo JavaScript -->
          </div>
        </div>

        <!-- Legenda -->
        <div class="legenda-custom">
          <div><span class="cor cor-azul"></span>Em andamento</div>
          <div><span class="cor cor-verde"></span>Concluído</div>
          <div><span class="cor cor-vermelho"></span>Cancelado</div>
          <div><span class="cor cor-hoje"></span>Hoje</div>
        </div>

      </div>

      <div class="proximos-card">

        <div class="titulo-card">
          <h2>Próximos</h2>
          <a href="#">Ver todos</a>
        </div>

        <div id="proximos-agendamentos">
          <?php if (!empty($proximos)): ?>
            <?php foreach ($proximos as $item): ?>
              <div class="agendamento-item">
                <strong><?= htmlspecialchars($item['nome_cliente']) ?></strong><br>
                <small><?= date('d/m/Y', strtotime($item['data_agen'])) ?></small>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Nenhum agendamento próximo.</p>
          <?php endif; ?>
        </div>

      </div>

    </div>

    <!-- Métricas -->
    <div class="metricas">
      <div class="metrica-card">
        <div class="icone"><i class="fa-regular fa-user"></i></div>
        <h3>Total de Clientes</h3>
        <h2 id="total-clientes"><?= $totalClientes ?></h2>
      </div>

      <div class="metrica-card">
        <div class="icone"><i class="fa-solid fa-dollar-sign"></i></div>
        <h3>Receita Total</h3>
        <h2 id="receita-total">R$ <?= number_format($receitaTotal, 2, ',', '.') ?></h2>
        <p id="total-atendimentos">Em <?= $totalAtendimentos ?> atendimentos</p>
      </div>

      <div class="metrica-card">
        <div class="icone"><i class="fa-solid fa-arrow-trend-up"></i></div>
        <h3>Lucro Líquido</h3>
        <h2 class="lucro" id="lucro-liquido">
          R$ <?= number_format($lucro, 2, ',', '.') ?>
        </h2>
        <p id="despesas">Despesas R$<?= number_format($despesas, 2, ',', '.') ?></p>
      </div>

      <div class="metrica-card">
        <div class="icone"><i class="fa-regular fa-calendar"></i></div>
        <h3>Próximos Agendamentos</h3>
        <h2 id="proximos-count"><?= $proximosCount ?></h2>
        <p>Aguardando</p>
      </div>
    </div>

    <div class="notificacoes">
      <div class="titulo-card">
        <h2>Notificações</h2>
      </div>
      <div id="lista-notificacoes">
        <?php if (!empty($notificacoes)): ?>
          <?php foreach ($notificacoes as $notif): ?>
            <div class="notificacao-item">
              <span class="notificacao-cliente"><?= htmlspecialchars($notif['nome_cliente']) ?></span>
              <span class="notificacao-data"><?= date('d/m/Y', strtotime($notif['data_agen'])) ?></span>
              <span class="notificacao-status <?= str_replace(' ', '-', $notif['status_agen']) ?>"><?= $notif['status_agen'] ?></span>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Todos os agendamentos aparecerão aqui.</p>
        <?php endif; ?>
      </div>
    </div>

  </div>

  <script>
    // Dados vindos do PHP
    const agendamentos = <?php echo json_encode($eventos ?? []); ?>;

    // VERIFICAÇÃO DETALHADA DOS DADOS
    console.log('=== DEBUG DO CALENDÁRIO ===');
    console.log('Total de agendamentos:', agendamentos.length);
    console.log('Dados dos agendamentos:', agendamentos);
    
    // Mostra detalhes de cada agendamento
    if (agendamentos.length > 0) {
        agendamentos.forEach((item, index) => {
            console.log(`Agendamento ${index + 1}:`, {
                dia: item.dia,
                mes: item.mes,
                ano: item.ano,
                status: item.status,
                cliente: item.cliente
            });
        });
    } else {
        console.warn('⚠️ Nenhum agendamento encontrado!');
    }

    let dataAtual = new Date();
    let mesAtual = dataAtual.getMonth();
    let anoAtual = dataAtual.getFullYear();

    const meses = [
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ];

    function renderizarCalendario(mes, ano) {
        const grid = document.getElementById('calendarioGrid');
        const titulo = document.getElementById('mesAtual');

        titulo.textContent = `${meses[mes]} ${ano}`;
        grid.innerHTML = '';

        const primeiroDia = new Date(ano, mes, 1);
        const ultimoDia = new Date(ano, mes + 1, 0);

        let diaSemanaInicio = primeiroDia.getDay();
        const totalDias = ultimoDia.getDate();
        const diasMesAnterior = new Date(ano, mes, 0).getDate();

        const hoje = new Date();
        const hojeDia = hoje.getDate();
        const hojeMes = hoje.getMonth();
        const hojeAno = hoje.getFullYear();

        console.log(`Renderizando calendário: ${meses[mes]} ${ano}`);
        console.log(`Total de dias: ${totalDias}`);

        // Dias do mês anterior
        for (let i = diaSemanaInicio - 1; i >= 0; i--) {
            const dia = diasMesAnterior - i;
            const div = document.createElement('div');
            div.className = 'dia outro-mes';
            div.textContent = dia;
            grid.appendChild(div);
        }

        // Dias do mês atual
        for (let dia = 1; dia <= totalDias; dia++) {
            const div = document.createElement('div');
            div.className = 'dia';
            div.textContent = dia;

            // HOJE
            if (dia === hojeDia && mes === hojeMes && ano === hojeAno) {
                div.classList.add('hoje');
            }

            // FILTRAR AGENDAMENTOS DO DIA, MÊS E ANO CORRETOS
            const eventosDoDia = agendamentos.filter(a => {
                const diaEvento = parseInt(a.dia);
                const mesEvento = parseInt(a.mes);
                const anoEvento = parseInt(a.ano);
                
                const corresponde = diaEvento === dia && 
                                   mesEvento === (mes + 1) && 
                                   anoEvento === ano;
                
                if (corresponde) {
                    console.log(`✅ Evento encontrado para ${dia}/${mes+1}/${ano}:`, a);
                }
                
                return corresponde;
            });

            if (eventosDoDia.length > 0) {
                console.log(`📅 Dia ${dia} tem ${eventosDoDia.length} evento(s)`);
                
                // Mapeia os status para as classes CSS
                const statusMap = {
                    'em andamento': 'em-andamento',
                    'concluído': 'concluido',
                    'cancelado': 'cancelado'
                };
                
                // Adiciona classe do status
                const status = eventosDoDia[0].status;
                const classeStatus = statusMap[status] || status.replace(/ /g, '-');
                div.classList.add(classeStatus);

                // Tooltip com todos os clientes
                const nomes = eventosDoDia.map(e => e.cliente).join(', ');
                div.title = nomes;

                // Clique mostra todos os agendamentos do dia
                div.addEventListener('click', function(e) {
                    e.stopPropagation();
                    let mensagem = "📅 Agendamentos do dia " + dia + "/" + (mes+1) + "/" + ano + ":\n\n";
                    eventosDoDia.forEach((ev, index) => {
                        mensagem += `${index + 1}. ${ev.cliente} (${ev.status})\n`;
                    });
                    alert(mensagem);
                });

                // Adiciona indicador visual de múltiplos agendamentos
                if (eventosDoDia.length > 1) {
                    const badge = document.createElement('span');
                    badge.className = 'multi-badge';
                    badge.textContent = eventosDoDia.length;
                    div.appendChild(badge);
                }
            }

            grid.appendChild(div);
        }

        // Completar com dias do próximo mês
        const totalCelulas = diaSemanaInicio + totalDias;
        const resto = totalCelulas % 7;

        if (resto !== 0) {
            const faltam = 7 - resto;
            for (let i = 1; i <= faltam; i++) {
                const div = document.createElement('div');
                div.className = 'dia outro-mes';
                div.textContent = i;
                grid.appendChild(div);
            }
        }
    }

    function mesAnterior() {
        mesAtual--;
        if (mesAtual < 0) {
            mesAtual = 11;
            anoAtual--;
        }
        renderizarCalendario(mesAtual, anoAtual);
    }

    function proximoMes() {
        mesAtual++;
        if (mesAtual > 11) {
            mesAtual = 0;
            anoAtual++;
        }
        renderizarCalendario(mesAtual, anoAtual);
    }

    // Inicializar calendário
    document.addEventListener('DOMContentLoaded', function() {
        console.log('🚀 Inicializando calendário...');
        renderizarCalendario(mesAtual, anoAtual);
    });
  </script>

</body>

</html>