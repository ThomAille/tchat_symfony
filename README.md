tchat_symfony
=============

Lancer les commandes suivante : 

php composer.phar update

php bin/console doctrine:create:database

php bin/console doctrine:schema:update --force

php bin/console server:run

Vous créer des rooms, tchatter dans les rooms. 

Il y a un listeners sur les activités des autres utilisateurs 
afin de pouvoir les afficher connecté

Je n'ai pas eu le temps de faire la même choses pour les messages,
pour mettre en avant l'activité d'une room sur le menu de gauche. 

Il manque aussi les appel Ajax pour afficher les messages en temps réel 
dans les conversations. 