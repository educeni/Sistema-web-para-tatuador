<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
    /*coisas do navbar aqui*/
    * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Poppins', sans-serif;
} 
    
    .sidebar {

        position: fixed;
        top: 0;
        left: 0;
        width: 230px;
        height: 100%;
        background: #191919;
        border-right: 1px solid #3a3a3a;
        padding: 20px;
    }

    .sidebar-logo {
        color: white;
        font-size: 20px;
        font-weight: 800;
        margin-bottom: 25px;
    }

    .sidebar a {
        display: block;
        text-decoration: none;
        color: #bdbdbd;
        padding: 12px 14px;
        border-radius: 10px;
        font-size: 15px;
        margin-bottom: 8px;
        transition: 0.2s;
    }

    /*efeito legal de passar o mouse q adoro*/
    .sidebar a:hover {
        background: #1a1a1a;
        color: white;
    }

    /*trocar a cor pra aparecer clicado*/
    .sidebar a.active {
        background: #2a2a2a;
        color: white;
    }
</style>

<body>
    <nav class="sidebar">
        <h2 class="sidebar-logo"> <img src="img/christmas-stars.png" width="14px"> TattooManager</h2>
        <a href="dashboard.php" data-page="dashboard.php"> <img src="img/dashboard.png" width="14px"> Dashboard</a>
        <a href="cliente.php" data-page="cliente.php"> <img src="img/customer.png" width="14px"> Clientes</a>
        <a href="relatorios.php" data-page="relatorios.php"> <img src="img/relatorio.png" width="14px"> Relatórios</a>
        <a href="materiais.php" data-page="materiais.php"> <img src="img/materias.png" width="14px"> Materiais</a>
    </nav>

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
</body>

</html>