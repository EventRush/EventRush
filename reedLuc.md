###     migrations
####     fonction pour la modification des colonnes dans les migrations

php
    composer require doctrine/dbal

application:
    (à modifier)
    $table->enum('etat', ['valide', 'refus', 'en cours']->default('valide'));

    (modification dans une nouvelle migration)
    $table->string('etat')->change();
    laravel ne permet pas la modification d'une colonne enum de manière à y ajouter un autre variable d'enumeration (pour postgresql) il faudra passer de enum à string en utilisant change()

###     authentification

Il existe 2 type de token sanctum :
    - token frontend (spa, blade) : à ignorer (transienttoken)
    - Api mobile (token en header) : à expirer