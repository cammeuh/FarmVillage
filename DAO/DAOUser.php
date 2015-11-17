<?php
require_once('Includes.php');

class DAOUser {
    /*
     * CONSTRUCTEURS
     */
    public function __construct() {
        
    }
    
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
                
                if(is_null($unit->getId())){
                    $unite = $DAOUnite->create($unit);
                    $uniteId = $unite->getId();
                }
                else{
                    $uniteId = $unit->getId();
                }
                
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
            $DAOUnite = new DAOUnite();
            
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
     * Updates a User object and returns it updated (ids might change in the process)
     */
    public function updateUser(User $toUpdate, $db = null){
        $returnValue = $toUpdate;
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $this->deleteUser($toUpdate, $db);
            
            foreach($toUpdate->getRessources() as $res){
                $res->setId(null);
            }
            
            foreach($toUpdate->getTechnologies() as $tech){
                $tech->setId(null);
                
                foreach($tech->getCout() as $res){
                    $res->setId(null);
                }
            }
            
            foreach($toUpdate->getBatimentsDefense() as $batDef){
                $batDef->setId(null);
                
                foreach($tech->getCout() as $res){
                    $res->setId(null);
                }
                
                foreach($batDef->getTechNeeded() as $tech){
                    $tech->setId(null);
                    
                    foreach($tech->getCout() as $res){
                        $res->setId(null);
                    }
                }
            }
            
            foreach($toUpdate->getBatimentsProduction() as $batProd){
                $batProd->setId(null);
                
                foreach($tech->getCout() as $res){
                    $res->setId(null);
                }
                
                foreach($batProd->getTechNeeded() as $tech){
                    $tech->setId(null);
                    
                    foreach($tech->getCout() as $res){
                        $res->setId(null);
                    }
                }
                
                $batProd->getProductionType()->setId(null);
                
                foreach($batProd->getPrixReparation() as $res){
                    $res->setId(null);
                }
            }
            
            foreach($toUpdate->getUnites() as $unit){
                $unit->setId(null);
                
                if($unit->getAffectationProd() !== 0){
                    foreach($unit->getAffectationProd() as $batProd){
                        $batProd->setId(null);

                        foreach($tech->getCout() as $res){
                            $res->setId(null);
                        }

                        foreach($batProd->getTechNeeded() as $tech){
                            $tech->setId(null);

                            foreach($tech->getCout() as $res){
                                $res->setId(null);
                            }
                        }

                        $batProd->getProductionType()->setId(null);

                        foreach($batProd->getrixReparation() as $res){
                            $res->setId(null);
                        }
                    }
                }
                
                
                if($unit->getAffectationDef() !== 0){
                    foreach($unit->getAffectationDef() as $batDef){
                        $batDef->setId(null);

                        foreach($tech->getCout() as $res){
                            $res->setId(null);
                        }

                        foreach($batDef->getTechNeeded() as $tech){
                            $tech->setId(null);

                            foreach($tech->getCout() as $res){
                                $res->setId(null);
                            }
                        }
                    }
                }
                
                foreach($unit->getTechNeeded() as $tech){
                    $tech->setId(null);

                    foreach($tech->getCout() as $res){
                        $res->setId(null);
                    }
                }
                
                foreach($unit->getCout() as $res){
                    $res->setId(null);
                }
            }
            
            $returnValue = $this->create($toUpdate, $db);
            
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
        return $returnValue;
    }
    
    /*
     * Deletes a User and its links, based on its id
     */
    public function deleteUser(User $toDelete, $db=null){
        try{
            if(!isset($db)) $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            $DAORessource = new DAORessource();
            $DAOTechnologie = new DAOTechnologie();
            $DAOBatimentDefense = new DAOBatimentDefense();
            $DAOBatimentProduction = new DAOBatimentProduction();
            $DAOUnite = new DAOUnite();
            $pseudo = $toDelete->getPseudo();
            
            foreach($db->query('SELECT idUnite FROM UserUnites WHERE pseudoUser=\''.$pseudo.'\';') as $row){
                $idUnite = $row['idUnite'];
                $db->query('DELETE FROM UserUnites WHERE idUnite='.$idUnite.';');
                $DAOUnite->deleteUniteById($idUnite, $db);
            }
            
            foreach($db->query('SELECT idBatimentProduction FROM UserBatimentsProduction WHERE pseudoUser=\''.$pseudo.'\';') as $row){
                $idBatimentProduction = $row['idBatimentProduction'];
                $db->query('DELETE FROM UserBatimentsProduction WHERE idBatimentProduction='.$idBatimentProduction.';');
                $DAOBatimentProduction->deleteBatimentProductionById($idBatimentProduction, $db);
            }
            
            foreach($db->query('SELECT idBatimentDefense FROM UserBatimentsDefense WHERE pseudoUser=\''.$pseudo.'\';') as $row){
                $idBatimentDefense = $row['idBatimentDefense'];
                $db->query('DELETE FROM UserBatimentsDefense WHERE idBatimentDefense='.$idBatimentDefense.';');
                $DAOBatimentDefense->deleteBatimentDefenseById($idBatimentDefense, $db);
            }
            
            foreach($db->query('SELECT idTechnologie FROM UserTechnologies WHERE pseudoUser=\''.$pseudo.'\';') as $row){
                $idTechnologie = $row['idTechnologie'];
                $db->query('DELETE FROM UserTechnologies WHERE idTechnologie='.$idTechnologie.';');
                $DAOTechnologie->deleteTechnologieById($idTechnologie, $db);
            }
            
            foreach($db->query('SELECT idRessource FROM UserRessources WHERE pseudoUser=\''.$pseudo.'\';') as $row){
                $idRessource = $row['idRessource'];
                $db->query('DELETE FROM UserRessources WHERE idRessource='.$idRessource.';');
                $DAORessource->deleteRessourceById($idRessource, $db);
            }
            
            $db->query('DELETE FROM User WHERE pseudo=\''.$toDelete->getPseudo().'\';');
        } catch (Exception $ex) {
            echo($ex->getMessage());
        }
    }
}
?>
