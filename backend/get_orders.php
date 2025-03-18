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
        SELECT c.commande_id, c.date_commande, c.statut,
               d.quantite, d.prix_unitaire,
               p.nom as produit_nom, p.image_url
        FROM commandes c
        JOIN detailscommandes d ON c.commande_id = d.commande_id
        JOIN produits p ON d.produit_id = p.produit_id
        WHERE c.client_id = ?
        ORDER BY c.date_commande DESC
    ');
    
    $stmt->execute([$_GET['client_id']]);
    $commandes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo json_encode($commandes);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}