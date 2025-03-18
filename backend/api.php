<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/db.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$action = $_GET['action'] ?? '';

try {
    switch($action) {
        case 'products':
            // Ancien index.php
            $stmt = $pdo->query('SELECT * FROM produits');
            $produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($produits);
            break;

        case 'user':
            // Ancien get_user.php
            if (!isset($_GET['client_id'])) {
                throw new Exception('client_id requis');
            }
            $stmt = $pdo->prepare('SELECT client_id, nom, email, adresse, ville, code_postal, pays FROM clients WHERE client_id = ?');
            $stmt->execute([$_GET['client_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$user) {
                throw new Exception('Utilisateur non trouvé');
            }
            
            echo json_encode($user);
            break;

        case 'orders':
            // Ancien get_orders.php
            if (!isset($_GET['client_id'])) {
                throw new Exception('client_id requis');
            }
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
            break;

        default:
            throw new Exception('Action non reconnue');
    }
} catch(Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}