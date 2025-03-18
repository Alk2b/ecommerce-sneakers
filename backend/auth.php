<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require 'includes/db.php';

// Configuration des headers CORS
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Gestion de la requête OPTIONS pour CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Lecture des données POST
$json = file_get_contents('php://input');
$data = json_decode($json, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $action = $data['action'] ?? '';

        if ($action === 'login') {
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';

            $stmt = $pdo->prepare('SELECT * FROM clients WHERE email = ?');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user) {
                // Pour le test, on vérifie le mot de passe en clair
                if ($password === $user['mot_de_passe']) {
                    echo json_encode([
                        'success' => true,
                        'user' => [
                            'id' => $user['client_id'],
                            'nom' => $user['nom'],
                            'email' => $user['email']
                        ]
                    ]);
                    exit;
                }
            }
            
            http_response_code(401);
            echo json_encode(['error' => 'Email ou mot de passe incorrect']);
            exit;
        }

        if ($action === 'register') {
            $nom = $data['nom'] ?? '';
            $email = $data['email'] ?? '';
            $password = $data['password'] ?? '';

            // Vérification email unique
            $stmt = $pdo->prepare('SELECT COUNT(*) FROM clients WHERE email = ?');
            $stmt->execute([$email]);
            if ($stmt->fetchColumn() > 0) {
                throw new Exception('Cet email est déjà utilisé');
            }

            // Insertion du nouveau client
            $stmt = $pdo->prepare('INSERT INTO clients (nom, email, mot_de_passe) VALUES (?, ?, ?)');
            $stmt->execute([$nom, $email, $password]);

            echo json_encode([
                'success' => true,
                'message' => 'Inscription réussie'
            ]);
            exit;
        }

    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

http_response_code(400);
echo json_encode(['error' => 'Requête invalide']);