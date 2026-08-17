<?php
// 1. Inclua o seu arquivo de conexão
require 'conexao.php'; 
include 'valida_log.php';
// 2. Recebendo os dados do formulário (os 'names' do HTML)
// OBS: Certifique-se de que o input oculto com o ID do cliente chama 'cliente_id'
$id_cliente  = $_POST['id_cliente']; 
$data_agen   = $_POST['data_visita'];
$hora_agen   = $_POST['horario_visita'];
$valor_agen  = $_POST['orcamento'];
$status_agen = $_POST['status'];
$observacoes = $_POST['observacoes'];

try {
    // 3. Comando SQL de INSERT com as colunas EXATAS da sua imagem
    // IMPORTANTE: Substitua 'nome_da_sua_tabela' pelo nome real da tabela (ex: agendamentos)
    $sql = "INSERT INTO agendamento
            (id_cliente, data_agen, hora_agen, valor_agen, status_agen, observacoes) 
            VALUES 
            (:id_cliente, :data_agen, :hora_agen, :valor_agen, :status_agen, :observacoes)";
    
    // 4. Preparar e executar a query
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute([
        ':id_cliente'  => $id_cliente,
        ':data_agen'   => $data_agen,
        ':hora_agen'   => $hora_agen,
        ':valor_agen'  => $valor_agen,
        ':status_agen' => $status_agen,
        ':observacoes' => $observacoes
    ]);

    echo "<h1 class='teste'>Agendamento salvo com sucesso!</h1>";

} catch (PDOException $e) {
    echo "Erro ao salvar: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Salvar Agendamento</title>
</head>
<body>
    <?php include 'navbar.php';?>
</body>
</html>