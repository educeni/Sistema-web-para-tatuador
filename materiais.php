<?php
// 1. LIGAÇÃO À BASE DE DADOS (PDO)
require_once('conexao.php'); 
include 'valida_log.php';

// Inicializa as variáveis para evitar avisos
$Scri_materiais = null;
$resultado_clientes = null;

// 2. LÓGICA PARA INSERIR NOVO MATERIAL (ESTOQUE)
if (isset($_POST['cadastrar_material'])) {
    try {
        $nome = $_POST['nome'];
        $categoria = $_POST['categoria'];
        $quantidade = $_POST['quantidade'];

        $query = "INSERT INTO materiais (nome, categoria, quantidade_atual) VALUES (:nome, :categoria, :quantidade)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([
            ':nome' => $nome,
            ':categoria' => $categoria,
            ':quantidade' => $quantidade
        ]);
        
        header('Location: materiais.php');
        exit;
        
    } catch (PDOException $e) {
        $erro_insercao = "Erro ao cadastrar material: " . $e->getMessage();
    }
}

// 3. LÓGICA PARA O CONSUMO INTELIGENTE (GASTO POR CLIENTE)
if (isset($_POST['registrar_consumo'])) {
    try {
        $id_material = intval($_POST['id_material']);
        $quantidade_gasta = intval($_POST['quantidade_gasta']);

        if ($id_material > 0 && $quantidade_gasta > 0) {
            // Subtrai direto do estoque usando o PDO original
            $sql_baixa = "UPDATE materiais SET quantidade_atual = quantidade_atual - :quantidade_gasta WHERE id = :id";
            $stmt_baixa = $pdo->prepare($sql_baixa);
            $stmt_baixa->execute([
                ':quantidade_gasta' => $quantidade_gasta,
                ':id' => $id_material
            ]);
        }
        header('Location: materiais.php');
        exit;
    } catch (PDOException $e) {
        $erro_insercao = "Erro ao registrar gasto: " . $e->getMessage();
    }
}

// 4. LÓGICA PARA BUSCAR OS MATERIAIS E CLIENTES JÁ CADASTRADOS
try {
    $sql_busca = "SELECT id, nome, categoria, quantidade_atual FROM materiais ORDER BY nome ASC";
    $Scri_materiais = $pdo->query($sql_busca)->fetchAll(PDO::FETCH_ASSOC);

    // Busca os usuários/clientes da tabela 'usuario' para o consumo inteligente
    $sql_clientes = "SELECT id_cliente, nome_cliente FROM clientes ORDER BY nome_cliente ASC";
    $resultado_clientes = $pdo->query($sql_clientes)->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $erro_busca = "Erro ao buscar dados: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Estoque - TattooManager</title>
    <link rel="stylesheet" href="css/materiais.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php include('navbar.php'); ?>

    <h2 class="titulo-pagina">Estoque (Armário)</h2>
	<p> 
    <div class="main-layout">
        
        <div class="col-forms">
            
            <div class="box-dark">
                <h3>Adicionar Novo Material</h3>
                <form action="materiais.php" method="POST">
                    <div class="form-group">
                        <label>Nome do Material / Insumo:</label>
                        <input type="text" name="nome" placeholder="Ex: Agulha 3RL" required>
                    </div>
                    <div class="form-group">
                        <label>Categoria:</label>
                        <select name="categoria" required>
                            <option value="">Selecione...</option>
                            <option value="Agulhas">Agulhas / Cartuchos</option>
                            <option value="Tintas">Tintas</option>
                            <option value="Descartáveis">Descartáveis</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantidade Inicial:</label>
                        <input type="number" name="quantidade" min="0" required>
                    </div>
                    <button type="submit" name="cadastrar_material" class="btn-submit btn-green">Salvar no Estoque</button>
                </form>
            </div>

            <div class="box-dark">
                <h3>Registrar Gasto por Cliente</h3>
                <form action="materiais.php" method="POST">
                    <div class="form-group">
                        <label>Selecione o Cliente:</label>
                        <select name="id_cliente" required>
                            <option value="">Selecione...</option>
                            <?php if ($resultado_clientes): foreach($resultado_clientes as $cli): ?>
                                <option value="<?= $cli['id_cliente'] ?>"><?= htmlspecialchars($cli['nome_cliente']) ?></option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Material Utilizado:</label>
                        <select name="id_material" required>
                            <option value="">Selecione...</option>
                            <?php if ($Scri_materiais): foreach($Scri_materiais as $mat): ?>
                                <option value="<?= $mat['id'] ?>"><?= htmlspecialchars($mat['nome']) ?> (Restam: <?= $mat['quantidade_atual'] ?>)</option>
                            <?php endforeach; endif; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Quantidade Gasta:</label>
                        <input type="number" name="quantidade_gasta" min="1" required>
                    </div>
                    <button type="submit" name="registrar_consumo" class="btn-submit btn-blue">Dar Baixa Inteligente</button>
                </form>
            </div>

        </div>

        <div class="col-table">
            <div class="box-dark" style="padding: 0; overflow: hidden;">
                <div style="padding: 24px 24px 10px 24px;">
                    <h3>Materiais Disponíveis no Estúdio</h3>
                </div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Categoria</th>
                            <th>Quantidade Atual</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        if (!empty($Scri_materiais)) {
                            foreach($Scri_materiais as $item) {
                                $low_stock = ($item['quantidade_atual'] < 5) ? "class='alert-low'" : "";
                                echo "<tr $low_stock>";
                                echo "<td>" . htmlspecialchars($item['id']) . "</td>";
                                echo "<td><strong>" . htmlspecialchars($item['nome']) . "</strong></td>";
                                echo "<td>" . htmlspecialchars($item['categoria']) . "</td>";
                                echo "<td>" . htmlspecialchars($item['quantidade_atual']) . " un.</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4' style='text-align:center; color:#666;'>Nenhum material cadastrado.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

    </div>
	</p>
</body>
</html>