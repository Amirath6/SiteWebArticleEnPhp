<?php 

/**
 * Définition de la classe ArticleBuilder qui permet de construire un article 
 * via un formulaire
 * @author OROU-GUIDOU  Amirath Fara
 * @version 1.0
 */

require_once("src/modele/Article.php");

class ArticleBuilder {

    /* Attributs de la classe */

    const TITRE_REF = "titre";
    const CONTENU_REF = "contenu";
    const AUTEUR_REF = "auteur";
    const DATE_CREATION_REF = "dateCreation";

    private $data;

    private $errors;

    /**
     * Constructeur de la classe ArticleBuilder
     * @param $data : données du formulaire
     * @param $errors : erreurs du formulaire
     */

    public function __construct($data=null){
        if($data === null) {
            $data = array(
                self::TITRE_REF => "",
                self::CONTENU_REF => "",
                self::AUTEUR_REF => "",
                self::DATE_CREATION_REF => ""
            );
        }
        $this->data = $data;
        $this->errors = array();
    }

    /**
     * Définition d'une fonction buildFromArticle() qui renvoie une nouvelle 
     * instance de la classe ArticleBuilder avec les données modifiables de     
     * l'article passé en paramètre        
     * @param $article 
     */
    public static function buildFromArticle(Article $article){
        return new ArticleBuilder(array(
            "titre" => $article->getTitre(),
            "contenu" => $article->getContenu(),
            "auteur" => $article->getAuteur(),
            "dateCreation" => $article->getDateCreations(),
        ));
    }

    /*************************************************************************
     * @GETTER
     ************************************************************************/
    
    /**
     * Définition de la fonction getData() qui renvoie la valeur d'un champ en 
     * fonction de la référence passée en argument
     * @param $ref : référence du champ
    */
    public function getData($ref){
        var_dump($this->data);
        return key_exists($ref, $this->data) ? $this->data[$ref] : '';
    }


    public function setData($ref, $str){
        $this->data[$ref] = $str;
    }

    /**
     * Définition de la fonction getErrors() qui renvoie les erreurs associées 
     * au champ de la référence passée en argument ou null s'il n'y a pas 
     * d'erreur
     * @param $ref : référence de l'erreur
    */
    public function getErrors($ref){
        return key_exists($ref, $this->errors) ? $this->errors[$ref] : null;
    }

    /**
     * Définition de la fonction createArticle() qui crée une nouvelle instance 
     * d'Article en utilisant l'attribut data
     */
    public function createArticle(){
        if (!key_exists(self::TITRE_REF, $this->data) || !key_exists(self::CONTENU_REF, $this->data) || !key_exists(self::AUTEUR_REF, $this->data) || !key_exists(self::DATE_CREATION_REF, $this->data)) {
            throw new Exception("Impossible de créer un article : données manquantes");
        }
        return new Article($this->data[self::TITRE_REF], $this->data[self::CONTENU_REF], $this->data[self::AUTEUR_REF], $this->data[self::DATE_CREATION_REF]);
    }

    /**
     * Définition de la fonction isValid() qui vérifie que les données de son 
     * attribut $data sont valides ou non
     */
    public function isValid(){

        $this->errors = array();

        if (!key_exists(self::TITRE_REF, $this->data) || $this->data[self::TITRE_REF] === '') {
            $this->errors[self::TITRE_REF] = "Le titre de l'article est obligatoire";
        }
        else if (mb_strlen($this->data[self::TITRE_REF]) >= 50) {
            $this->errors[self::TITRE_REF] = "Le titre de l'article doit comporter au moins 50 caractères";
        }

        if (!key_exists(self::CONTENU_REF, $this->data) || $this->data[self::CONTENU_REF] === '') {
            $this->errors[self::CONTENU_REF] = "Le contenu de l'article est obligatoire";
        }
        else if (mb_strlen($this->data[self::CONTENU_REF]) >= 2000) {
            $this->errors[self::CONTENU_REF] = "Le contenu de l'article doit comporter au moins 2000 caractères";
        }

        if (!key_exists(self::AUTEUR_REF, $this->data) || $this->data[self::AUTEUR_REF] === '') {
            $this->errors[self::AUTEUR_REF] = "L'auteur de l'article est obligatoire";
        }
        else if (mb_strlen($this->data[self::AUTEUR_REF]) >= 50) {
            $this->errors[self::AUTEUR_REF] = "L'auteur de l'article doit comporter au moins 50 caractères";
        }

        if(!key_exists(self::DATE_CREATION_REF, $this->data) || $this->data[self::DATE_CREATION_REF] === ''){
            $this->errors[self::DATE_CREATION_REF] = "La date de création de l'article est obligatoire";
        }
        else if(!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$this->data[self::DATE_CREATION_REF])){
            $this->errors[self::DATE_CREATION_REF] = "La date de création de l'article doit être au format YYYY-MM-DD";
        }

        return count($this->errors) == 0;
    }

    /**
     * Définition de la fonction getTitreRef qui renvoie la référence du champ
     * titre
     */
    public static function getTitreRef(){
        return self::TITRE_REF;
    }

    /**
     * Définition de la fonction getContenuRef qui renvoie la référence du champ
     * contenu
     */
    public static function getContenuRef(){
        return self::CONTENU_REF;
    }

    /**
     * Définition de la fonction getAuteurRef qui renvoie la référence du champ
     * auteur
     */
    public static function getAuteurRef(){
        return self::AUTEUR_REF;
    }

    /**
     * Définition de la fonction getDateCreationRef qui renvoie la référence du 
     * champ date de création
     */
    public static function getDateCreationRef(){
        return self::DATE_CREATION_REF;
    }

    /**
     * Définition de la fonction updateArticle() qui met à jour les données d'un
     * article avec les données fournies
     * @param article : article à mettre à jour
     */
    public function updateArticle(Article $article){

        if (key_exists(self::TITRE_REF, $this->data)) {
            $article->setTitre($this->data[self::TITRE_REF]);
        }

        if (key_exists(self::CONTENU_REF, $this->data)) {
            $article->setContenu($this->data[self::CONTENU_REF]);
        }

        if (key_exists(self::AUTEUR_REF, $this->data)) {
            $article->setAuteur($this->data[self::AUTEUR_REF]);
        }

        if (key_exists(self::DATE_CREATION_REF, $this->data)) {
            $article->setDateCreation($this->data[self::DATE_CREATION_REF]);
        }
    }

}