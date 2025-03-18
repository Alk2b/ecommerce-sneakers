<?php
require 'includes/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

if (!isset($_GET['client_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'client_id requis']);
    exit;
}

try {
    $stmt = $pdo->prepare('
        SELECT client_id, nom, email, adresse, ville, code_postal, pays 
        FROM clients 
        WHERE client_id = ?
    ');
    
    $stmt->execute([$_GET['client_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        throw new Exception('Utilisateur non trouvé');
    }
    
    echo json_encode($user);
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}