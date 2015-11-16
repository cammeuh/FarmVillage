<?php
require_once('Includes.php');

class DAOTechnologie {
    private $_id;               // int
    private $_nom;		// string
    private $_niveau;		// int
    private $_cout;		// Ressource[]
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
    
    /*
     * Inserts a new Technologie into DB, and returns the object with its ID
     */
    public function create(Technologie $toInsert, $db = null) {
        $returnValue = new Technologie();
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $DAORessource = new DAORessource();
            $db->query('INSERT INTO Technologie (nom, niveau) VALUES (\''.$toInsert->getNom().'\','.$toInsert->getNiveau().');');
            $id = intval($db->lastInsertId());
            $ressourcesId = array();
            
            foreach ($toInsert->getCout() as $cout){
                $ressourceId = 0;
                
                if(is_null($cout->getId())){
                    $ressource = $DAORessource->create($cout);
                    $ressourceId = $ressource->getId();
                    $ressourcesId[] = $ressourceId;
                }
                else{
                    $ressourceId = $cout->getId();
                    $ressourcesId[] = $ressourceId;
                }
                
                $db->query('INSERT INTO TechnologieCout (idTechnologie, idRessource) VALUES ('.$id.','.$ressourceId.');');
            }

            foreach($db->query('SELECT id,nom,niveau FROM Technologie WHERE id='.$id.';') as $row){
                $returnValue->setId($row['id']);
                $returnValue->setNom($row['nom']);
                $returnValue->setNiveau($row['niveau']);
            }

            foreach($ressourcesId as $resId){
                $returnValue->addRessourceCout($DAORessource->getRessourceById($resId));
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }

    /*
     * Returns a Technologie object, based on its id
     */
    public function getRessourceById($id, $db = null){
        $returnValue = new Ressource();
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            
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
    
    
}
?>
