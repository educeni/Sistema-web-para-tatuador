<?php

$host = 'sql309.infinityfree.com'; 
$dbname = 'if0_42374528_projeto_extensivo';
$user = 'if0_42374528';
$pass = 'tattoolc0907';

try {
    // 1. Tenta a conexão padrão dos seus amigos (localhost na porta 3306)
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (PDOException $e) {
    
    // 2. Se falhar (que é o que vai acontecer no SEU PC por causa do conflito),
    // o PHP entra aqui e tenta a sua configuração especial de salvamento:
    try {
        $host_especial = '127.0.0.1:3307'; // A sua porta do XAMPP
        $pdo = new PDO("mysql:host=$host_especial;dbname=$dbname;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    } catch (PDOException $e2) {
        // Se falhar nos dois, aí realmente o banco está desligado
        die("Erro na conexão: " . $e2->getMessage());
    }
}
?>