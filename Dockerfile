FROM debian:jessie

# Installations
RUN apt-get update
RUN apt-get install -y git nodejs-legacy npm nginx php5-fpm
RUN npm install -g bower

# Remove default files
RUN rm -rf /var/www/html/*

# Clone project and install dependencies
RUN git clone https://github.com/tux-00/network_miner.git /var/www/html/
RUN cd /var/www/html/ && bower install --allow-root

# Configure nginx
RUN sed -i "s/index index.html/index index.php index.html/g" /etc/nginx/sites-available/default
RUN sed -i "43i location ~ \.php$ {\n\t\tinclude snippets/fastcgi-php.conf;\n\t\tfastcgi_pass unix:/var/run/php5-fpm.sock;\n\t}" /etc/nginx/sites-available/default

CMD service nginx start && service php5-fpm start && /bin/bash
