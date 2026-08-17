<?php
include 'conexao.php';
include 'valida_log.php';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Regra de negócio: Buscar todos os clientes ordenados alfabeticamente
    $stmt = $pdo->query("SELECT * FROM clientes ORDER BY nome_cliente ASC");
    $clientes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $agendamento = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro de conexão com o banco de dados: " . $e->getMessage());
}
?>



<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/agendamento.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <title>Novo Agendamento</title>
</head>

<body>
    <?php include 'navbar.php';?>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentPage = window.location.pathname.split('/').pop();
        
        document.querySelectorAll('.sidebar a').forEach(link => {
            const href = link.getAttribute('href');
            
            // Se estiver em inserir_cliente, ativa cliente.php
            if (currentPage === 'inserir_cliente.php') {
                if (href === 'cliente.php') {
                    link.classList.add('active');
                }
            } else {
                // Comportamento normal para outras páginas
                if (href === currentPage) {
                    link.classList.add('active');
                }
            }
        });
    });
</script>

    <div id="step1-selecao">
    <div class="panel-header">
        <h2><img src="img/calendar.png" width="20px"> Novo Agendamento</h2>
        <p>Selecione ou cadastre um cliente</p>
    </div>

    <input type="text" id="searchInput" class="search-box" placeholder="Buscar por nome, telefone ou Instagram...">

    <div class="client-list" id="clientList">
        <?php if (!empty($clientes)): ?>
            <?php foreach ($clientes as $cliente): ?>
                
                <div class="client-card" onclick="abrirFormulario(<?= $cliente['id_cliente'] ?>, '<?= htmlspecialchars($cliente['nome_cliente']) ?>')">
                    
                    <div class="client-info">
                        <div class="avatar"><img src="img/user.png" width="14px"></div>
                        <div class="details">
                            <strong><?= htmlspecialchars($cliente['nome_cliente']) ?></strong>
                            <span><?= htmlspecialchars($cliente['tel_cliente']) ?></span>
                            <?php if (!empty($cliente['instagram'])): ?>
                                <a href="#" class="instagram"><?= htmlspecialchars($cliente['instagram']) ?></a>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <?php foreach ($agendamento as $agendamento): ?>
                        <div class="sessions">
                            <?= htmlspecialchars($agendamento['valor_agen']) ?> sessão(ões)
                        </div>
                    <?php endforeach; ?>
                </div>
                
            <?php endforeach; ?>
        <?php else: ?>
            <p style="text-align: center; color: var(--text-muted);">Nenhum cliente cadastrado.</p>
        <?php endif; ?>
    </div>

    <button class="btn-novo-cliente"><img src="img/user.png" width="14px"><a href="inserir_cliente.php" class="btn-link"> Cadastrar Novo Cliente </a></button>
</div>

<div id="step2-formulario" style="display: none;">
    
    <div class="panel-header header-voltar">
        <button type="button" class="btn-voltar-icon" onclick="voltarParaSelecao()">⬅</button>
        <div>
            <h2 id="tituloFormulario"><img src="img/calendar.png" width="14px"> Novo Agendamento</h2>
            <p>Preencha os dados do agendamento</p>
        </div>
    </div>

    <form action="salvar_agendamento.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_cliente" id="clienteIdInput">

        <h3>Data e Horário</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Data da Visita *</label>
                <input type="date" name="data_visita" required>
            </div>
            <div class="form-group">
                <label>Horário *</label>
                <input type="time" name="horario_visita" required>
            </div>
        </div>

        <div class="box-retoque">
            <span class="retoque-title">Retoque (Opcional)</span>
            <div class="form-row">
                <div class="form-group">
                    <label>Data do Retoque</label>
                    <input type="date" name="data_retoque">
                </div>
                <div class="form-group">
                    <label>Horário do Retoque</label>
                    <input type="time" name="horario_retoque">
                </div>
            </div>
        </div>

        <h3>Valores</h3>
        <div class="form-row">
            <div class="form-group">
                <label>Orçamento (R$) *</label>
                <input type="number" step="0.01" name="orcamento" placeholder="0.00" required>
            </div>
            <div class="form-group">
                <label>Despesas (R$)</label>
                <input type="number" step="0.01" name="despesas" placeholder="0.00">
            </div>
        </div>

        <div class="form-group">
            <label>Status</label>
            <select name="status">
                <option value="Agendado">Agendado</option>
                <option value="Concluído">Concluído</option>
                <option value="Cancelado">Cancelado</option>
            </select>
        </div>

        <div class="form-group">
            <label>Observações</label>
            <textarea name="observacoes" rows="3" placeholder="Detalhes da tatuagem, preferências do cliente, etc."></textarea>
        </div>

        <div class="upload-section">
            <p>📷 Imagens de Referência</p>
            <label class="btn-upload">
                ⬆️ Adicionar Imagens
                <input type="file" name="img_ref[]" multiple style="display: none;">
            </label>
        </div>

        <div class="upload-section">
            <p>📷 Fotos do Procedimento</p>
            <label class="btn-upload">
                ⬆️ Adicionar Fotos
                <input type="file" name="img_proc[]" multiple style="display: none;">
            </label>
        </div>

        <div class="form-footer">
            <button type="button" class="btn-voltar" onclick="voltarParaSelecao()">Voltar</button>
            <button type="submit" class="btn-salvar">Criar Agendamento</button>
        </div>
    </form>
</div>

    <script>
        // Função chamada ao clicar em um cliente da lista
function abrirFormulario(id, nomeCliente) {
    // 1. Esconde a lista de clientes
    document.getElementById('step1-selecao').style.display = 'none';
    
    // 2. Mostra o formulário
    document.getElementById('step2-formulario').style.display = 'block';
    
    // 3. Atualiza o título com o nome do cliente (Ex: Novo Agendamento - João Silva)
    document.getElementById('tituloFormulario').innerHTML = '<img src="img/calendar.png" width="20px"> Novo Agendamento - ' + nomeCliente;
    
    // 4. Salva o ID do cliente no input oculto para o PHP conseguir salvar no banco depois
    document.getElementById('clienteIdInput').value = id;
}

// Função chamada ao clicar no botão "Voltar" ou na setinha
function voltarParaSelecao() {
    // Inverte a visualização
    document.getElementById('step2-formulario').style.display = 'none';
    document.getElementById('step1-selecao').style.display = 'block';
}
    </script>

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