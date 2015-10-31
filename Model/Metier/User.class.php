<?php
require_once('Batiment.class.php');
require_once('Ressource.class.php');
require_once('Technologie.class.php');
require_once('Unite.class.php');

class User {
    private $_pseudo;		// string
    private $_coordonnee;	// string
    private $_faction;		// string
    private $_password;		// string
    private $_ressources;	// Ressource[]
    private $_technologies;	// Technologie[]
    private $_batiments;	// Batiment[]
    private $_unites;           // Unite[]
	
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
    
    /*
     * GETTERS & SETTERS (pas encore secure)
     */

    public function getPseudo() {
        return $this->_pseudo;
    }

    public function getCoordonnee() {
        return $this->_coordonnee;
    }

    public function getFaction() {
        return $this->_faction;
    }

    public function getPassword() {
        return $this->_password;
    }

    public function getRessources() {
        return $this->_ressources;
    }

    public function getTechnologies() {
        return $this->_technologies;
    }

    public function getBatiments() {
        return $this->_batiments;
    }

    public function getUnites() {
        return $this->_unites;
    }

    public function setPseudo($pseudo) {
        $this->_pseudo = $pseudo;
    }

    public function setCoordonnee($coordonnee) {
        $this->_coordonnee = $coordonnee;
    }

    public function setFaction($faction) {
        $this->_faction = $faction;
    }

    public function setPassword($password) {
        $this->_password = $password;
    }

    public function setRessources($ressources) {
        $this->_ressources = $ressources;
    }

    public function setTechnologies($technologies) {
        $this->_technologies = $technologies;
    }

    public function setBatiments($batiments) {
        $this->_batiments = $batiments;
    }

    public function setUnites($unites) {
        $this->_unites = $unites;
    }

    /*
     * FONCTIONS GENERIQUES
     */
    public function toString() {
        $ressourcesString = "";

        foreach ($this->_ressources as $r){
            $ressourcesString .= $r->toString()." ";
        }

        $technologiesString = "";

        foreach($this->_technologies as $t){
            $technologiesString .= $t->toString()." ";
        }
        
        $batimentsString = "";

        foreach($this->_batiments as $b){
            $batimentsString .= $b->toString()." ";
        }
        
        $unitesString = "";

        foreach($this->_unites as $u){
            $unitesString .= $u->toString()." ";
        }
		
        return static::class."<br/> Pseudo : 
            ".$this->_pseudo."<br/> coordonnee : 
            ".$this->_coordonnee."<br/> faction : 
            ".$this->_faction."<br/> password : 
            ".$this->_password."<br/> ressouces : 
            ".$ressourcesString."<br/> technologies : 
            ".$technologiesString."<br/> batiments : 
            ".$batimentsString."<br/> unites :  
            ".$unitesString."<br/>";
    }
    
    public function addRessource($ressource) {
        if (is_a($ressource, 'Ressource')){
            if (!isset($this->_ressources)){
                $this->_ressources = array();
            }
            
            $this->_ressources[] = $ressource;
        }
    }
    
    public function addTechnologie($technologie) {
        if (is_a($technologie, 'Technologie')){
            if (!isset($this->_technologies)){
                $this->_technologies = array();
            }
            
            $this->_technologies[] = $technologie;
        }
    }
    
    public function addBatiment($batiment) {
        if (is_a($batiment, 'Batiment')){
            if (!isset($this->_batiments)){
                $this->_batiments = array();
            }
            
            $this->_batiments[] = $batiment;
        }
    }
    
    public function addUnite($unite) {
        if (is_a($unite, 'Unite')){
            if (!isset($this->_unites)){
                $this->_unites = array();
            }
            
            $this->_unites[] = $unite;
        }
    }
    
    public function updateRessource($ressource) {
        if (is_a($ressource, 'Ressource')){
            if (isset($this->_ressources)){
                foreach ($this->_ressources as $r){
                    if ($r->getNom() === $ressource->getNom()){
                        $r = $ressource;
                    }
                    break;
                }
            }
            else{
                $this->addRessource($ressource);
            }
        }
    }
    
    public function updateTechnologie($technologie) {
        if (is_a($technologie, 'Technologie')){
            if (isset($this->_technologies)){
                foreach ($this->_technologies as $t){
                    if ($t->getNom() === $technologie->getNom()){
                        $t = $technologie;
                    }
                    break;
                }
            }
            else{
                $this->addTechnologie($technologie);
            }
        }
    }
    
    public function updateBatiment($batiment) {
        if (is_a($batiment, 'Batiment')){
            if (isset($this->_batiments)){
                foreach ($this->_batiments as $b){
                    if ($b->getNom() === $batiment->getNom()){
                        $b = $batiment;
                    }
                    break;
                }
            }
            else{
                $this->addBatiment($batiment);
            }
        }
    }
    
    public function updateUnite($unite) {
        if (is_a($unite, 'Unite')){
            if (isset($this->_unites)){
                foreach ($this->_unites as $u){
                    if ($u->getNom() === $unite->getNom()){
                        $u = $unite;
                    }
                    break;
                }
            }
            else{
                $this->addUnite($unite);
            }
        }
    }
}
?>
