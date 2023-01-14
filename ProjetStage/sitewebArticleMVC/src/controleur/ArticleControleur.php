<?php 

/**
 * Définition de la classe ArticleControleur qui permet de 
 * gérer les articles
 * @author OROU-GUIDOU  Amirath Fara
 * @version 1.0
 */
require_once("src/modele/ArticleStorage.php");
require_once("src/modele/ArticleBuilder.php");
require_once("src/modele/Article.php");
require_once("src/vue/ArticleVue.php");

class ArticleControleur {

    protected $vue;

    protected $articleStorage;

    protected $currentArticleBuilder;

    protected $modifierArticleBuilder;

    /**
     * Constructeur de la classe ArticleControleur
     * @param ArticleVue $vue
     * @param ArticleStorage $articleStorage
     */
    public function __construct(ArticleVue $vue, ArticleStorage $articleStorage)
    {
        $this->vue = $vue;
        $this->articleStorage = $articleStorage;
        $this->currentArticleBuilder = key_exists("currentArticleBuilder", $_SESSION) ? $_SESSION["currentArticleBuilder"] : null;
        $this->modifierArticleBuilder = key_exists("modifierArticleBuilder", $_SESSION) ? $_SESSION["modifierArticleBuilder"] : null;
    }

    /**
     * Function __destruct() qui permet de détruire les variables de session
     */
    public function __destruct()
    {
        $_SESSION["currentArticleBuilder"] = $this->currentArticleBuilder;
        $_SESSION["modifierArticleBuilder"] = $this->modifierArticleBuilder;
    }

    /********************************************************************
     * @GETTERS
     *******************************************************************/
    /**
     * Function getVue() qui permet de retourner la vue
     * @return vue
     */
    public function getVue(){
        return $this->vue;
    }

    /**
     * Function getArticleStorage() qui permet de retourner l'articleStorage
     * @return articleStorage
     */
    public function getArticleStorage()
    {
        return $this->articleStorage;
    }

    /**
     * Définition de la fonction showInformation qui permet d'afficher les  
     * informations d'un article
     * @param articleId l'id de l'article
     */
    public function showInformation($articleId)
    {
        $storage = $this->articleStorage->read($articleId);
        if ($storage == null) {
            $this->vue->makeUnknownArticlePage();
        } else {
            $this->vue->makeArticlePage($articleId, $storage);
        }
    }

    /**
     * Définition de la fonction showList qui permet d'afficher la liste des articles
     */
    public function showList(){
        $articles = $this->articleStorage->readAll();
        $this->vue->makeListeArticlePage($articles);
    }

    /**
     * Définition de la fonction showListSearch pour la recherche d'un 
     * article
     * @param search le mot clé de recherche
     */
    public function showListSearch($search)
    {
        $articles = $this->articleStorage->readAll();
        $resFinal = array();
        foreach($articles as $article => $value){
            if (preg_match("/" . $search . "/", $value->getTitre())){
                $resFinal[$article] = $value;
            }
        }
        $this->vue->makeListeArticlePage($resFinal, count($resFinal) === 0);
    }

    /**
     * Définition de la fonction newArticle pour la création d'un nouvel  
     * article
     */
    public function newArticle(){

        if ($this->currentArticleBuilder == null) {
            $this->currentArticleBuilder = new ArticleBuilder();
        }
        $this->vue->makeArticleCreationPage($this->currentArticleBuilder);
    }

    /**
     * Définition de la fonction saveNewArticle pour la sauvegarde d'un 
     * nouvel article
     * @param data les données de l'article
     */
    public function saveNewArticle($data){
        $this->currentArticleBuilder = new ArticleBuilder($data);
        if ($this->currentArticleBuilder->isValid()) {

            //On construit le nouvel article
            $article = $this->currentArticleBuilder->createArticle();

            // On l'ajoute dans la base de données
            $articleId = $this->articleStorage->create($article);

            //On détruit le builder courant
            $this->currentArticleBuilder = null;

            //On redirige vers la page de l'article
            $this->vue->displayArticleCreationSuccess($articleId);
        }
        else {
            $this->vue->displayArticleCreationFailure();
        }        

    }

    /**
     * Définition de la fonction deleteArticle pour la suppression d'un
     * article
     */


     
}


