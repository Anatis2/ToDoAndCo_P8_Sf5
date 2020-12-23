# TODOANDCO_P8

ToDo&Co est une application de gestion de tâches.

Elle correspond au projet 8 du parcours de développeur d'applications PHP/Symfony de OpenClassrooms.

Etapes d'installation
========================

1) Installez les librairies, en tapant la commande "composer install"

2) Si besoin, modifiez les données de configuration du .env (notamment le nom d'utilisateur et le mot de passe, dans DATABASE_URL)

3) Créez la base de données, en tapant la commande "php bin/console doctrine:database:create"

4) Créez le schéma de la base de données, grâce à la commande "php bin/console doctrine:schema:update --force"

5) Insérez les données de test, avec la commande "php bin/console doctrine:fixtures:load"


