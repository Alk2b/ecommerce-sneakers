CREATE DATABASE IF NOT EXISTS ecommerce_sneakers;
USE ecommerce_sneakers;

-- Table des clients
CREATE TABLE IF NOT EXISTS Clients (
    client_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(50) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    mot_de_passe VARCHAR(255) NOT NULL,
    adresse VARCHAR(255),
    ville VARCHAR(100),
    code_postal VARCHAR(10),
    pays VARCHAR(50)
);

-- Table des produits (sneakers)
CREATE TABLE IF NOT EXISTS Produits (
    produit_id INT PRIMARY KEY AUTO_INCREMENT,
    nom VARCHAR(100) NOT NULL,
    description TEXT,
    prix DECIMAL(10, 2) NOT NULL,
    stock INT NOT NULL,
    image_url VARCHAR(255)
);

-- Table des commandes
CREATE TABLE IF NOT EXISTS Commandes (
    commande_id INT PRIMARY KEY AUTO_INCREMENT,
    client_id INT,
    date_commande DATETIME DEFAULT CURRENT_TIMESTAMP,
    statut VARCHAR(50) DEFAULT 'En attente',
    FOREIGN KEY (client_id) REFERENCES Clients(client_id)
);

-- Table des détails des commandes
CREATE TABLE IF NOT EXISTS DetailsCommandes (
    detail_id INT PRIMARY KEY AUTO_INCREMENT,
    commande_id INT,
    produit_id INT,
    quantite INT NOT NULL,
    prix_unitaire DECIMAL(10, 2) NOT NULL,
    FOREIGN KEY (commande_id) REFERENCES Commandes(commande_id),
    FOREIGN KEY (produit_id) REFERENCES Produits(produit_id)
);


-- Insertion de clients
INSERT INTO Clients (nom, email, mot_de_passe, adresse, ville, code_postal, pays)
VALUES 
('Jean Dupont', 'jean.dupont@example.com', 'motdepasse123', '123 Rue de Paris', 'Paris', '75001', 'France'),
('Marie Martin', 'marie.martin@example.com', 'motdepasse456', '456 Avenue des Champs', 'Lyon', '69002', 'France');

-- Insertion de produits (sneakers)
INSERT INTO Produits (nom, description, prix, stock, image_url)
VALUES 
('Nike Air Max', 'Chaussures de sport confortables', 120.00, 10, 'https://static.nike.com/a/images/t_PDP_936_v1/f_auto,q_auto:eco/a7f07bf7-7896-48c7-b53d-ab5daf86f84e/NIKE+AIR+MAX+EXCEE.png'),
('Adidas Superstar', 'Chaussures classiques en cuir', 100.00, 15, 'https://thumblr.uniid.it/product/261690/6f3199611e0a.jpg?width=3840&format=webp&q=75'),
('Puma RS-X', 'Chaussures lifestyle modernes', 90.00, 20, ' https://images.puma.com/image/upload/f_auto,q_auto,b_rgb:fafafa,w_2000,h_2000/global/391928/01/sv01/fnd/EEA/fmt/png/Sneakers-RS-X-Triple');

-- Insertion de commandes
INSERT INTO Commandes (client_id, statut)
VALUES 
(1, 'En attente'),
(2, 'Expédiée');

-- Insertion de détails de commandes
INSERT INTO DetailsCommandes (commande_id, produit_id, quantite, prix_unitaire)
VALUES 
(1, 1, 2, 120.00),
(1, 2, 1, 100.00),
(2, 3, 3, 90.00);