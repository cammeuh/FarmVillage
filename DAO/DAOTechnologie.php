<?php
require_once('Includes.php');

class DAOTechnologie {
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
    
    /*
     * Inserts a new Technologie into DB, and returns the object with its ID
     */
    public function create(Technologie $toInsert, $db = null) {
        $returnValue = null;
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $DAORessource = new DAORessource();
            $returnValue = $toInsert;
            $db->query('INSERT INTO Technologie (nom, niveau) VALUES (\''.$toInsert->getNom().'\','.$toInsert->getNiveau().');');
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
                
                $db->query('INSERT INTO TechnologieCout (idTechnologie, idRessource) VALUES ('.$id.','.$ressourceId.');');
            }

            $returnValue = $this->getTechnologieById($id);
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }

    /*
     * Returns a Technologie object, based on its id
     */
    public function getTechnologieById($id, $db = null){
        $returnValue = null;
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            
            $returnValue = new Technologie();
            $DAORessource = new DAORessource();
            
            foreach($db->query('SELECT id,nom,niveau FROM Technologie WHERE id='.$id.';') as $row){
                $returnValue->setId($row['id']);
                $returnValue->setNom($row['nom']);
                $returnValue->setNiveau($row['niveau']);
            }
            
            foreach($db->query('SELECT idRessource FROM TechnologieCout WHERE idTechnologie='.$id.';') as $row){
                $returnValue->addRessourceCout($DAORessource->getRessourceById($row['idRessource']));
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }
    
    /*
     * Updates a Technologie object, based on its id
     */
    public function updateRessource(Technologie $toUpdate, $db = null){
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $result = $db->query('UPDATE Technologie SET nom=\''.$toUpdate->getNom().'\',niveau='.$toUpdate->getNiveau().' WHERE id='.$toUpdate->getId().';');
            $DAORessource = new DAORessource();
            
            foreach($toUpdate->getCout() as $ressourceToUpdate){
                $DAORessource->updateRessource($ressourceToUpdate);
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }
    
    
}
?>
