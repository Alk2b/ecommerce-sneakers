#!/bin/bash

echo "=== Déploiement eSneakers avec Docker Compose ==="

# Arrêter et nettoyer les anciens conteneurs
echo "🧹 Nettoyage des anciens conteneurs..."
docker-compose down -v 2>/dev/null
docker stop sneakers1 mysql-db phpmyadmin esneakers-web esneakers-mysql esneakers-phpmyadmin 2>/dev/null
docker rm sneakers1 mysql-db phpmyadmin esneakers-web esneakers-mysql esneakers-phpmyadmin 2>/dev/null
docker network rm esneakers-net esneakers-network 2>/dev/null

# Construire et démarrer les services
echo "🏗️  Construction et démarrage des services..."
docker-compose up --build -d

# Attendre que MySQL soit complètement prêt
echo "⏳ Attente de MySQL (60 secondes pour être sûr)..."
sleep 60

# Vérifier l'état des services
echo "📊 Vérification des services..."
docker-compose ps

# Tester la connexion API
echo "🔌 Test de connexion API..."
sleep 5
response=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/backend/api.php?action=products)
if [ "$response" = "200" ]; then
    echo "✅ API fonctionne !"
else
    echo "❌ Problème API (code: $response)"
fi

echo ""
echo "=== Déploiement terminé ! ==="
echo "🌐 Application: http://localhost:8080/frontend/"
echo "🗄️  phpMyAdmin: http://localhost:8081"
echo "🔍 API Test: http://localhost:8080/backend/api.php?action=products"
echo ""
echo "📋 Commandes utiles:"
echo "   📊 Logs: docker-compose logs -f"
echo "   🔄 Redémarrer: docker-compose restart"
echo "   🛑 Arrêter: docker-compose down"
echo "   🗑️  Tout supprimer: docker-compose down -v"
