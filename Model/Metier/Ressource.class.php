<?php

class Ressource {
    private $_id;               // int
    private $_nom;		// string
    private $_quantite;		// int
	
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
	
    /*
     * GETTERS & SETTERS (pas encore secure)
     */
    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
    }

    public function getNom(){
        return $this->_nom;
    }

    public function setNom($nom){
        $this->_nom = $nom;
    }

    public function getQuantite(){
        return $this->_quantite;
    }

    public function setQuantite($quantite){
        $this->_quantite = $quantite;
    }
    
    /*
     * FONCTIONS GENERIQUES
     */
    public function toString() {
        return static::class."<br/> Id :
            ".$this->_id."<br/> Nom : 
            ".$this->_nom."<br/> Quantite : 
            ".$this->_quantite."<br/>";
    }
    
    
}
?>
