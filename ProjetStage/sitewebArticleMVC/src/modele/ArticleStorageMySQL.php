<?php

/**
 * Définition de la classe ArticleStorageMySQL qui permet de
 * stocker les articles dans une base de données MySQL
 * @author OROU-GUIDOU  Amirath Fara
 * @version 1.0
 */

class ArticleStorageMySQL implements ArticleStorage {

    /* Attributs de la classe */
    private $database;

    /**
     * Constructeur de la classe ArticleStorageMySQL
     * @param pdo : PHP Data Object
     */
    public function __construct($pdo){
        $this->database = $pdo;
    }

    /************************************************************************
     * Déclaration des méthodes de l'interface ArticleStorage
     ************************************************************************/
    /**
      * Définition de la fonction create() qui permet de créer un article et   * l'insérer dans la base de données
      * @param article : article à créer
    */
    public function create(Article $article){
        // Préparation de la requête
        $requete = "INSERT INTO article (titre, contenu, auteur, dateCreation) VALUES (:titre, :contenu, :auteur, :dateCreation)";

        // Exécution de la requête
        $statement = $this->database->prepare($requete);
        $statement->bindValue(":titre", $article->getTitre(), PDO::PARAM_STR);
        $statement->bindValue(":contenu", $article->getContenu(), PDO::PARAM_STR);
        $statement->bindValue(":auteur", $article->getAuteur(), PDO::PARAM_STR);
        $statement->bindValue(":dateCreation", $article->getDateCreation(), PDO::PARAM_STR);
        $statement->execute();
        return $this->database->lastInsertId();
    }

    /**
      * Définition de la fonction read() qui permet de lire un article
      * @param id : identifiant de l'article à lire
    */
    public function read($id){

        $requete = "SELECT * FROM article WHERE id = :id";

        // Préparation de la requête
        $statement = $this->database->prepare($requete);

        // Exécution du statement
        $statement->execute(array(":id" => intval($id)));

        // utilisation classique du fetch
        $fetch = $statement->fetch(PDO::FETCH_ASSOC);

        if ($fetch){
            return new Article($fetch["titre"], $fetch["contenu"], $fetch["auteur"], $fetch["dateCreation"]);
        }
        return null;
    }

    /**
     * Définition de la fonction readAll qui permet de lire tous les articles
     */
    public function readAll(){
        $requete = "SELECT * FROM article";
        $statement = $this->database->prepare($requete);
        $statement->execute();
        $fetchAll = $statement->fetchAll();
        $data = array();
        foreach($fetchAll as $key => $value){
            $data[$value['id']] = new Article($value["titre"], $value["contenu"], $value["auteur"], $value["dateCreation"]);
        }
        return $data;
    }

    /**
     * Définition de la fonction update() qui permet de mettre à jour un article
     * @param id : identifiant de l'article à mettre à jour
     * @param article : article à mettre à jour
     */
    public function update($id, Article $article){
        $requete = "UPDATE article SET titre = :titre, contenu = :contenu, auteur = :auteur, dateCreation = :dateCreation WHERE id = :id";
        $statement = $this->database->prepare($requete);
        $statement->bindValue(":titre", $article->getTitre(), PDO::PARAM_STR);
        $statement->bindValue(":contenu", $article->getContenu(), PDO::PARAM_STR);
        $statement->bindValue(":auteur", $article->getAuteur(), PDO::PARAM_STR);
        $statement->bindValue(":dateCreation", $article->getDateCreation(), PDO::PARAM_STR);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->execute();
    }

    /**
     * Définition de la fonction delete() qui permet de supprimer un article
     * @param id : identifiant de l'article à supprimer
     */
    public function delete($id){
        $requete = "DELETE FROM article WHERE id = :id";
        $statement = $this->database->prepare($requete);
        $statement->bindValue(":id", $id, PDO::PARAM_INT);
        return $statement->execute();
    }

    /**
     * Définition de la fonction deleteAll() qui permet de supprimer tous les 
     * articles
     */
    public function deleteAll(){
        $requete = "DELETE FROM article";
        $statement = $this->database->prepare($requete);
        $statement->execute();
        return $this->database->lastInsertId();
    }

}