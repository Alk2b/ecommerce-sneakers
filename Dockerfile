FROM docker.io/rockylinux:9.3

LABEL description="Serveur Apache eSneakers basé sur Rocky Linux"

# Installer Apache, PHP et extensions nécessaires
RUN dnf -y install httpd php php-mysqlnd php-pdo php-json php-mbstring && dnf clean all

# Configurer Apache
RUN echo "ServerName localhost" >> /etc/httpd/conf/httpd.conf

# Copier les fichiers du projet
COPY . /var/www/html/
COPY run-httpd.sh /run-httpd.sh 

# Définir les permissions
RUN chown -R apache:apache /var/www/html/
RUN chmod +x /run-httpd.sh 

# Exposer le port 80
EXPOSE 80 

# Healthcheck pour vérifier que Apache fonctionne
HEALTHCHECK --interval=30s --timeout=3s --start-period=5s --retries=3 \
  CMD curl -f http://localhost/ || exit 1

# Commande par défaut
CMD ["/run-httpd.sh"]
