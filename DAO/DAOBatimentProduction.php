<?php
require_once('Includes.php');

class DAOBatimentProduction {
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
    private $_productionType;	// Ressource
    private $_productionTemps;	// int
    private $_prixReparation;	// Ressource[]
    private $_actif;            // bool
    protected $_id;		// int
    protected $_niveau;		// int
    protected $_cout;		// Ressource[]
    protected $_techNeeded;	// Technologie[]
    /*
     * Inserts a new BatimentProduction into DB, and returns the object with its ID
     */
    public function create(BatimentProduction $toInsert, $db = null) {
        $returnValue = null;
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $DAORessource = new DAORessource();
            $DAOTechnologie = new DAOTechnologie();
            $returnValue = $toInsert;
            $db->query('INSERT INTO BatimentProduction (niveau,productionTemps,actif,idRessource) VALUES ('.$toInsert->getNiveau().','.time().','.$toInsert->isActif().','.$toInsert->getProductionType()->getId().');');
            $id = intval($db->lastInsertId());
            $returnValue->setId($id);
            
            foreach ($toInsert->getCout() as $cout){
                $ressourceId = 0;
                
                if(is_null($cout->getId())){
                    $ressource = $DAORessource->create($cout);
                    $ressourceId = $ressource->getId();
                }
                else{
                    $ressourceId = $cout->getId();
                }
                
                $db->query('INSERT INTO BatimentProductionCout (idBatiment, idRessource) VALUES ('.$id.','.$ressourceId.');');
            }
            
            foreach ($toInsert->getTechNeeded() as $tech){
                $techNeededId = 0;
                
                if(is_null($tech->getId())){
                    $technologie = $DAOTechnologie->create($tech);
                    $techNeededId = $technologie->getId();
                }
                else{
                    $techNeededId = $tech->getId();
                }
                
                $db->query('INSERT INTO BatimentProductionTechnologie (idBatiment, idTechnologie) VALUES ('.$id.','.$techNeededId.');');
            }
            
            foreach ($toInsert->getPrixReparation() as $cout){
                $ressourceId = 0;
                
                if(is_null($cout->getId())){
                    $ressource = $DAORessource->create($cout);
                    $ressourceId = $ressource->getId();
                }
                else{
                    $ressourceId = $cout->getId();
                }
                
                $db->query('INSERT INTO BatimentProductionPrixReparation (idBatiment, idRessource) VALUES ('.$id.','.$ressourceId.');');
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }

    /*
     * Returns a Technologie object, based on its id
     */
    public function getBatimentProductionById($id, $db = null){
        $returnValue = null;
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            
            $returnValue = new BatimentProduction();
            $DAORessource = new DAORessource();
            $DAOTechnologie = new DAOTechnologie();
            
            foreach($db->query('SELECT id,niveau,productionTemps,actif,idRessource FROM BatimentProduction WHERE id='.$id.';') as $row){
                $returnValue->setId($row['id']);
                $returnValue->setNiveau($row['niveau']);
                $returnValue->setProductionTemps($row['productionTemps']);
                $returnValue->setActif($row['actif']);
                $returnValue->setPrixReparation($DAORessource->getRessourceById($row['idRessource']));
            }
            
            foreach($db->query('SELECT idRessource FROM BatimentProductionCout WHERE idBatiment='.$id.';') as $row){
                $returnValue->addRessourceCout($DAORessource->getRessourceById($row['idRessource']));
            }
            
            foreach($db->query('SELECT idTechnologie FROM BatimentProductionTechnologie WHERE idBatiment='.$id.';') as $row){
                $returnValue->addTechNeeded($DAOTechnologie->getTechnologieById($row['idTechnologie']));
            }
            
            foreach($db->query('SELECT idRessource FROM BatimentProductionPrixReparation WHERE idBatiment='.$id.';') as $row){
                $returnValue->addPrixReparation($DAORessource->getRessourceById($row['idRessource']));
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }
    
    /*
     * Updates a BatimentProduction object, based on its id
     */
    public function updateBatimentProduction(BatimentProduction $toUpdate, $db = null){
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $result = $db->query('UPDATE BatimentProduction SET nom=\''.$toUpdate->getNom().'\',niveau='.$toUpdate->getNiveau().' WHERE id='.$toUpdate->getId().';');
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }
}
?>
