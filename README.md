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

### Credenciales
Cédula: 1234
Contraseña: 1234
