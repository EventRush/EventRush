###     migrations
####     fonction pour la modification des colonnes dans les migrations

php
    composer require doctrine/dbal

application:
    (à modifier)
    $table->enum('etat', ['valide', 'refus', 'en cours']->default('valide'));

    (modification dans une nouvelle migration)
    $table->string('etat')->change();