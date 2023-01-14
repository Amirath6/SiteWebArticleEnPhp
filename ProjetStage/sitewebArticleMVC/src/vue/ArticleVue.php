<?php

/**
 * Définition de la classe ArticleVue qui permet de gérer la vue des articles
 * @author OROU-GUIDOU  Amirath Fara
 * @version 1.0
 */

require_once("src/Router.php");
require_once("src/modele/ArticleBuilder.php");
require_once("src/modele/Article.php");

class ArticleVue {

    protected $router;
    protected $title;
    protected $content;

    protected $feedback;

    /**
     * Constructeur de la classe ArticleVue
     * @param router : routeur de l'application
     * @ffeedback : message de feedback
     */

    public function __construct(Router $router, $feedback){
        $this->router = $router;
        $this->feedback = $feedback;
        $this->title = null;
        $this->content = null;
    }

    /**
     * Définition de la fonction render() qui affiche une page HTML
     * avec le contenu de ces attributs
     */
    public function render(){
        if ($this->title === null || $this->content === null){
            $this->makeUnexpectedErrorPage();
        }
        $title = $this->title;
        $content = $this->content;
        $menu = array(
            "Accueil" => $this->router->getHomeURL(),
            "Liste des articles" => $this->router->getArticleListURL(),
            "Nouvel article" => $this->router->getArticleCreationURL(),
            "A propos" => $this->router->getAboutURL()
        );
        include("Squelette.php");
    }

    /*******************************************************************
     * Méthodes de génération de pages
     *******************************************************************/
    public function makeHomePage() {
		$this->title = "<br>" . "Bienvenue dans le monde des articles" . "<br><br>";
        $this->content = "<div><br><p><strong>Un article </strong> est un texte écrit qui traite d'un sujet ou d'une question d'intérêt général ou spécialisé. Il peut être publié dans différents supports tels que <strong>des journaux, des magazines, des sites internet, des blogs, </strong> etc. <br>Les articles peuvent couvrir une variété de sujets, comme <strong>l'actualité, la politique, les sciences, les technologies, les arts, les sports, la culture, la santé, l'éducation, </strong> etc.</p> <br></div>

        Il existe différents types d'articles, tels que :
        <ul>
        <li>Les articles de fond qui analysent en profondeur un sujet ou une question d'actualité,</li><br>
        <li>Les articles de revue qui font le point sur les dernières avancées dans un domaine spécifique,</li><br>
        <li>Les articles de presse qui couvre l'actualité d'un événement ou d'une situation,</li><br>
        <li>Les articles scientifiques qui présentent les résultats d'une recherche ou d'une expérience,</li><br>
        <li>Les articles de blog qui sont généralement écrits par des personnes qui partagent leur expérience ou leur point de vue personnel sur un sujet donné.</li><br>
        Dans tous les cas, un article est généralement organisé en paragraphes, avec une introduction qui expose le sujet, un développement qui détaille les idées et les arguments, et une conclusion qui résume les principales idées et apporte des perspectives pour la suite. Les articles sont généralement rédigés dans un style clair et concis, et sont destinés à un public spécifique, qui peut être large ou spécialisé.";
	}

    /**
     * Définition de la fonction makeArticlePage() qui génère l'affichage d'une 
     * page sur l'article passé en argument
     * 
     * @param id : identifiant de l'article
     * @param article : article à afficher
     */
    public function makeArticlePage($id, Article $article){

        $titleArt = self::htmlesc($article->getTitre());
        $contenuArt = self::htmlesc($article->getContenu());
        $auteurArt = self::htmlesc($article->getAuteur());
        $dateArt = new DateTime(self::htmlesc($article->getDateCreation()));

        $this->title = "Un article : $titleArt";

        $s = "";
        $s .= "<h2>Desscription de l'article</h2>";
        $s .= "<h3> Cet article a été créé par $auteurArt le $dateArt</h3>";
        $s .= "<p>$contenuArt</p>";
        $s .= "<ul>\n";
        $s .= "<li><a href=\"".$this->router->getArticleModifPageURL($id)."\">Modifier</a></li>\n";
        $s .= "<li><a href=\"".$this->router->getArticleDeletionURL($id)."\">Supprimer</a></li>\n";
        $s .= "</ul>\n";
        $this->content = $s;
    }

