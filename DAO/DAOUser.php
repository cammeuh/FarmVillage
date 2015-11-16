<?php
require_once('Includes.php');

class DAOUser {
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
    private $_pseudo;		// string
    private $_coordonnee;	// string
    private $_faction;		// string
    private $_password;		// string
    /*
     * Inserts a new User into DB, and returns the object with its ID
     */
    public function create(User $toInsert, $db = null) {
        $returnValue = null;
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $DAORessource = new DAORessource();
            $DAOTechnologie = new DAOTechnologie();
            $DAOBatimentDefense = new DAOBatimentDefense();
            $DAOBatimentProduction = new DAOBatimentProduction();
            $DAOUnite = new DAOUnite();
            $returnValue = $toInsert;
            $db->query('INSERT INTO User (pseudo,coordonnee,faction,password) VALUES (\''.$toInsert->getPseudo().'\',\''.$toInsert->getCoordonnee().'\',\''.$toInsert->getFaction().'\',\''.$toInsert->getPassword().'\');');

            foreach ($toInsert->getRessources() as $res){
                $ressourceId = 0;
                
                if(is_null($res->getId())){
                    $ressource = $DAORessource->create($res);
                    $ressourceId = $ressource->getId();
                }
                else{
                    $ressourceId = $res->getId();
                }
                
                $db->query('INSERT INTO UserRessources (pseudoUser, idRessource) VALUES (\''.$toInsert->getPseudo().'\','.$ressourceId.');');
            }
            
            foreach ($toInsert->getTechnologies() as $tech){
                $techId = 0;
                
                if(is_null($tech->getId())){
                    $technologie = $DAOTechnologie->create($tech);
                    $techId = $technologie->getId();
                }
                else{
                    $techId = $tech->getId();
                }
                
                $db->query('INSERT INTO UserTechnologies (pseudoUser, idTechnologie) VALUES (\''.$toInsert->getPseudo().'\','.$techId.');');
            }
            
            if (!is_null($toInsert->getBatimentsDefense())){
                foreach ($toInsert->getBatimentsDefense() as $bat){
                    $batimentId = 0;

                    if(is_null($bat->getId())){
                        $batiment = $DAOBatimentDefense->create($bat);
                        $batimentId = $batiment->getId();
                    }
                    else{
                        $batimentId = $bat->getId();
                    }

                    $db->query('INSERT INTO UserBatimentsDefense (pseudoUser, idBatimentDefense) VALUES (\''.$toInsert->getPseudo().'\','.$batimentId.');');
                }
            }
            
            if (!is_null($toInsert->getBatimentsProduction())){
                foreach ($toInsert->getBatimentsProduction() as $bat){
                    $batimentId = 0;
                    
                    if(is_null($bat->getId())){
                        $batiment = $DAOBatimentProduction->create($bat);
                        $batimentId = $batiment->getId();
                    }
                    else{
                        $batimentId = $bat->getId();
                    }
                    
                    $db->query('INSERT INTO UserBatimentsProduction (pseudoUser, idBatimentProduction) VALUES (\''.$toInsert->getPseudo().'\','.$batimentId.');');
                }
            }
            
            foreach ($toInsert->getUnites() as $unit){
                $uniteId = 0;
                var_dump($unit->getId());
                if(is_null($unit->getId())){
                    $unite = $DAOUnite->create($unit);
                    $uniteId = $unite->getId();
                }
                else{
                    $uniteId = $unit->getId();
                }
                var_dump('INSERT INTO UserUnites (pseudoUser, idUnite) VALUES (\''.$toInsert->getPseudo().'\','.$uniteId.');');
                $db->query('INSERT INTO UserUnites (pseudoUser, idUnite) VALUES (\''.$toInsert->getPseudo().'\','.$uniteId.');');
            }
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }

    /*
     * Returns a User object, based on its id
     */
    public function getUserByPseudo($pseudo, $db = null){
        $returnValue = null;
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            
            $returnValue = new User();
            $DAORessource = new DAORessource();
            $DAOTechnologie = new DAOTechnologie();
            $DAOBatimentDefense = new DAOBatimentDefense();
            $DAOBatimentProduction = new DAOBatimentProduction();
            
            foreach($db->query('SELECT pseudo,coordonnee,faction,password FROM User WHERE pseudo=\''.$pseudo.'\';') as $row){
                $returnValue->setPseudo($row['pseudo']);
                $returnValue->setCoordonnee($row['coordonnee']);
                $returnValue->setFaction($row['faction']);
                $returnValue->setPassword($row['password']);
            }
            
            foreach($db->query('SELECT idRessource FROM UserRessources WHERE pseudoUser=\''.$pseudo.'\';') as $row){
                $returnValue->addRessource($DAORessource->getRessourceById($row['idRessource']));
            }
            
            foreach($db->query('SELECT idTechnologie FROM UserTechnologies WHERE pseudoUser=\''.$pseudo.'\';') as $row){
                $returnValue->addTechnologie($DAOTechnologie->getTechnologieById($row['idTechnologie']));
            }
            
            foreach($db->query('SELECT idUnite FROM UserUnites WHERE pseudoUser=\''.$pseudo.'\';') as $row){
                $returnValue->addUnite($DAOUnite->getUniteById($row['idUnite']));
            }
            
            foreach($db->query('SELECT idBatimentDefense FROM UserBatimentsDefense WHERE pseudoUser=\''.$pseudo.'\';') as $row){
                $returnValue->addBatimentDefense($DAOBatimentDefense->getBatimentDefenseById($row['idBatimentDefense']));
            }
            
            foreach($db->query('SELECT idBatimentProduction FROM UserBatimentsProduction WHERE pseudoUser=\''.$pseudo.'\';') as $row){
                $returnValue->addBatimentProduction($DAOBatimentProduction->getBatimentProductionById($row['idBatimentProduction']));
            }
            
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }
    
    /*
     * Updates a User object, based on its id
     */
    public function updateUser(User $toUpdate, $db = null){
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $result = $db->query('UPDATE User SET coordonnee=\''.$toUpdate->getCoordonnee().'\',faction=\''.$toUpdate->getFaction().'\',password=\''.$toUpdate->getPassword().'\' WHERE pseudo=\''.$toUpdate->getPseudo().'\';');
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }
}
?>
