<?php

/*
 * On indique que les chemins des fichiers qu'on inclut
 * seront relatifs au répertoire src.
 */
set_include_path("./src");


/* Inclusion des classes utilisées dans ce fichier */
require_once("src/Router.php");
require_once("src/vue/ArticleVue.php");
require_once("src/controleur/ArticleControleur.php");
require_once("src/modele/ArticleStorage.php");
require_once("src/modele/Article.php");
require_once("src/modele/ArticleStorageMySQL.php");
require_once("private/mysql_config.php");

/**
 * Cette page est le point d'entrée du site web.
 * On se contente de créer un routeur et de lancer la méthode main.
 */

$dsn = "mysql:host=" . MYSQL_HOST . "; port=" . MYSQL_PORT . "; dbname=" . MYSQL_DB . "; charset=" . MYSQL_CHARSET;

$user = MYSQL_USER;

$password = MYSQL_PASSWORD;

$pdo = new PDO($dsn, $user, $password);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$animalStorageMySQL = new ArticleStorageMySQL($pdo);
$router = new Router();
$router->main($animalStorageMySQL);

