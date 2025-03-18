<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

try {
    $stmt = $pdo->query('SELECT * FROM produits');
    $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($produits)) {
        echo json_encode([]);
        exit;
    }
    
    echo json_encode($produits);
} catch(PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
exit;

// Simple redirection vers api.php avec le bon paramètre
header('Location: api.php?action=products');

// Endpoint pour passer une commande
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SERVER['REQUEST_URI'] === '/api/commandes') {
    $data = json_decode(file_get_contents('php://input'), true);
    $client_id = $data['client_id'];
    $produits = $data['produits'];

    // Insertion de la commande
    $stmt = $pdo->prepare('INSERT INTO Commandes (client_id) VALUES (:client_id)');
    $stmt->execute(['client_id' => $client_id]);
    $commande_id = $pdo->lastInsertId();

    // Insertion des détails de la commande
    foreach ($produits as $produit) {
        $stmt = $pdo->prepare('INSERT INTO DetailsCommandes (commande_id, produit_id, quantite, prix_unitaire) VALUES (:commande_id, :produit_id, :quantite, :prix_unitaire)');
        $stmt->execute([
            'commande_id' => $commande_id,
            'produit_id' => $produit['produit_id'],
            'quantite' => $produit['quantite'],
            'prix_unitaire' => $produit['prix']
        ]);
    }

    echo json_encode(['message' => 'Commande passée avec succès']);
}
?>