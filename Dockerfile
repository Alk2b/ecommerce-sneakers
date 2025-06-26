FROM docker.io/rockylinux:9.3

LABEL description="Serveur Apache ecommerce basé sur rockylinux"

RUN dnf -y install httpd php php-mysqlnd && dnf clean all

COPY . /var/www/html/
COPY run-httpd.sh /run-httpd.sh 

RUN chown -R apache:apache /var/www/html/
RUN chmod +x /run-httpd.sh 

EXPOSE 80 

CMD ["/run-httpd.sh"]
