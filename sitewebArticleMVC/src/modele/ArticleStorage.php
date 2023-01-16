<?php

/**
 * Définition de la classe ArticleStorage qui permet de 
 * stocker les articles
 * @author OROU-GUIDOU  Amirath Fara
 * @version 1.0
 */
require_once("src/modele/Article.php");

interface ArticleStorage {

    /************************************************************************
     * Déclaration des méthodes
     ************************************************************************/

    /**
      * Définition de la fonction create() qui permet de créer un article et   * l'insérer dans la base de données
      * @param article : article à créer
    */
    public function create(Article $article);

    /**
      * Définition de la fonction read() qui permet de lire un article
      * @param id : identifiant de l'article à lire
    */
    public function read($id);

    /**
     * Définition de la fonction readAll qui permet de lire tous les articles
     */
    public function readAll();

    /**
     * Définition de la fonction update() qui permet de mettre à jour un article
     * @param id : identifiant de l'article à mettre à jour
     * @param article : article à mettre à jour
     */
    public function update($id, Article $article);

    /**
     * Définition de la fonction delete() qui permet de supprimer un article
     * @param id : identifiant de l'article à supprimer
     */
    public function delete($id);

    /**
     * Définition de la fonction deleteAll() qui permet de supprimer tous les 
     * articles
     */
    public function deleteAll();


}