<?php
require_once('Includes.php');

class DAORessource {
	
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
    
    /*
     * Inserts a new Ressource into DB, and returns the object with its ID
     */
    public function create(Ressource $toInsert, $db = null) {
        $returnValue = new Ressource();
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            
            $db->query('INSERT INTO Ressource (nom, quantite) VALUES (\''.$toInsert->getNom().'\','.$toInsert->getQuantite().');');
            $id = intval($db->lastInsertId());
            
            foreach($db->query('SELECT id,nom,quantite FROM Ressource WHERE id='.$id.';') as $row){
                $returnValue->setId($row['id']);
                $returnValue->setNom($row['nom']);
                $returnValue->setQuantite($row['quantite']);
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }

    /*
     * Returns a Ressource object, based on its id
     */
    public function getRessourceById($id, $db = null){
        $returnValue = new Ressource();
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            
            $result = $db->query('SELECT id,nom,quantite FROM Ressource WHERE id='.$id.';');

            foreach($result as $row){
                $returnValue->setId($row['id']);
                $returnValue->setNom($row['nom']);
                $returnValue->setQuantite($row['quantite']);
            }
        
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }
    
    
}
?>
