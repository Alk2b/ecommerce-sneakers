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
            // GET /api.php?action=products
            $stmt = $pdo->query('SELECT * FROM Produits');
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'user':
            // GET /api.php?action=user&client_id=X
            if (!isset($_GET['client_id'])) {
                throw new Exception('client_id requis');
            }
            $stmt = $pdo->prepare('SELECT * FROM Clients WHERE client_id = ?');
            $stmt->execute([$_GET['client_id']]);
            echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
            break;

        case 'orders':
            // GET /api.php?action=orders&client_id=X
            if (!isset($_GET['client_id'])) {
                throw new Exception('client_id requis');
            }
            $stmt = $pdo->prepare('
                SELECT c.commande_id, c.date_commande, c.statut,
                       d.quantite, d.prix_unitaire,
                       p.nom as produit_nom, p.image_url
                FROM Commandes c
                JOIN DetailsCommandes d ON c.commande_id = d.commande_id
                JOIN Produits p ON d.produit_id = p.produit_id
                WHERE c.client_id = ?
                ORDER BY c.date_commande DESC
            ');
            $stmt->execute([$_GET['client_id']]);
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
            break;

        case 'create_order':
            // POST /api.php?action=create_order
            if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
                throw new Exception('Méthode non autorisée');
            }

            $data = json_decode(file_get_contents('php://input'), true);
            if (!$data) {
                throw new Exception('Données JSON invalides');
            }

            try {
                // Démarrer la transaction
                $pdo->beginTransaction();

                // Insertion dans la table Commandes
                $stmt = $pdo->prepare('INSERT INTO Commandes (client_id, date_commande, statut) VALUES (:client_id, NOW(), "En attente")');
                $stmt->execute(['client_id' => $data['client_id']]);
                $commande_id = $pdo->lastInsertId();

                // Insertion dans la table DetailsCommandes
                foreach ($data['produits'] as $produit) {
                    $stmt = $pdo->prepare('INSERT INTO DetailsCommandes (commande_id, produit_id, quantite, prix_unitaire) VALUES (:commande_id, :produit_id, :quantite, :prix_unitaire)');
                    $stmt->execute([
                        'commande_id' => $commande_id,
                        'produit_id' => $produit['produit_id'],
                        'quantite' => $produit['quantite'],
                        'prix_unitaire' => $produit['prix']
                    ]);
                }

                // Valider la transaction
                $pdo->commit();

                echo json_encode(['success' => true, 'commande_id' => $commande_id]);
            } catch (Exception $e) {
                // Annuler la transaction uniquement si elle a été démarrée
                if ($pdo->inTransaction()) {
                    $pdo->rollBack();
                }
                http_response_code(500);
                echo json_encode(['error' => $e->getMessage()]);
            }
            break;

        default:
            throw new Exception('Action non reconnue');
    }
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}