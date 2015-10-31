<?php
require_once('Ressource.class.php');

class Technologie {
    private $_id;               // int
    private $_nom;		// string
    private $_niveau;		// int
    private $_cout;		// Ressource[]
	
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

    public function getNiveau(){
        return $this->_niveau;
    }

    public function setNiveau($niveau){
        $this->_niveau = $niveau;
    }

    public function getCout(){
        return $this->_cout;
    }

    public function setCout($cout){
        $this->_cout = $cout;
    }

    /*
     * FONCTIONS GENERIQUES
     */
    public function toString() {
        $coutString = "";

        foreach ($this->_cout as $c){
            $coutString .= $c->toString()." ";
        }
		
        return static::class."<br/> Nom : 
            ".$this->_nom."<br/> niveau : 
            ".$this->_niveau."<br/> cout : 
            ".$coutString."<br/>";
    }
    
    public function addRessourceCout ($ressource){
        if (is_a($ressource, 'Ressource')){
            if (!isset($this->_cout)){
                $this->_cout = array();
            }
            
            $this->_cout[] = $ressource;
        }
    }
    
    public function updateRessourceCout($ressource) {
        if (is_a($ressource, 'Ressource')){
            if (isset($this->_cout)){
                foreach ($this->_cout as $r){
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
}
?>
