<?php
    require_once('Includes.php');
    
    class IndexController{
        
        
        public function ___construct(){
            
        }
        /*
         * FONCTIONS DE TEST
         */
        public function createTestUnit() {
            $user = new User();
            $user->setPseudo("cammeuh");
            $user->setPassword("vodka");
            $user->setFaction("Alliance");
            $user->setCoordonnee("(1;1)");
            
            $ressourceUn = $this->createRessource(RESSOURCEUN, 1000);
            $ressourceDeux = $this->createRessource(RESSOURCEDEUX, 2000);
            $ressourceTrois = $this->createRessource(RESSOURCETROIS, 3000);
            
            $user->addRessource($ressourceUn);
            $user->addRessource($ressourceDeux);
            $user->addRessource($ressourceTrois);
            
            $ressourceUn = $this->createRessource(RESSOURCEUN, 4000);
            $ressourceDeux = $this->createRessource(RESSOURCEDEUX, 5000);
            $ressourceTrois = $this->createRessource(RESSOURCETROIS, 6000);
            
            $technologieUn = $this->createTechnologie(TECHNOLOGIEUN, $ressourceUn);
            $technologieDeux = $this->createTechnologie(TECHNOLOGIEDEUX, $ressourceDeux, 2);
            $technologieTrois = $this->createTechnologie(TECHNOLOGIETROIS, $ressourceTrois, 5);
            
            $user->addTechnologie($technologieUn);
            $user->addTechnologie($technologieDeux);
            $user->addTechnologie($technologieTrois);
            
            $batimentDefense = new BatimentDefense();
            $batimentDefense->setniveau(0);
            $batimentDefense->addRessourceCout($ressourceUn);
            $batimentDefense->addTechNeeded($technologieUn);
            $batimentDefense->setAttaque(100);
            $batimentDefense->setDefense(1000);
            
            $ressourceUn = $this->createRessource(RESSOURCEUN, 7000);
            $ressourceDeux = $this->createRessource(RESSOURCEDEUX, 8000);
            $ressourceTrois = $this->createRessource(RESSOURCETROIS, 9000);
            $technologieUn = $this->createTechnologie(TECHNOLOGIEUN, $ressourceUn);
            
            $batimentProduction = new BatimentProduction();
            $batimentProduction->setniveau(0);
            $batimentProduction->addRessourceCout($ressourceUn);
            $batimentProduction->addTechNeeded($technologieUn);
            $batimentProduction->setProductionType($ressourceDeux);
            $batimentProduction->addPrixReparation($ressourceTrois);
            $batimentProduction->setActif(true);
            
            $user->addBatimentDefense($batimentDefense);
            $user->addBatimentProduction($batimentProduction);
            
            $ressourceUn = $this->createRessource(RESSOURCEUN, 10000);
            $ressourceDeux = $this->createRessource(RESSOURCEDEUX, 8000);
            $technologieUn = $this->createTechnologie(TECHNOLOGIEUN, $ressourceDeux);
            
            $unite = new Unite();
            $unite->setAffectationProduction($batimentProduction);
            $unite->setAffectationDefense(0);
            $unite->setNiveau(0);
            $unite->addRessourceCout($ressourceUn);
            $unite->addTechNeeded($technologieUn);
            $unite->setType(UNITEUN);
            
            $user->addUnite($unite);
            
            $DAOUser = new DAOUser();
            
            $userDone = $DAOUser->create($user);
            
            //var_dump($userDone);
            
            $ressourceUn = $this->createRessource(RESSOURCEUN, 10000);
            $ressourceDeux = $this->createRessource(RESSOURCEDEUX, 8000);
            $technologieUn = $this->createTechnologie(TECHNOLOGIEUN, $ressourceDeux);
            
            $unite = new Unite();
            $unite->setAffectationProduction($batimentProduction);
            $unite->setAffectationDefense(0);
            $unite->setNiveau(0);
            $unite->addRessourceCout($ressourceUn);
            $unite->addTechNeeded($technologieUn);
            $unite->setType(UNITEUN);
            
            $userDone->addUnite($unite);
            
            $userDone = $DAOUser->updateUser($userDone);
            
            //var_dump($userDone);
            
            //$DAOUser->deleteUser($userDone);
        }
        
        public function createFirstUser(){
            $globalController = new GlobalController();
            $user = $globalController->createNewUser('cammeuh', 'vodka', 'Alliance', '(2;2)');
            echo($globalController->displayUser($user));
            $DAOUser = new DAOUser();
            $userDone = $DAOUser->create($user);
            echo($globalController->displayUser($userDone));
        }
        /*
         * 
         */
        public function initDB(){
            $db = null;
            $dbh = new PDO('mysql:host=localhost', 'nico', 'nico');
            $dbh->exec("DROP DATABASE `FarmVillage`;");
            try{
                $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
            } catch (Exception $ex) {
                $dbh = new PDO('mysql:host=localhost', 'nico', 'nico');
                $dbh->exec("CREATE DATABASE `FarmVillage`;");
                $db = new PDO('mysql:host=localhost;dbname=FarmVillage;charset=utf8', 'nico', 'nico');
                
                $db->query('CREATE TABLE Ressource (id int(9) NOT NULL auto_increment, nom varchar(255) NOT NULL DEFAULT \'error\', quantite int NOT NULL DEFAULT 0, PRIMARY KEY (id));');
                $db->query('CREATE TABLE Technologie (id int(9) NOT NULL auto_increment, nom varchar(255) NOT NULL DEFAULT \'error\', niveau int NOT NULL DEFAULT 1, PRIMARY KEY (id));');
                $db->query('CREATE TABLE TechnologieCout (idTechnologie int(9) NOT NULL, idRessource int(9) NOT NULL, FOREIGN KEY(idTechnologie) REFERENCES Technologie(id), FOREIGN KEY(idRessource) REFERENCES Ressource(id));');
                $db->query('CREATE TABLE BatimentDefense (id int(9) NOT NULL auto_increment, niveau int(9) NOT NULL DEFAULT 1, nom varchar(255) NOT NULL DEFAULT \'error\', attaque int NOT NULL DEFAULT 0, defense int NOT NULL DEFAULT 0, actif BOOLEAN NOT NULL DEFAULT TRUE, PRIMARY KEY(id));');
                $db->query('CREATE TABLE BatimentProduction (id int(9) NOT NULL auto_increment, niveau int(9) NOT NULL DEFAULT 1, nom varchar(255) NOT NULL DEFAULT \'error\', productionTemps int NOT NULL DEFAULT 1, actif BOOLEAN NOT NULL DEFAULT TRUE, idRessource int NOT NULL, PRIMARY KEY(id), FOREIGN KEY(idRessource) REFERENCES Ressource(id));');
                $db->query('CREATE TABLE BatimentDefenseCout (idBatiment int(9) NOT NULL, idRessource int(9) NOT NULL, FOREIGN KEY(idBatiment) REFERENCES BatimentDefense(id), FOREIGN KEY(idRessource) REFERENCES Ressource(id));');
                $db->query('CREATE TABLE BatimentProductionCout (idBatiment int(9) NOT NULL, idRessource int(9) NOT NULL, FOREIGN KEY(idBatiment) REFERENCES BatimentProduction(id), FOREIGN KEY(idRessource) REFERENCES Ressource(id));');
                $db->query('CREATE TABLE BatimentDefenseTechnologie (idBatiment int(9) NOT NULL, idTechnologie int(9) NOT NULL, FOREIGN KEY(idBatiment) REFERENCES BatimentDefense(id), FOREIGN KEY(idTechnologie) REFERENCES Technologie(id));');
                $db->query('CREATE TABLE BatimentProductionTechnologie (idBatiment int(9) NOT NULL, idTechnologie int(9) NOT NULL, FOREIGN KEY(idBatiment) REFERENCES BatimentProduction(id), FOREIGN KEY(idTechnologie) REFERENCES Technologie(id));');
                $db->query('CREATE TABLE BatimentProductionPrixReparation (idBatiment int(9) NOT NULL, idRessource int(9) NOT NULL, FOREIGN KEY(idBatiment) REFERENCES BatimentProduction(id), FOREIGN KEY(idRessource) REFERENCES Ressource(id));');
                $db->query('CREATE TABLE Unite (id int(9) NOT NULL auto_increment, type varchar(255) NOT NULL DEFAULT \'error\', niveau int(9) NOT NULL DEFAULT 1, idBatimentDefense int, idBatimentProduction int, PRIMARY KEY(id), FOREIGN KEY(idBatimentDefense) REFERENCES BatimentDefense(id), FOREIGN KEY(idBatimentProduction) REFERENCES BatimentProduction(id));');
                $db->query('CREATE TABLE UniteCout (idUnite int(9) NOT NULL, idRessource int(9) NOT NULL, FOREIGN KEY(idUnite) REFERENCES Unite(id), FOREIGN KEY(idRessource) REFERENCES Ressource(id));');
                $db->query('CREATE TABLE UniteTechnologie (idUnite int(9) NOT NULL, idTechnologie int(9) NOT NULL, FOREIGN KEY(idUnite) REFERENCES Unite(id), FOREIGN KEY(idTechnologie) REFERENCES Technologie(id));');
                $db->query('CREATE TABLE User (pseudo varchar(255) NOT NULL, password varchar(255) NOT NULL, coordonnee varchar(255) NOT NULL DEFAULT \'(0;0)\', faction varchar(255) NOT NULL DEFAULT \'Sans faction\', PRIMARY KEY(pseudo));');
                $db->query('CREATE TABLE UserRessources (pseudoUser varchar(255) NOT NULL, idRessource int(9) NOT NULL, FOREIGN KEY(pseudoUser) REFERENCES User(pseudo), FOREIGN KEY(idRessource) REFERENCES Ressource(id));');
                $db->query('CREATE TABLE UserTechnologies (pseudoUser varchar(255) NOT NULL, idTechnologie int(9) NOT NULL, FOREIGN KEY(pseudoUser) REFERENCES User(pseudo), FOREIGN KEY(idTechnologie) REFERENCES Technologie(id));');
                $db->query('CREATE TABLE UserBatimentsDefense (pseudoUser varchar(255) NOT NULL, idBatimentDefense int(9) NOT NULL, FOREIGN KEY(pseudoUser) REFERENCES User(pseudo), FOREIGN KEY(idBatimentDefense) REFERENCES BatimentDefense(id));');
                $db->query('CREATE TABLE UserBatimentsProduction (pseudoUser varchar(255) NOT NULL, idBatimentProduction int(9) NOT NULL, FOREIGN KEY(pseudoUser) REFERENCES User(pseudo), FOREIGN KEY(idBatimentProduction) REFERENCES BatimentProduction(id));');
                $db->query('CREATE TABLE UserUnites (pseudoUser varchar(255) NOT NULL, idUnite int(9) NOT NULL, FOREIGN KEY(pseudoUser) REFERENCES User(pseudo), FOREIGN KEY(idUnite) REFERENCES Unite(id));');
                
                
                
            }
            
        }
    }
?>