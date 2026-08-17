<?php
// valida_log.php
session_start();



if (!isset($_SESSION['id_usuario']) || empty($_SESSION['id_usuario'])) {
    
    header('Location: index.php');
    exit;
}
?>