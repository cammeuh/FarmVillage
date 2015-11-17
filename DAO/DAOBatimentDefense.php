<?php
require_once('Includes.php');

class DAOBatimentDefense {
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
    
    /*
     * Inserts a new BatimentDefense into DB, and returns the object with its ID
     */
    public function create(BatimentDefense $toInsert, $db = null) {
        $returnValue = null;
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $DAORessource = new DAORessource();
            $DAOTechnologie = new DAOTechnologie();
            $returnValue = $toInsert;
            $db->query('INSERT INTO BatimentDefense (niveau,nom,attaque,defense) VALUES ('.$toInsert->getNiveau().',\''.$toInsert->getNom().'\','.$toInsert->getAttaque().','.$toInsert->getDefense().');');
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
                
                $db->query('INSERT INTO BatimentDefenseCout (idBatiment, idRessource) VALUES ('.$id.','.$ressourceId.');');
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
                
                $db->query('INSERT INTO BatimentDefenseTechnologie (idBatiment, idTechnologie) VALUES ('.$id.','.$techNeededId.');');
            }

            $returnValue = $this->getBatimentDefenseById($id, $db);
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }

    /*
     * Returns a Technologie object, based on its id
     */
    public function getBatimentDefenseById($id, $db = null){
        $returnValue = null;
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            
            $returnValue = new BatimentDefense();
            $DAORessource = new DAORessource();
            $DAOTechnologie = new DAOTechnologie();
            
            foreach($db->query('SELECT id,niveau,nom,attaque,defense FROM BatimentDefense WHERE id='.$id.';') as $row){
                $returnValue->setId($row['id']);
                $returnValue->setNiveau($row['niveau']);
                $returnValue->setAttaque($row['attaque']);
                $returnValue->setDefense($row['defense']);
                $returnValue->setNom($row[['nom']]);
            }
            
            foreach($db->query('SELECT idRessource FROM BatimentDefenseCout WHERE idBatiment='.$id.';') as $row){
                $returnValue->addRessourceCout($DAORessource->getRessourceById($row['idRessource']));
            }
            
            foreach($db->query('SELECT idTechnologie FROM BatimentDefenseTechnologie WHERE idBatiment='.$id.';') as $row){
                $returnValue->addTechNeeded($DAOTechnologie->getTechnologieById($row['idTechnologie']));
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }
    
    /*
     * Updates a BatimentDefense object, based on its id
     */
    public function updateBatimentDefense(BatimentDefense $toUpdate, $db = null){
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $result = $db->query('UPDATE BatimentDefense SET nom=\''.$toUpdate->getNom().'\',niveau='.$toUpdate->getNiveau().' WHERE id='.$toUpdate->getId().';');
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }
    
    /*
     * Deletes a BatimentDefense and its links, based on its id
     */
    public function deleteBatimentDefenseById($toDelete, $db=null){
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $DAORessource = new DAORessource();
            $DAOTechnologie = new DAOTechnologie();
            
            foreach($db->query('SELECT idTechnologie FROM BatimentDefenseTechnologie WHERE idBatiment='.$toDelete.';') as $row){
                $idTechnologie = $row['idTechnologie'];
                $db->query('DELETE FROM BatimentDefenseTechnologie WHERE idTechnologie='.$idTechnologie.';');
                $DAOTechnologie->deleteTechnologieById($idTechnologie, $db);
            }
            
            foreach($db->query('SELECT idRessource FROM BatimentDefenseCout WHERE idBatiment='.$toDelete.';') as $row){
                $idRessource = $row['idRessource'];
                $db->query('DELETE FROM BatimentDefenseCout WHERE idRessource='.$idRessource.';');
                $DAORessource->deleteRessourceById($idRessource, $db);
            }
            
            $db->query('DELETE FROM BatimentDefense WHERE id='.$toDelete.';');
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }
}
?>
