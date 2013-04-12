Commencé avec BF13
==================

# Prérequis

* Symfony 2.2
* TwitterBoostrap 2

# Installation

### Etape 1 : Installation du bundle avec Composer

Ajoutez BusinessApplicationBundle dans le fichier `composer.json`
```json
{
    "require": {
        "bf13/business-application-bundle": "dev-master"
    }
}
```

Lancez l'installation
```sh
$ composer.phar update bf13/business-application-bundle
```

### Etape 2 : Activation du bundle

Activer le bundle dans le kernel:
``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new BF13\Bundle\BusinessApplicationBundle\BF13BusinessApplicationBundle(),
    );
}
```

### Etape 3 : Modification du schéma de données

Lancez la commande suivante:
```sh
$ php app/console doctrine:schema:update --force
```

### Etape 4 : Création d'un bundle

Le but de ce bundle est de fournir un point d'entré.

Il offre un mode d'implémentation.




