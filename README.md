# ecommerce-sneakers

eSneakers - Plateforme E-Commerce de Sneakers
Une application e-commerce complète pour la vente de sneakers avec backend PHP, base de données MySQL et frontend HTML/JavaScript.

## Architecture du Projet 

ecommerce-sneakers/
├── backend/           # API REST PHP
│   ├── includes/      # Composants PHP réutilisables
│   │   └── db.php     # Connexion à la base de données
│   └── index.php      # Point d'entrée de l'API
├── database/          # Fichiers liés à la base de données
│   └── schema.sql     # Définitions du schéma de base de données
├── frontend/          # Code côté client
│   ├── index.html     # Page HTML principale
│   └── script.js      # JavaScript frontend
└── README.md          # Documentation du projet

## Stack Technique
Backend: PHP 8.x
Base de données: MySQL 8.0
Frontend: HTML5, JavaScript, Tailwind CSS
Serveur: Apache (WAMP/LAMP)
Déploiement: Serveur WAMP local et Microsoft Azure

## Référence de l'API
L'API est construite en PHP et suit les principes REST.

## Points d'Accès
Endpoint	Méthode	Description	Paramètres
/products	GET	Obtenir tous les produits disponibles	Aucun
/products/{id}	GET	Obtenir un produit spécifique	id: ID du produit
/cart/add	POST	Ajouter un produit au panier	product_id, quantity
/orders	POST	Créer une nouvelle commande	products[], client_id
/orders/{id}	GET	Obtenir les détails d'une commande	id: ID de la commande
/clients/{id}	GET	Obtenir les détails d'un client	id: ID du client

## Développement Local
Installer un serveur WAMP/MAMP/LAMP
Cloner le dépôt dans le répertoire racine de votre serveur web:
Importer le schéma de la base de données:
Configurer la connexion à la base de données dans backend/includes/db.php
Accéder à l'application à l'adresse http://localhost/ecommerce-sneakers/frontend/