# SiteWebArticleEnPhp
# Articles PHP

Ce site web utilise PHP pour afficher des articles stockés dans une base de données. Il utilise également des templates pour la mise en page des pages. Il n'a pas de page de connexion pour l'administration ou la modification des articles.

## Prérequis
- PHP 7.2 ou supérieur
- Un serveur web (par exemple Apache ou Nginx)
- Une base de données MySQL
- Les extensions PHP PDO et PDO_MySQL

## Installation
1. Téléchargez le code source du projet sur votre serveur web.
2. Créez une base de données et importez le fichier de structure de base de données (articles.sql)
3. Modifiez le fichier de configuration (config.php) pour qu'il pointe vers votre base de données.
4. Accédez au site web à l'aide de votre navigateur.

## Utilisation
- Pour afficher un article, accédez à la page de détail de l'article (http://localhost:8887/SiteWebArticleEnPhp/sitewebArticleMVC/articles.php/2)(le 2 représente l'identifiant de l'article).
- Pour ajouter ou modifier un article, vous pouvez utiliser un outil externe comme phpMyAdmin pour accéder à la base de données directement.
Remarque : Ce README est un exemple générique pour un site web qui utilise PHP pour afficher des articles stockés dans une base de données. Il est fortement conseillé de vérifier les versions des différents composants et d'adapter les instructions d'installation en fonction de votre environnement. Il est important de noter que cette configuration n'est pas sécurisée et donne accès aux données de la base de données à tous les utilisateurs qui ont accès à cet outil externe. Il est donc conseillé de sécuriser cette accès ou de ne pas utiliser cette configuration en production.