    /**
     * Définition de la fonction makeUnknownArticlePage() qui génère 
     * l'affichage du message d'erreur
     */
    public function makeUnknownArticlePage(){
        $this->title = "Erreur Article inconnu";
        $this->content = "Cet article n'existe pas";
    }

    /**
     * Définition de la fonction makeListeArticlePage() qui génère 
     * l'affichage de la liste des articles
     * 
     * @param articles : liste des articles
     */
    public function makeListeArticlePage(array $articles, $error=false){
        $this->title = "Tous les articles";
        $this->content = "";
        $this->content .= "<form class=\"no-border\" action={$this->router->getArticleListURL()} method=\"POST\">"; 
        $this->content .= "<input type=\"text\" id=\"search\" name=\"search\" placeholder=\"Rechercher un article...\">";
        $this->content .= "<button class=\"button info\" type=\"submit\">Rechercher</button>";
        $this->content.= "</form>";
        $this->content .= "<p> Cliquer sur un article pour voir les détails.</p>\n";
        if ($error){
            $this->content .= "<p style=\"text-align:center\">Aucun article ne correspond à votre recherche.</p>\n";
        }
        else{
            $this->content .=  "<ul class =\"gallery\">\n";

            foreach($articles as $article => $value){
                $this->content .= $this->galleryArticle($article, $value);
            }
            $this->content .= "</ul>\n";
        }

    }


    /**
     * Définition d'une méthode makeUnknownList pour le message d'erreur
     */

     public function makeUnknownList(){
        $this->title = "Erreur";
        $this->content = "Il n'y a pas de liste d'article";
    }

    /**
     * Définition de la fonction makeUnknownActionPage() qui génère
     * l'affichage du message d'erreur pour une action inconnue
     */
    public function makeUnknownActionPage() {
		$this->title = "Erreur";
		$this->content = "La page demandée n'existe pas.";
	}

    /* Génère une page d'erreur inattendue. Peut optionnellement
	 * prendre l'exception qui a provoqué l'erreur
	 * en paramètre, mais n'en fait rien pour l'instant. */

     public function makeUnexpectedErrorPage(Exception $e=null){
		$this->title = "Erreur";
		$this->content = "Une erreur inattendue s'est produite." . "<pre>" . var_export($e) . "</pre>";
	}

    /**
     * Définition d'une méthode pour débuger la page
     * Elle va faciliter le debug en nous permettant d'afficher le contenu d'une variable. 
     * 
     * @param variable 
     */
    public function makeDebugPage($variable) {
        $this->title = 'Debug';
        $this->content = '<pre>'.htmlspecialchars(var_export($variable, true)).'</pre>';
    }

    /**
     * Définition d'une fonction makeArticleCreationPage() qui permettra 
     * d'afficher le formulaire de création d'un article
     */
    public function makeArticleCreationPage(ArticleBuilder $builder){
        $this->title = "Ajouter un article";
        $s = '<form action="'.$this->router->getArticleSaveURL().'" method="POST">' . "\n";
        $s .= self::getFormFields($builder);
        $s .= "<button>Créer</button>";
        $s .= "</form>\n";
        $this->content = $s;
    }

    /**
     * Définition d'une méthode displayArticleCreationPage() qui utilise la méthode créée ci-dessus pour rediriger le client vers la page de l'article dont l'identifiant est passé en paramètre
     * @param id l'identifiant de l'Article
    */
    public function displayArticleCreationSuccess($id){
        $this->router->POSTredirect($this->router->getArticleURL($id), "L'article a bien été créé!");
    }

    /**
     * Définition d'une méthode displayArticleCreationFailure() qui permet 
     * d'afficher un message d'erreur si la création de l'article a échoué et 
     * qui nous redirige vers la page de création d'un article
     */
    public function displayArticleCreationFailure(){
        $this->router->POSTredirect($this->router->getArticleCreationURL(), "Erreur dans le formulaire : La création de l'article a échoué!");
    }


    /**
     * Définition d'une méthode makeArticleDeletionPage() qui permettra 
     * d'afficher un formulaire de suppression d'un article
     * @param id l'identifiant de l'article
     * @param a l'objet article
     */

