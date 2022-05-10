Installation
===============================

If you do not have [Composer](http://getcomposer.org/), you may install it by following the instructions
at [getcomposer.org](http://getcomposer.org/doc/00-intro.md#installation-nix).

Before updating the composer of the project run this command to install assept-plugin

```bash
composer global require "fxp/composer-asset-plugin"
composer global require "hirak/prestissimo"
```

And in the last run composer update in root of the project:

```bash
composer install
```

### Configuration

Run this command in root of project and select developement env such a production or developement.

```bash
php init
```

### Migration

For migrate database just use this command and all needed table and data to generate:

```bash
php yii migrate --migrationPath=@yii/rbac/migrations
php yii migrate
php yii mongodb-migrate
```

Each env have own configuration.

Back-End
```
backend/config/main-local.php
backend/config/params-local.php
```

API
```
api/config/main-local.php
api/config/params-local.php
```

Common, Database and email configuration available in this section.
```
common/config/main-local.php
common/config/params-local.php
```

### Translation

For extract the translation run this command each time to generate related files to translate:

```bash
php yii message common/messages/config.php
```

generated translation files exist over here `/path/to/game-center-application/common/messages/{locale}`

### Optimization

Because Composer autoloader is used to include most third-party class files, you should consider optimizing it by executing the following command:
```bash
composer dumpautoload -o
```

GETTING STARTED
---------------
- for frontend `/path/to/navar-application/frontend/web/`
- for backend `/path/to/navar-application/backend/web/`
- for api `/path/to/navar-application/api/web/`

API Documentation Generator
---------------
For generate API document NodeJS and NPM needed to install in the the server.
Install both of NodeJS and NPM usig this link:

https://nodejs.org/en/download/package-manager/#debian-and-ubuntu-based-linux-distributions

And after use this instruction for fix permissions of npm
https://docs.npmjs.com/getting-started/fixing-npm-permissions

And apidoc module need for generate document. Install it with this command:

```bash
npm install apidoc -g
```

Asset managment with node
---------------

```bash
sudo apt-get install ruby-dev gem rubygems
sudo gem install compass --version 1.0.3
sudo gem install sass --version 3.4.22
npm install -g grunt grunt-cli
npm install
```

run:

```bash
grunt build
```