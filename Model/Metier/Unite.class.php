<?php
require_once('Batiment.class.php');
require_once('Ressource.class.php');
require_once('Technologie.class.php');

class Unite {
    private $_id;		// int
    private $_affectationDef;	// BatimentDefense
    private $_affectationProd;	// BatimentProduction
    private $_niveau;		// int
    private $_cout;		// Ressource[]
    private $_techNeeded;	// Technologie[]
	
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
	
    /*
     * GETTERS & SETTERS (pas encore secure)
     */

    public function getId(){
        return $this->_id;
    }

    public function setId($id){
        $this->_id = $id;
    }

    public function getAffectationDefense(){
        return $this->_affectationDef;
    }

    public function setAffectationDefense($affectationDef){
        $this->_affectationDef = $affectationDef;
    }
    
    public function getAffectationProduction(){
        return $this->_affectationProd;
    }

    public function setAffectationProduction($affectationProd){
        $this->_affectationProd = $affectationProd;
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

    public function getTechNeeded(){
        return $this->_techNeeded;
    }

    public function setTechNeeded($techNeeded){
        $this->_techNeeded = $techNeeded;
    }

    /*
     * FONCTIONS GENERIQUES
     */
    public function toString() {
        $coutString = "";

        foreach ($this->_cout as $c){
            $coutString .= $c->toString()." ";
        }

        $techNeededString = "";

        foreach ($this->_techNeeded as $tn){
            $techNeededString .= $tn->toString()." ";
        }
		
        return static::class."<br/> Id : 
            ".$this->_id."<br/> affectation : 
            ".$this->_affectationDefense->toString()."<br/> niveau : 
            ".$this->_niveau."<br/> cout : 
            ".$coutString."<br/> techNeeded : 
            ".$techNeededString."<br/>";
    }
    
    public function addRessourceCout ($ressource){
        if (is_a($ressource, 'Ressource')){
            if (!isset($this->_cout)){
                $this->_cout = array();
            }
            
            $this->_cout[] = $ressource;
        }
    }
    
    public function addTechNeeded ($technologie){
        if (is_a($technologie, 'Technologie')){
            if (!isset($this->_techNeeded)){
                $this->_techNeeded = array();
            }
            
            $this->_techNeeded[] = $technologie;
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
                $this->addRessourceCout($ressource);
            }
        }
    }
    
    public function updateTechNeeded($technologie) {
        if (is_a($technologie, 'Technologie')){
            if (isset($this->_techNeeded)){
                foreach ($this->_techNeeded as $t){
                    if ($t->getNom() === $technologie->getNom()){
                        $t = $technologie;
                    }
                    break;
                }
            }
            else{
                $this->addTechNeeded($technologie);
            }
        }
    }
}
?>
