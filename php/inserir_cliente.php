<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro</title>
    <link rel="stylesheet" href="css/cadastro_cliente.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>

    <?php include 'navbar.php'; include 'valida_log.php';?>

    <form action="salvar_cliente.php" method="post">
        <h1>Cadastrar Novo Cliente</h1>
        <p>Preencha os dados básicos</p>

        <div class="campos">

            <div class="campo">
                <label for="name">Nome completo</label>
                <input type="text" id="nome" placeholder="Nome do cliente" name="nome_cliente">
            </div>

            <div class="campo">
                <label for="telefone">Telefone</label>
                <input type="number" id="telefone" placeholder="(21) 99999-9999" name="telefone">
            </div>

            <div class="campo">
                <label for="email">E-mail</label>
                <input type="text" id="email" placeholder="cliente@email.com" name="email">
            </div>

            <div class="campo">
                <label for="insta">Instagram</label>
                <input type="text" id="insta" placeholder="@usuario" name="insta">
            </div>

            <div class="campo">
                <label for="alergias">Alergias</label>
                <input type="text" id="alergias" placeholder="Ex: Látex, Nenhuma" name="alergias">
            </div>

            <div class="campo">
                <label for="tipo_pele">Tipo de Pele</label>
                <input type="text" id="tipo_pele" placeholder="Ex: Normal, Oleosa, Sensível" name="tipo_pele">
            </div>

            <div class="campo">
                <label for="Medicamentos">Medicamentos</label>
                <input type="text" id="medicamentos" placeholder="Ex: Nenhum" name="medicamentos">
            </div>

            <div class="campo">
                <label for="cond_saude">Condições de Saúde</label>
                <input type="text" id="cond_saude" placeholder="Ex: Saudável" name="cond_saude">
            </div>


        </div>
        
        <!--botaozinho-->
        <div class="botoes">
            <a href="cliente.php">Voltar</a>
            <input type="submit" value="Cadastrar Cliente">
        </div>
            
        
    </form>

</body>
</html>