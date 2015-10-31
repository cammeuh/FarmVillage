<?php
    require_once('IncludeAllModel.php');
    
    class IndexController{
        
        
        public function ___construct(){
            
        }
        
        public function createTestUnit() {
            $user = new User();
            $user->setPseudo("cammeuh");
            $user->setPassword("vodka");
            $user->setFaction("Alliance");
            $user->setCoordonnee("(1;1)");
            
            $ressourceUn = $this->createRessource(RESSOURCEUN, 1234);
            $ressourceDeux = $this->createRessource(RESSOURCEDEUX, 9876);
            $ressourceTrois = $this->createRessource(RESSOURCETROIS, 5555);
            
            $user->addRessource($ressourceUn);
            $user->addRessource($ressourceDeux);
            $user->addRessource($ressourceTrois);
            
            $technologieUn = $this->createTechnologie(TECHNOLOGIEUN, $ressourceUn);
            $technologieDeux = $this->createTechnologie(TECHNOLOGIEDEUX, 2, [$ressourceUn, $ressourceDeux]);
            $technologieTrois = $this->createTechnologie(TECHNOLOGIETROIS, 5, $ressourceTrois);
            
            $user->addTechnologie($technologieDeux);
            
            $batimentDefense = $this->createBatimentDefense(1654867, $ressourceUn, $technologieTrois, 1000);
            
            $batimentProduction = new BatimentProduction();
            $batimentProduction->setId(0);
            $batimentProduction->setniveau(0);
            $batimentProduction->addRessourceCout($ressource);
            $batimentProduction->addTechNeeded($technologie);
            $batimentProduction->addRessourceProduction($ressource);
            $batimentProduction->setProductionTemps(60);
            $batimentProduction->addPrixReparation($ressource);
            $batimentProduction->setActif(true);
            
            $user->addBatiment($batimentDefense);
            $user->addBatiment($batimentProduction);
            
            $unite = new Unite();
            $unite->setId(0);
            $unite->setAffectation($batimentProduction);
            $unite->setNiveau(0);
            $unite->addRessourceCout($ressource);
            $unite->addTechNeeded($technologie);
            
            $user->addUnite($unite);
            
            var_dump($user);
        }
        
        /*
         * $quantite n'est pas obligatoire, considéré 0 si pas de quantite passée
         */
        public function createRessource($nom, $quantite = 0) {
            $returnValue = new Ressource();
            
            $returnValue->setNom($nom);
            $returnValue->setQuantite($quantite);
            
            return $returnValue;
        }
        
        /*
         * $niveau n'est pas obligatoire, considéré 0 si pas de niveau passé
         * $ressource doit être une Ressource ou un tableau de Ressource
         */
        public function createTechnologie($nom, $niveau = 0, $ressource) {
            $returnValue = new Technologie();
            
            $returnValue->setNom($nom);
            $returnValue->setNiveau($niveau);
            
            if (is_array($ressource)){
                foreach ($ressource as $r){
                    if (is_a($r,'Ressource')){
                        $returnValue->addCout($r);
                    }
                }
            }
            else if (is_a($ressource,'Ressource')){
                $returnValue->addCout($ressource);
            }
            else{
                return 0;
            }
            
            return $returnValue;
        }
        
        /*
         * $niveau, $attaque et $defense ne sont pas obligatoires, considérés 0 si pas passés
         * $cout doit être une Ressource ou un tableau de Ressource
         * $technologie doit être une Technologie ou un tableau de Technologie
         */
        public function createBatimentDefense($id, $niveau = 0, $cout, $technologie, $attaque = 0, $defense = 0) {
            $returnValue = new BatimentDefense();
            
            $returnValue->setId($id);
            $returnValue->setniveau($niveau);
            
            if (is_array($cout)){
                foreach ($cout as $c){
                    if (is_a($c,'Ressource')){
                        $returnValue->addCout($c);
                    }
                }
            }
            else if (is_a($cout,'Ressource')){
                $returnValue->addCout($cout);
            }
            else{
                return 0;
            }
            
            if (is_array($technologie)){
                foreach ($technologie as $t){
                    if (is_a($t,'Technologie')){
                        $returnValue->addTechNeeded($t);
                    }
                }
            }
            else if (is_a($t,'Technologie')){
                $returnValue->addTechNeeded($technologie);
            }
            else{
                return 0;
            }
            
            $returnValue->setAttaque($attaque);
            $returnValue->setDefense($defense);
            
            return $returnValue;
        }
        
        public function createBatimentProduction($id, $niveau = 0, $cout, $technologie, $productionType, $productionTemps, $prixReparation) {
            $returnValue = new BatimentDefense();
            
            $returnValue->setId($id);
            $returnValue->setniveau($niveau);
            
            if (is_array($cout)){
                foreach ($cout as $c){
                    if (is_a($c,'Ressource')){
                        $returnValue->addCout($c);
                    }
                }
            }
            else if (is_a($cout,'Ressource')){
                $returnValue->addCout($cout);
            }
            else{
                return 0;
            }
            
            if (is_array($technologie)){
                foreach ($technologie as $t){
                    if (is_a($t,'Technologie')){
                        $returnValue->addTechNeeded($t);
                    }
                }
            }
            else if (is_a($t,'Technologie')){
                $returnValue->addTechNeeded($technologie);
            }
            else{
                return 0;
            }
            
            if (is_array($productionType)){
                foreach ($productionType as $p){
                    if (is_a($p,'Ressource')){
                        $returnValue->addRessourceProduction($p);
                    }
                }
            }
            else if (is_a($p,'Ressource')){
                $returnValue->addRessourceProduction($productionType);
            }
            else{
                return 0;
            }
            
            /*
             * private $_productionType;	// Ressource[]
    private $_productionTemps;	// int
    private $_prixReparation;	// Ressource[]
    private $_actif;
             */
            
            return $returnValue;
        }
        
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
                $db->query('CREATE TABLE BatimentDefense (id int(9) NOT NULL auto_increment, niveau int(9) NOT NULL DEFAULT 1, attaque int NOT NULL DEFAULT 0, defense int NOT NULL DEFAULT 0, actif BOOLEAN NOT NULL DEFAULT TRUE, PRIMARY KEY(id));');
                $db->query('CREATE TABLE BatimentProduction (id int(9) NOT NULL auto_increment, niveau int(9) NOT NULL DEFAULT 1, productionTemps int NOT NULL DEFAULT 1, actif BOOLEAN NOT NULL DEFAULT TRUE, idRessource int NOT NULL, PRIMARY KEY(id), FOREIGN KEY(idRessource) REFERENCES Ressource(id));');
                $db->query('CREATE TABLE BatimentDefenseCout (idBatiment int(9) NOT NULL, idRessource int(9) NOT NULL, FOREIGN KEY(idBatiment) REFERENCES BatimentDefense(id), FOREIGN KEY(idRessource) REFERENCES Ressource(id));');
                $db->query('CREATE TABLE BatimentProductionCout (idBatiment int(9) NOT NULL, idRessource int(9) NOT NULL, FOREIGN KEY(idBatiment) REFERENCES BatimentProduction(id), FOREIGN KEY(idRessource) REFERENCES Ressource(id));');
                $db->query('CREATE TABLE BatimentDefenseTechnologie (idBatiment int(9) NOT NULL, idTechnologie int(9) NOT NULL, FOREIGN KEY(idBatiment) REFERENCES BatimentDefense(id), FOREIGN KEY(idTechnologie) REFERENCES Technologie(id));');
                $db->query('CREATE TABLE BatimentProductionTechnologie (idBatiment int(9) NOT NULL, idTechnologie int(9) NOT NULL, FOREIGN KEY(idBatiment) REFERENCES BatimentProduction(id), FOREIGN KEY(idTechnologie) REFERENCES Technologie(id));');
                $db->query('CREATE TABLE Unite (id int(9) NOT NULL auto_increment, niveau int(9) NOT NULL DEFAULT 1, idBatimentDefense int, idBatimentProduction int, PRIMARY KEY(id), FOREIGN KEY(idBatimentDefense) REFERENCES BatimentDefense(id), FOREIGN KEY(idBatimentProduction) REFERENCES BatimentProduction(id));');
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