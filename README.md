# TODOANDCO_P8

[![Codacy Badge](https://app.codacy.com/project/badge/Grade/5ac09926bfd0458ca7ec11426aef2757)](https://www.codacy.com/gh/Anatis2/ToDoAndCo_P8_Sf5/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Anatis2/ToDoAndCo_P8_Sf5&amp;utm_campaign=Badge_Grade)

ToDo&Co est une application de gestion de tâches.

Elle correspond au projet 8 du parcours de développeur d'applications PHP/Symfony de OpenClassrooms.


Etapes d'installation
========================

1) Installez les librairies, en tapant la commande "composer install"

2) Si besoin, modifiez les données de configuration du .env (notamment le nom d'utilisateur et le mot de passe, dans DATABASE_URL)

3) Créez la base de données, en tapant la commande "php bin/console doctrine:database:create"

4) Créez le schéma de la base de données, grâce à la commande "php bin/console doctrine:schema:update --force"

5) Insérez les données de test, avec la commande "php bin/console doctrine:fixtures:load"


Comptes de connexion
======================

Utilisateur :
---------------
 #. Email : user@user.fr
 #. Mot de passe : user
 
Administrateur :
---------------
 #. Email : admin@admin.fr
 #. Mot de passe : admin
 

Démarches à respecter lors de la modification du projet
==========================================================

L’application TodoAndCo est déjà viable, mais des fonctionnalités risquent de devoir être ajoutées ou modifiées.

Quand c’est le cas, des issues sont ajoutées à GitHub, et éventuellement assignées à des développeurs en particulier.

La liste de ces issues est présente sur le lien suivant : https://github.com/Anatis2/ToDoAndCo_P8_Sf5/issues

Avant de modifier le projet, il faut déjà le mettre à jour sur votre machine locale.
Pour cela, tapez la commande **git pull**.

Ensuite, créez une branche spécialisée pour la fonctionnalité que vous souhaitez ajouter ou modifier (**git branch nomDeLaBranche**).

Tout au long de l’implémentation de cette fonctionnalité, n’oubliez pas :
 * de vérifier que l'ensemble de vos fichiers sont bien suivis (si ce n'est pas le cas : **git add .**)
 * d’effectuer des commits réguliers, avec des messages clairs (**git commit -m “message expliquant ce que vous avez fait”**), 
 * éventuellement d’effectuer un push sur cette branch (**git push nomDeLaBranche**).

Lorsque vous pensez que votre fonctionnalité est prête, ou que vous souhaitez la soumettre à quelqu’un pour qu’il puisse vous aider, rendez-vous sur votre repo sur le site de GitHub et proposez une **pull request** à un contributeur.

Il ne vous restera plus qu’à attendre que ce collaborateur valide cette pull request, suite à quoi vous pourrez merger votre branche sur la branche principale.
Pour ce faire, rendez-vous sur la branche master (**git checkout master**), puis effectuez un merge de la branche correspondante (**git merge nomDeLaBranche**).

Enfin, effectuez un push de cette branche.
