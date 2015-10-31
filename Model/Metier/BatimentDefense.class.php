<?php
require_once('Batiment.class.php');

class BatimentDefense extends Batiment {
    private $_attaque;		// int
    private $_defense;		// int
	
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
	
    /*
     * GETTERS & SETTERS (pas encore secure)
     */

    public function getAttaque(){
        return $this->_attaque;
    }

    public function setAttaque($attaque){
        $this->_attaque = $attaque;
    }

    public function getDefense(){
        return $this->_defense;
    }

    public function setDefense($defense){
        $this->_defense = $defense;
    }
    
    /*
     * FONCTIONS GENERIQUES
     */
    public function toString(){
        $coutString = "";

        foreach ($this->_cout as $c){
            $coutString .= $c->toString()." ";
        }

        $techNeededString = "";

        foreach($this->_techNeeded as $t){
            $techNeededString .= $t->toString()." ";
        }
		
        return static::class."<br/> Id : 
            ".$this->_id."<br/> niveau : 
            ".$this->_niveau."<br/> cout : 
            ".$coutString."<br/> techNeeded : 
            ".$techNeededString."<br/> attaque :
            ".$this->_attaque."<br/> defense :    
            ".$this->_defense."<br/>";
    }
}
?>
