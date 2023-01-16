<?php 

/** 
 * Le routeur s'occupe d'analyser les requêtes HTTP
 * pour décider quoi faire et quoi afficher.
 * Il se contente de passer la main au contrôleur et
 * à la vue une fois qu'il a déterminé l'action à effectuer.
 * 
 * @author OROU-GUIDOU  Amirath Fara
 * @version 1.0
 */

set_include_path("vue/ArticleVue.php");
set_include_path("controleur/ArticleControleur.php");

require_once("controleur/ArticleControleur.php");
require_once("vue/ArticleVue.php");

class Router {

    protected $direction = '/SiteWebArticleEnPhp/ProjetStage/sitewebArticleMVC/';

    public function main($articleStorage){

        session_start();
        $feedback = key_exists("feedback", $_SESSION) ? $_SESSION["feedback"] : '';

        $_SESSION["feedback"] = '';
        $view = new ArticleVue($this, $feedback);
        $controller = new ArticleControleur($view, $articleStorage);

        $action = null;
        $articleId = null;

        if (isset($_SERVER['PATH_INFO'])){
            $articleId = strtok($_SERVER['PATH_INFO'], '/');
            $action = strtok('/');

            if(!is_numeric($articleId)){
                $action = $articleId;
                $articleId = null;
            }

            if($action === false){
                $action = null;
            }

            if($action == null && $articleId == null){
                // On est sur la page d'accueil
                $view->makeHomePage();
            }

            else if($action === 'galerie'){
                // Afficher la liste des articles
                if ($_SERVER['REQUEST_METHOD'] === 'POST'){
                    $controller->showListSearch($_POST['search']);
                }
                
                else if ($_SERVER['REQUEST_METHOD'] === 'GET'){
                    $controller->showList();
                }

                else{
                    $view->makeUnknownArticlePage();
                }
            }

            else if ($action === 'nouveau'){
                // Afficher le formulaire de la création d'un article
                $controller->newArticle();
            }

            else if ($action === 'sauverNouveau'){
                // Sauvegarder un nouvel article
                $controller->saveNewArticle($_POST);
            }

            else if ($action === 'modifier'){
                if($articleId === null){
                    $view->makeUnexpectedErrorPage();
                }

                else{
                // afficher le formulaire de modification d'un article
                $controller->modifyArticle($articleId);
                }
            }

            else if ($action === 'sauverModification'){
                // sauvegarder les modifications d'un article
                if($articleId === null){
                    $view->makeUnexpectedErrorPage();
                }else{
                    $controller->saveArticleModification($articleId, $_POST);
                }

            }
            
            else if ($action === 'supprimer') {
                // supprimer un article
                if($articleId === null){
                    $view->makeUnexpectedErrorPage();
                }else{
                    $controller->deleteArticle($articleId);
                }
            } 
            
            else if ($action === 'confirmationSuppression') {
                // afficher la page de confirmation de suppression
                if($articleId === null){
                    $view->makeUnexpectedErrorPage();
                }else{
                     $controller->askArticleDeletion($articleId);
                }
            } 
            
            else if ($action === 'about') {
                // afficher la page à propos
                $view->makeAboutPage();
            } else {
                // afficher la page d'erreur
                if($articleId === null){
                    $view->makeUnexpectedErrorPage();
                }
                
                else{
                    // afficher la page d'information d'un Articlee
                     $controller->showInformation($articleId);
                }
            }
        } else {
            // afficher la page d'accueil
            $view->makeHomePage();
        }

        $view->render();
    }


    /* URL de la page d'accueil */
    public function getHomeURL() {
        return $this->direction . 'articles.php';
    }

    /* URL de la page de l'affichage des listes des article */
    public function getArticleListURL() {
        return $this->direction . 'articles.php/galerie';
    }

    /* URL de la page de l'Article d'identifiant $id */
    function getArticleURL($id){
        return  $this->direction . 'articles.php/' . $id;
    }

    /* URL de la page pour la création  d'un Article */
    function getArticleCreationURL(){
        return $this->direction . 'articles.php/nouveau';
    }

    /* URL de la page pour la sauvegarde de la création*/
    function getArticleSaveURL(){
        return $this->direction . 'articles.php/sauverNouveau';
    }

    /* URL de la page d'édition d'un Article existant */
    public function getArticleModifPageURL($id){
        return $this->direction . 'articles.php/' . $id . '/modifier';
    }

    /* URL d'enregistrement des modifications sur un
	 * Article (champ 'action' du formulaire) */
    public function updateModifiedArticle($id) {
        return $this->direction . 'articles.php/' . $id . '/sauverModification';
    }

    /* URL de la page supprimant effectivement l'Article*/
    function getArticleDeletionURL($id){
        return $this->direction . 'articles.php/' . $id . '/supprimer';
    }

    /*  URL de la page demandant à l'internaute de confirmer son souhait de supprimer l'Article */
    function getArticleAskDeletionURL($id){
        return $this->direction . 'articles.php/' . $id . '/confirmationSuppression';
    }

    /* URL de la page about */
    function getAboutURL(){
        return $this->direction . 'articles.php/about';
    }


    /**
     * Ajout d'une méthode POSTredirect qui envoie une réponse HTTP de type 303 See Other
     * demandant au client de se rediriger vers l'URL passée en argument
    */
    public function POSTredirect($url,$feedback){
        $_SESSION['feedback'] = $feedback;
        header("Location: ".htmlspecialchars_decode($url),true,303);
        die;
    }
}
