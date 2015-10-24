<?php
//require_once('Batiment.class.php');
//require_once('Technologie.class.php');

class Unite {
    private $_id;		// int
    private $_affectation;	// Batiment
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

    public function getAffectation(){
        return $this->_affectation;
    }

    public function setAffectation($affectation){
        $this->_affectation = $affectation;
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

        foreach($this->_techNeeded as $t){
            $techNeededString .= $t->toString()." ";
        }
		
        return static::class."<br/> Id : 
            ".$this->_id."<br/> affectation : 
            ".$this->_affectation."<br/> niveau : 
            ".$this->_niveau."<br/> cout : 
            ".$coutString."<br/> techNeeded : 
            ".$techNeededString."<br/>";
    }
}
?>
