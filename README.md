# Oliker

Oliker is an open source classified platform that is capable to run classified sites similar to OLX, Quickr, etc (You can say OLX clone). It is written in AngularJS with REST API for high performance in mind.


TODO insert screenshot

## Support

Oliker classified platform is an open source project. Full commercial support (commercial license, customization, training, etc) are available through [Oliker classified platform support](https://www.agriya.com/solutions/classified-ads-solution)

Theming partner [CSSilize for design and HTML conversions](http://cssilize.com/)

## Getting Started

### Prerequisites

#### For deployment

* PostgreSQL
* PHP >= 5.5.9 with OpenSSL, PDO, Mbstring and cURL extensions
* Nginx (preferred) or Apache

#### For building (build tools)

* Nodejs
* Composer
* Bower
* Grunt

### Setup

* PHP dependencies are handled through `composer` (Refer `/server/php/Slim/`)
* JavaScript dependencies are handled through `bower` (Refer `/client/`)
* Needs writable permission for `/tmp/` and `/media/` folders found within project path
* Build tasks are handled through `grunt`
* Database schema `/sql/oliker_with_empty_data.sql`

### License

Copyright (c) 2014-2018 [Agriya](https://www.agriya.com/).

Dual License (OSL 3.0 & [Commercial License](https://www.agriya.com/contact))
