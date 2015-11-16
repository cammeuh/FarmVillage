<?php
require_once('Includes.php');

class DAOUnite {
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
    
    /*
     * Inserts a new Unite into DB, and returns the object with its ID
     */
    public function create(Unite $toInsert, $db = null) {
        $returnValue = null;
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $DAORessource = new DAORessource();
            $DAOTechnologie = new DAOTechnologie();
            $returnValue = $toInsert;
            $idBatDef = $toInsert->getAffectationDefense() === 0 ? 0 : $toInsert->getAffectationDefense()->getId();
            $idBatProd = $toInsert->getAffectationProduction() === 0 ? 0 : $toInsert->getAffectationProduction()->getId();
            
            if ($idBatDef == 0 && $idBatProd == 0){
                $db->query('INSERT INTO Unite (niveau) VALUES ('.$toInsert->getNiveau().');');
            }
            else if ($idBatProd == 0 && $idBatDef != 0){
                $db->query('INSERT INTO Unite (niveau,idBatimentDefense) VALUES ('.$toInsert->getNiveau().','.$idBatDef.');');
            }
            else if ($idBatProd != 0 && $idBatDef == 0){
                $db->query('INSERT INTO Unite (niveau,idBatimentProduction) VALUES ('.$toInsert->getNiveau().','.$idBatProd.');');
            }
            
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
                
                $db->query('INSERT INTO UniteCout (idUnite, idRessource) VALUES ('.$id.','.$ressourceId.');');
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
                
                $db->query('INSERT INTO UniteTechnologie (idUnite, idTechnologie) VALUES ('.$id.','.$techNeededId.');');
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        
        return $returnValue;
    }

    /*
     * Returns a Technologie object, based on its id
     */
    public function getUniteById($id, $db = null){
        $returnValue = null;
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            
            $returnValue = new Unite();
            $DAORessource = new DAORessource();
            $DAOTechnologie = new DAOTechnologie();
            $DAOBatimentDefense = new DAOBatimentDefense();
            $DAOBatimentProduction = new DAOBatimentProduction();
            
            foreach($db->query('SELECT id,niveau,idBatimentDefense,idBatimentProduction FROM Unite WHERE id='.$id.';') as $row){
                $returnValue->setId($row['id']);
                $returnValue->setNiveau($row['niveau']);
                $row['idBatimentDefense'] == 0 ? $returnValue->setAffectationDefense(0) : $returnValue->setAffectationDefense($DAOBatimentDefense->getBatimentDefenseById($row['idBatimentDefense']));
                $row['idBatimentProduction'] == 0 ? $returnValue->setAffectationProduction(0) : $returnValue->setAffectationProduction($DAOBatimentProduction->getBatimentProductionById($row['idBatimentProduction']));
            }
            
            foreach($db->query('SELECT idRessource FROM UniteCout WHERE idUnite='.$id.';') as $row){
                $returnValue->addRessourceCout($DAORessource->getRessourceById($row['idRessource']));
            }
            
            foreach($db->query('SELECT idTechnologie FROM UniteTechnologie WHERE idUnite='.$id.';') as $row){
                $returnValue->addTechNeeded($DAOTechnologie->getTechnologieById($row['idTechnologie']));
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }
    
    /*
     * Updates a Unite object, based on its id
     */
    public function updateUnite(Unite $toUpdate, $db = null){
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $idBatDef = $toUpdate->getAffectationDefense() == 0 ? 0 : $toUpdate->getAffectationDefense()->getId();
            $idBatProd = $toUpdate->getAffectationProduction() == 0 ? 0 : $toUpdate->getAffectationProduction()->getId();
            $result = $db->query('UPDATE Unite SET niveau='.$toUpdate->getNiveau().',idBatimentDefense='.$idBatDef.',idBatimentProduction='.$idBatProd.' WHERE id='.$toUpdate->getId().';');
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }
}
?>