     public function makeArticleDeletionPage($id, Article $a){
        $article = self::htmlesc($a->getTitre());

        $this->title = "Suppression de l'article « {$article} »";
        $this->content = "<p>L'article <strong> {$article} </strong> va être supprimé.</p>\n";
        $this->content .= '<form action="' . $this->router->getArticleAskDeletionURL($id) . '" method="POST">' . "\n";
        $this->content .= "<button>Confirmer</button>\n</form>\n";
    }   

    /**
     * Définition d'une méthode makeArticleDeletedPage() qui permettra 
     * d'afficher un message de confirmation de suppression d'un article
     */
    public function makeArticleDeletedPage() {
		$this->router->POSTredirect($this->router->getArticleListURL(), "L'article a bien été supprimé !");
	}

    /**
     * Définition d'une méthode makeArticleModifPage() qui permettra d'afficher un formulaire de modification d'un article
     * @param id l'identifiant de l'article
     * @param builder l'objet ArticleBuilder
     */
    public function makeArticleModifPage($id, ArticleBuilder $builder){
        $this->title = "Modifier l'article";

        $this->content = '<form action="' . $this->router->updateModifiedArticle($id) . '" method="POST">' . "\n";
        $this->content .= self::getFormFields($builder);
        $this->content .= '<button>Modifier</button>' . "\n";
        $this->content .= '</form>' . "\n";
    }

    /**
     * Définition d'une méthode displayArticleModifSuccess() qui permet d'afficher un message de confirmation de modification d'un article
     * @param id l'identifiant de l'article
     */
    public function displayArticleModifiedSuccess($id){
        $this->router->POSTredirect($this->router->getArticleURL($id), "L'article a été bien modifié !");
    }

    /**
     * Définition d'une méthode displayArticleModifFailure() qui permet d'afficher un message d'erreur si la modification de l'article a échoué et qui nous redirige vers la page de modification d'un article
     * @param id l'identifiant de l'article
     */
    public function displayArticleModifiedFailure($id){
        $this->router->POSTredirect($this->router->getArticleModifPageURL($id), "Erreurs dans le formulaire : La modification de l'article a échoué !");
    }


    /* Définition de la function makeAboutPage() */
    public function makeAboutPage() {
        $this->title = "A propos";
        $this->content = "<strong><u>Nom de L'étudiant</u> : OROU-GUIDOU</strong><br><br>";
        $this->content .= "<strong><u>Prénom de L'étudiant</u> : Amirath Fara</strong><br><br>";
        $this->content .= "<strong><u>Numéro de l'étudiant</u> : 22012235</strong><br><br>";
        $this->content .= "<strong><u>Diplôme</u> : Licence 3 Informatique</strong><br><br>";
        $this->content .= "<strong><u>Groupe TD/TP</u> : 2B</strong><br>";
        $this->content .= "<p>Le but de ce site est de gérer des objets en PHP(ici les informations sur les Articlees ou la description d'un Articlee, son nom , date de naissance, son genre de musique l'année de début de sa carrière
        ...) et de créer un site respectant le modèle MVCR vu en cours et en TP.<br> J'ai intégrer les fonctionnalités suivantes :</p>";
        $this->content .= "<ul>";
        $this->content .= "<li>La Liste des Articlees</li>";
        $this->content .= "<li>Création d'un Articlee</li>";
        $this->content .= "<li>Modification d'un Articlee</li>";
        $this->content .= "<li>Utilisation d'un builder pour la validation et la création d'un objet</li>";
        $this->content .= "<li>Suppression d'un Articlee</li>";
        $this->content .= "<li>Redirection POST après creation/modification/suppression réussite ou echouer avec un message feedback(gestion du feedback)</li>";
        $this->content .= "<li>Utilisation d'une base de donnée MySql</li>";
        $this->content .= "</ul>";

        $this->content.= "<br><br>";
        $this->content .= "<strong><u>Optionnels réalisés</u> :</strong><br>";
        $this->content .= "<ul>";
        $this->content .= "<li>Routage via le chemin virtuel (PATH_INFO) dans les URL plutôt qu'avec des paramètres d'URL</li>";
        $this->content .= "<li>En plus de l'optionnel du path info j'ai aussi fait l'optionnel : possibilité de filtrer la liste des objets via un champ de recherche</li>";

        $this->content .= "</ul>";

