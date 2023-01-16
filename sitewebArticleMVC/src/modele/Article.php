<?php

/**
 * Définition de la classe Article qui représente un article
 * @author OROU-GUIDOU  Amirath Fara
 * @version 1.0
*/

class Article {
     
    /* Attributs de la classe  */

    private $titre;
    private $contenu;
    private $auteur;
    private $dateCreation;

    /**
     * Constructeur de la classe Article
     * @param titre : titre de l'article
     * @param contenu : contenu de l'article
     * @param auteur : auteur de l'article
     * @param dateCreation : date de création de l'article
     */
    public function __construct($titre, $contenu, $auteur, $dateCreation)
    {
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->auteur = $auteur;
        $this->dateCreation = $dateCreation;
    }

    /****************************************************************************
     * @GETTER
     *****************************************************************************/

    /**
     * Définition de la fonction getTitre()
     * @return titre : titre de l'article
     */
    public function getTitre(){
        return $this->titre;
    }

    /**
     * Définition de la fonction getContenu()
     * @return contenu : contenu de l'article
     */
    public function getContenu(){
        return $this->contenu;
    }

    /**
     * Définition de la fonction getAuteur()
     * @return auteur : auteur de l'article
     */
    public function getAuteur(){
        return $this->auteur;
    }

    /**
     * Définition de la fonction getDateCreation()
     * @return dateCreation : date de création de l'article
     */
    public function getDateCreations(){
        return $this->dateCreation;
    }

    /****************************************************************************
     * @SETTER
     *****************************************************************************/

    /**
     * Définition de la fonction setTitre()
     * @param titre : titre de l'article
     */
    public function setTitre($titre){
        $this->titre = $titre;
    }

    /**
     * Définition de la fonction setContenu()
     * @param contenu : contenu de l'article
     */
    public function setContenu($contenu){
        $this->contenu = $contenu;
    }

    /**
     * Définition de la fonction setAuteur()
     * @param auteur : auteur de l'article
     */
    public function setAuteur($auteur){
        $this->auteur = $auteur;
    }


    /**
     * Définition de la fonction setDateCreation()
     * @param dateCreation : date de création de l'article
     */
    public function setDateCreation($dateCreation){
        $this->dateCreation = $dateCreation;
    }
}