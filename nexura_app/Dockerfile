# Usa una imagen base de PHP con Apache
FROM php:8.2-apache

# Instala extensiones PHP necesarias
# Es crucial instalar pdo_mysql o mysqli para conectarte a la base de datos
RUN docker-php-ext-install pdo_mysql mysqli

# Si tu aplicación necesita otras extensiones, añádelas aquí, por ejemplo:
# RUN docker-php-ext-install gd curl

# Copia tu código PHP al directorio de Apache
# Asume que tu código está en la carpeta 'src'
COPY src/ /var/www/html/

# Establece los permisos correctos (necesario si tu app escribe en el disco)
# Aunque para GAE Flexible es mejor usar Cloud Storage para datos persistentes
RUN chown -R www-data:www-data /var/www/html

# El puerto por defecto para App Engine Flexible es 8080.
# Apache por defecto escucha en el puerto 80, App Engine se encarga del ruteo.
# Sin embargo, si quieres que Apache escuche en 8080:
# Puedes configurar esto en un archivo de configuración de Apache que copies.
# Para este ejemplo, asumiremos que App Engine manejará el puerto 80.
EXPOSE 80