        $this->content .= "<u>Notes :</u><br><br>";
        $this->content .= "Par rapport aux optionnels je voulais aussi faire celui de upload d'image mais je n'ai pas réussi à le faire.<br>
        J'ai essayé mais j'ai pas pu le faire fonctionner.<br> Mais par rapport à l'ajout d'un Articlee au niveau de l'image, j'arrive à ajouter l'image mais les images qu'il faut mettre même si cela va être télécharger 
        sur internet doit être dans le dossier images dans le répertoire <strong><u>dm-tw4b-2022/filrougeArticlee/exoMVCR/images</u></strong> sinon cela ne fonctionne pas.<br> J'ai aussi mis des images dans le dossier upload pour que vous puissiez tester.<br> 
        Mais les images qui sont dans upload sont aussi dans le dossier images car quand j'envoi l'image de upload, l'image ne s'affiche pas parce qu'il ne retrouves pas le fichier dans la base.
        <br><br>";

        $this->content .= "<p style=\"text-align:center\"><strong><u>MERCI</u></strong></p>";

    }

    /******************************************************************************/
	/* Méthodes utilitaires                                                       */
	/******************************************************************************/
    /**
     * Méthode pour la gallery des articles c'est à dire la liste des articles
     * @param id l'identifiant de l'article
     * @param a l'article
     */
    protected function galleryArticle($id, $a){
        $res = '<li><a href="' . $this->router->getArticleURL($id).'">';
        $res .= '<h3>' . self::htmlesc($a->getArticle()). '</h3>';
        $res .= '</a></li>'."\n";
		return $res;
    }




    /**
     * Méthode getFormFields pour la forme de la page de création d'un nouvel Article
     */
    protected function getFormFields(ArticleBuilder $builder){
        
        $titleref = $builder->getTitreRef();
        $s = "";
        $s .= '<p><label>Titre : <input type="text" name="' . $titleref . '" value="';
        $s .= self::htmlesc($builder->getData($titleref));
        $s .= "\" placeholder='ex:City News' />";
        $err = $builder->getErrors($titleref);
        if($err !== null){
            $s .= '<span class="error">' . $err . '</span>';
        }
        $s .= "</label></p>\n";

        $contenuref = $builder->getContenuRef();
        $s .= '<p><label>Contenu de l\'article : <textarea cols="40" rows="5" name="' . $contenuref . '" placeholder="ex:City News est un magazine de presse écrite hebdomadaire, fondé en 1924 par le journaliste et écrivain américain Henry Luce....">';
        $s .= self::htmlesc($builder->getData($contenuref));
        $s .= "</textarea>";
        $err = $builder->getErrors($contenuref);
        if($err !== null){
            $s .= '<span class="error">' . $err . '</span>';
        }
        $s .= "</label></p>\n";

        $auteurref = $builder->getAuteurRef();
        $s .= '<p><label>Auteur : <input type="text" name="' . $auteurref . '" value="';
        $s .= self::htmlesc($builder->getData($auteurref));
        $s .= "\" placeholder='ex:John Doe' />";
        $err = $builder->getErrors($auteurref);
        if($err !== null){
            $s .= '<span class="error">' . $err . '</span>';
        }
        $s .= "</label></p>\n";

        $dateref = $builder->getDateCreationRef();
        $s .= '<p><label>Date de création : <input type="date" name="' . $dateref . '" value="';
        $s .= self::htmlesc($builder->getData($dateref));
        $s .= "\" placeholder='ex:2021-12-31' />";
        $err = $builder->getErrors($dateref);
        if($err !== null){
            $s .= '<span class="error">' . $err . '</span>';
        }
        $s .= "</label></p>\n";

        return $s;
    }

    /* Une fonction pour échapper les caractères spéciaux de HTML,
	* car celle de PHP nécessite trop d'options. */
	public static function htmlesc($str) {
		return htmlspecialchars($str,
			/* on échappe guillemets _et_ apostrophes : */
			ENT_QUOTES
			/* les séquences UTF-8 invalides sont
			* remplacées par le caractère �
			* au lieu de renvoyer la chaîne vide…) */
			| ENT_SUBSTITUTE
			/* on utilise les entités HTML5 (en particulier &apos;) */
			| ENT_HTML5,
			'UTF-8');
	}
}


