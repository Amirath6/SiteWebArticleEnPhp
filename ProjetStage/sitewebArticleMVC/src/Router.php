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

    protected $direction = "/SiteWebArticleEnPhp/ProjetStage/sitewebArticleMVC/";

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
        }
    }
}
