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
     * @param articleId l'id de l'article
     */
    public function deleteArticle($articleId){
        // On récupère l'article dans la base de données
        $article = $this->articleStorage->read($articleId);
        if($article === null){
            $this->vue->makeUnknownArticlePage();
        }
        else{
            $this->vue->makeArticleDeletionPage($articleId, $article);
        }
    }

    /**
     * Définition de la fonction askArticleDeletion pour demander à 
     * l'internaute de confirmer son souhait de supprimer l'article
     * 
     * @param articleId l'id de l'article
     */
    public function askArticleDeletion($articleId){
        // L'utilisateur confirme vouloir supprimer l'article
        $ok = $this->articleStorage->delete($articleId);
        if(!$ok){
            // L'article n'existe pas dans la base de données
            $this->vue->makeUnknownArticlePage();
        }
        else{
            // L'article a bien été supprimé
            $this->vue->makeArticleDeletedPage();
        }
    }

    /**
     * Définition de la fonction modifyArticle pour la modification d'un 
     * article
     * 
     * @param articleId l'id de l'article
     */
    public function modifyArticle($articleId){

        if (key_exists($articleId, $this->modifierArticleBuilder)) {
            // Préparation de la page du formulaire
            $this->vue->makeArticleModifPage($articleId, $this->modifierArticleBuilder[$articleId]);
        }
        else{
            // On recupère dans la base de données l'article à modifier
            $a = $this->articleStorage->read($articleId);

            if($a === null){
                $this->vue->makeUnknownArticlePage();
            }
            else{
                $builder = ArticleBuilder::buildFromArticle($a);
                $this->vue->makeArticleModifPage($articleId, $builder);
            }
        }
    }

    /**
     * Définition de la fonction saveArticleModification pour la sauvegarde 
     * d'un article modifié
     * 
     * @param articleId l'id de l'article
     * @param data les données de l'article
     */
    public function saveArticleModification($articleId, array $data){
        // On recupère en base de données l'article à modifier
        $article = $this->articleStorage->read($articleId);

        if ($article === null){
            $this->vue->makeUnknownArticlePage();
        }
        else{
            // On construit un builder à partir des données de l'article
            $builder = new ArticleBuilder($data);

            //Validation des donnéees
            if ($builder->isValid()){
                $builder->updateArticle($article);
                $ok = $this->articleStorage->update($articleId, $article);
                if (!$ok){
                    throw new Exception("Erreur lors de la mise à jour de l'article");
                }

                // On redirige vers la page de l'article
                unset($this->modifierArticleBuilder[$articleId]);
                $this->vue->displayArticleModifiedSuccess($articleId);
            }
            else{
                $this->modifierArticleBuilder[$articleId] = $builder;
                $this->vue->displayArticleModifiedFailure($articleId);
            }
        }
    }



     
}


