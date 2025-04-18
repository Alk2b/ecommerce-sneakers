# ecommerce-sneakers

eSneakers - Plateforme E-Commerce de Sneakers  
Une application e-commerce complète pour la vente de sneakers avec backend PHP, base de données MySQL et frontend HTML/JavaScript.

## Architecture du Projet

ecommerce-sneakers/  
├── backend/ # API REST PHP  
│ ├── includes/ # Composants PHP réutilisables  
│ │ └── db.php # Connexion à la base de données  
│ ├── api.php # Point d'entrée API centralisé (GET et POST)  
│ ├── auth.php # Gestion authentification  
│ └── index.php # Redirection vers l'API centralisée  
├── database/ # Fichiers liés à la base de données  
│ └── schema.sql # Définitions du schéma de base de données  
├── frontend/ # Code côté client  
│ ├── css/ # Styles personnalisés  
│ ├── js/ # Scripts JavaScript  
│ │ ├── auth.js # Gestion authentification  
│ │ ├── cart.js # Gestion panier  
│ │ └── script.js # Logique principale  
│ ├── index.html # Page d'accueil  
│ ├── cart.html # Page panier  
│ ├── login.html # Page connexion/inscription  
│ └── profile.html # Page profil utilisateur  
└── README.md # Documentation du projet

## Stack Technique

- **Backend** : PHP 8.x
- **Base de données** : MySQL 8.0
- **Frontend** : HTML5, JavaScript, Tailwind CSS
- **Serveur** : Apache (WAMP/LAMP)
- **Déploiement** : Serveur WAMP local et Microsoft Azure

## Référence de l'API

L'API est construite en PHP et suit les principes REST.

### Points d'Accès

| Endpoint                     | Méthode | Description                 | Paramètres            |
| ---------------------------- | ------- | --------------------------- | --------------------- |
| /api.php?action=products     | GET     | Obtenir tous les produits   | Aucun                 |
| /api.php?action=user         | GET     | Détails utilisateur         | client_id             |
| /api.php?action=orders       | GET     | Commandes utilisateur       | client_id             |
| /api.php?action=create_order | POST    | Créer une commande          | products[], client_id |
| /api.php?action=update_user  | POST    | Modifier profil utilisateur | client_id, données    |
| /auth.php                    | POST    | Authentification            | email, password       |

## Développement Local

1. Installer un serveur WAMP/MAMP/LAMP.
2. Cloner le dépôt dans le répertoire racine de votre serveur web.
3. Importer le schéma de la base de données depuis `database/schema.sql`.
4. Configurer la connexion à la base de données dans `backend/includes/db.php`.
5. Accéder à l'application à l'adresse [http://localhost/ecommerce-sneakers/frontend/](http://localhost/ecommerce-sneakers/frontend/).

## Fonctionnalités Implémentées

- **Authentification** :

  - Inscription et connexion des utilisateurs
  - Gestion des sessions avec localStorage

- **Gestion des produits** :

  - Affichage des produits disponibles
  - Interface responsive avec Tailwind CSS

- **Gestion du panier** :

  - Ajouter des produits
  - Modifier les quantités
  - Supprimer des produits
  - Persistance du panier avec localStorage

- **Commandes** :

  - Création de nouvelles commandes
  - Historique des commandes
  - Statut des commandes en temps réel

- **Profil utilisateur** :
  - Affichage des informations personnelles
  - Modification des informations (nom, email, adresse...)
  - Historique des commandes détaillé
  - Interface de modification intuitive
