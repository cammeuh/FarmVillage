<?php
require_once('Batiment.class.php');

class BatimentProduction extends Batiment {
    private $_productionType;	// Ressource
    private $_productionTemps;	// int
    private $_prixReparation;	// Ressource[]
    private $_actif;            // bool
	
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
	
    /*
     * GETTERS & SETTERS (pas encore secure)
     */

    public function getProductionType(){
        return $this->_productionType;
    }

    public function setProductionType($productionType){
        $this->_productionType = $productionType;
    }
    
    public function getProductionTemps(){
        return $this->_productionTemps;
    }

    public function setProductionTemps($productionTemps){
        $this->_productionTemps = $productionTemps;
    }

    public function getPrixReparation(){
        return $this->_prixReparation;
    }

    public function setPrixReparation($prixReparation){
        $this->_prixReparation = $prixReparation;
    }
    
    public function isActif(){
        return $this->_actif;
    }
    
    public function setActif($actif){
        $this->_actif = $actif;
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
        
        $prixReparationString = "";

        foreach($this->_prixReparation as $p){
            $prixReparationString .= $p->toString()." ";
        }
		
        return static::class."<br/> Id : 
            ".$this->_id."<br/> niveau : 
            ".$this->_niveau."<br/> cout : 
            ".$coutString."<br/> techNeeded : 
            ".$techNeededString."<br/> productionType :
            ".$this->_productionType."<br/> productionTemps :  
            ".$this->_productionTemps."<br/> coutReparation : 
            ".$prixReparationString."<br/> isActif : 
            ".$this->_actif."<br/>";
    }
    
    public function addPrixReparation ($ressource){
        if (is_a($ressource, 'Ressource')){
            if (!isset($this->_prixReparation)){
                $this->_prixReparation = array();
            }
            
            $this->_prixReparation[] = $ressource;
        }
    }
    
    public function updatePrixReparation($ressource) {
        if (is_a($ressource, 'Ressource')){
            if (isset($this->_prixReparation)){
                foreach ($this->_prixReparation as $r){
                    if ($r->getNom() === $ressource->getNom()){
                        $r = $ressource;
                    }
                    break;
                }
            }
            else{
                $this->addPrixReparation($ressource);
            }
        }
    }
}
?>
