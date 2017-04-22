## Sistema de Gestión de Trámites Administrativos de la Universidad de Carabobo (SISTRAMDUC)

El personal docente y de investigación de la Universidad de Carabobo cuenta con una serie de trámites administrativos que se rigen por el Estatuto Docente. En búsqueda de cumplir con la Ley de Simplificación de Trámites Administrativos, la cual tiene como propósito racionalizar y optimizar las tramitaciones que realizan las personas ante la Administración Pública a los fines de mejorar su eficacia, eficiencia entre otros, se plantea la creación de un sistema web centralizado de gestión de trámites docentes.

El sistema busca la mayor automatización administrativa de los trámites haciendo énfasis en la autogestión, así como también dar simplicidad, celeridad, eficacia y eficiencia, además de mejorar el servicio y las relaciones entre los diferentes organismos y las personas.

### Trámites Administrativos
 - Solicitud de Comisión de Servicios Remunerada
 - Solicitud de Beneficios Mensuales y Mensual con Período Lectivo No Fiscal
 - Solicitud de Trámites Mensual y Anual (útiles escolares, guardería y bono de alimentación)
 - Solicitud de Descarga Horaria
 - Solicitud de Reincorporación
 - Solicitud de Apertura de Consurso de Preparadores
 - Solicitud de Apertura de Concurso de Oposición
 - Solicitud de Jubilación

### Tecnologías
 - [Symfony Framework](https://symfony.com/)
 - [PostgreSQL](https://www.postgresql.org/)
 - [Twitter Bootstrap](http://twitter.github.com/bootstrap/)
 - [jQuery](http://jquery.com)

### Para los que tengan versiones menores a 5.6 de php
```bash
$ sudo add-apt-repository ppa:ondrej/php -y
$ sudo apt-get update -y
$ sudo apt-get install php7.0-curl php7.0-cli php7.0-dev php7.0-gd php7.0-intl php7.0-mcrypt php7.0-json php7.0-mysql php7.0-opcache   php7.0-bcmath php7.0-mbstring php7.0-soap php7.0-xml php7.0-zip -y
$ sudo mv /etc/apache2/envvars /etc/apache2/envvars.bak
$ sudo apt-get remove libapache2-mod-php5 -y
$ sudo apt-get install libapache2-mod-php7.0 -y
$ sudo cp /etc/apache2/envvars.bak /etc/apache2/envvars
$ sudo apt-get install php7.0-pgsql
```
### Para los que les da error de password duplicate
```bash
$ sudo service postgresql start
$ psql
ubuntu=# CREATE USER username SUPERUSER PASSWORD 'password';
ubuntu=# \q

Luego en el archivo parameters.yml se colocan las credenciales de super usuario y listo.
```

### Instalación

```bash
$ cd sistramduc
$ composer install
$ npm install -g bower
$ bower install
$ php bin/console doctrine:database:create
$ php bin/console doctrine:schema:update --force
$ php bin/console doctrine:fixtures:load
$ php bin/console server:run
```

### Para agregar el BarcodeBundle (permite generar el código de barra)
...bash
$ composer require hackzilla/barcode-bundle ~2.0
...

### Para que no escriban tanto
```bash
$ alias rd='sudo service postgresql start;php bin/console doctrine:database:drop --force --env=dev;php bin/console doctrine:database:create --env=dev;php bin/console doctrine:schema:update --force --env=dev;php bin/console doctrine:fixtures:load --no-interaction --env=dev;'
$ rd
```

### Credenciales
Cédula: 1234
Contraseña: 1234
