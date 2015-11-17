<?php
require_once('Ressource.class.php');
require_once('Technologie.class.php');

abstract class Batiment {
    protected $_id;		// int
    protected $_niveau;		// int
    protected $_nom;            // string
    protected $_cout;		// Ressource[]
    protected $_techNeeded;	// Technologie[]
	
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

    public function getNiveau(){
        return $this->_niveau;
    }

    public function setNiveau($niveau){
        $this->_niveau = $niveau;
    }
    
    function getNom() {
        return $this->_nom;
    }

    function setNom($nom) {
        $this->_nom = $nom;
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
    abstract protected function toString();
    
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
