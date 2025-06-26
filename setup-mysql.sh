#!/bin/bash
echo "=== Configuration MySQL + App ==="

# Nettoyer
docker stop esneakers1 mysql-db 2>/dev/null
docker rm esneakers1 mysql-db 2>/dev/null
docker network rm esneakers-net 2>/dev/null

# Modifier db.php pour Docker
sed -i "s/localhost/mysql-db/g" backend/includes/db.php
sed -i "s/password = '';/password = 'rootpass';/g" backend/includes/db.php

# Reconstruire
docker build -t esneakers-projet:devel .

# Réseau
docker network create esneakers-net

# MySQL
docker run -d --name mysql-db --network esneakers-net -e MYSQL_ROOT_PASSWORD=rootpass -e MYSQL_DATABASE=sneakershop mysql:8.0

echo "Attente MySQL (30s)..."
sleep 30

# App web
docker run -d --name esneakers --network esneakers-net -p 8080:80 esneakers-projet:devel

echo "Terminé ! Testez : http://localhost:8080/frontend/"

# Rendre exécutable
chmod +x setup-mysql.sh

# Lancer le script
./setup-mysql.sh
