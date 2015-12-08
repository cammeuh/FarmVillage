<?php
    require_once('Includes.php');
    
    class GlobalController{
        
        public function ___construct(){
            
        }
        
        public function displayUser(User $user){
            $returnValue = 'Ressources :<br/>';
            
            foreach($user->getRessources() as $res){
                $returnValue.='---'.$res->getNom().' : '.$res->getQuantite().'<br/>';
            }
            
            $returnValue.='Technologies :<br/>';
            
            foreach($user->getTechnologies() as $tech){
                $returnValue.='---'.$tech->getNom().' : '.$tech->getNiveau().'<br/>';
            }
            
            $returnValue.='Batiments de Defense :<br/>';
            
            foreach($user->getBatimentsDefense() as $bat){
                $returnValue.='---'.$bat->getNom().' : '.$bat->getNiveau().'<br/>';
            }
            
            $returnValue.='Batiments de Production :<br/>';
            
            foreach($user->getBatimentsProduction() as $bat){
                $returnValue.='---'.$bat->getNom().' : '.$bat->getNiveau().'<br/>';
            }
            
            $returnValue.='Unites :<br/>';
            
            foreach($user->getUnites() as $unit){
                $returnValue.='---'.$unit->getNom().' : '.$unit->getNiveau().'<br/>';
            }
            
            return $returnValue;
        }
        
        public function compareTechnologies($userTech, $tech){
            $returnValue = true;
            
            foreach($userTech as $ut){
                foreach($tech as $t){
                    if ($ut->getNom() === $t->getNom()){
                        if ($ut->getNiveau() < $t->getNiveau()){
                            $returnValue = false;
                            break;
                        }
                    }
                }
            }
            
            return $returnValue;
        }
        
        public function compareRessources($userRess, $ress){
            $returnValue = true;
            
            foreach($userRess as $ur){
                foreach($ress as $r){
                    if ($ur->getNom() === $r->getNom()){
                        if ($ur->getQuantite() < $r->getQuantite()){
                            $returnValue = false;
                            break;
                        }
                    }
                }
            }
            
            return $returnValue;
        }
        
        /*
         * THIS METHOD MUST BE CALLED EVERYTIME WE GET A NEW PAGE
         * it calculates the ressources produced since last page visited
         */
        public function calculateNewProduction(User $user){
            $returnValue = $user;
            $now = time();
            foreach($user->getBatimentsProduction() as $batiment){
                if ($batiment->isActif()){
                    $then = $batiment->getProductionTemps();
                    $timeDelta = $now-$then;
                    $ressourceNom = $batiment->getProductionType()->getNom();
                    $ressourceQuantiteAProduire = $batiment->getProductionType()->getQuantite();
                    $batimentNiveau = $batiment->getNiveau();
                    
                    foreach($user->getRessources() as $ressource){
                        if ($ressource->getNom() == $ressourceNom){
                            $ressourceQuantiteThen = $ressource->getQuantite();
                            $ressource->addQuantite($ressourceQuantiteAProduire*($timeDelta*$batimentNiveau/3600));
                            break;
                        }
                    }
                }
            }
            
            return $returnValue;
        }
        
        /*
         * METHODS TO CREATE BASIC OBJECTS, WHICH ARE PLAYABLE
         */
        
        public function createNewUser($pseudo, $password, $faction, $coordonnee){
            $ressourcesToCreate = $this->createNewRessourcesForNewUser();
            $technologiesToCreate = $this->createNewTechnologiesForNewUser();          
            $batimentsDefToCreate = array();
            $batimentsProdToCreate = $this->createNewBatimentsProductionForNewUser();
            $unitesToCreate = array();
            
            return $this->createUser($pseudo, $password, $faction, $coordonnee, $ressourcesToCreate, $technologiesToCreate, $batimentsDefToCreate, $batimentsProdToCreate, $unitesToCreate);
        }
        
        public function createNewRessourcesForNewUser(){
            $returnValue = array();
            $returnValue[] = $this->createRessource(RESSOURCEUN, 3000);
            $returnValue[] = $this->createRessource(RESSOURCEDEUX, 800);
            $returnValue[] = $this->createRessource(RESSOURCETROIS);
            
            return $returnValue;
        }
        
        public function createNewTechnologiesForNewUser(){
            $returnValue = array();
            $returnValue[] = $this->createTechnologie(TECHNOLOGIEUN, $this->createRessource(RESSOURCEUN, 2000));
            $returnValue[] = $this->createTechnologie(TECHNOLOGIEDEUX, [$this->createRessource(RESSOURCEUN, 2000), $this->createRessource(RESSOURCEDEUX, 1500)]);
            $returnValue[] = $this->createTechnologie(TECHNOLOGIETROIS, $this->createRessource(RESSOURCETROIS, 500));
            
            return $returnValue;
        }
        
        public function createNewBatimentsProductionForNewUser(){
            $returnValue = array();
            $returnValue[] = $this->createBatimentProduction(BATIMENTPRODUN, 
                    $this->createRessource(RESSOURCEUN, 300), 
                    null, 
                    $this->createRessource(RESSOURCEUN, 50), 
                    $this->createRessource(RESSOURCEUN, 300),
                    true);
            $returnValue[] = $this->createBatimentProduction(BATIMENTPRODDEUX, 
                    $this->createRessource(RESSOURCEUN, 450),
                    $this->createTechnologie(TECHNOLOGIEUN, $this->createRessource(RESSOURCEUN, 2000), $niveau = 1),  
                    $this->createRessource(RESSOURCEDEUX, 40), 
                    $this->createRessource(RESSOURCEUN, 450),
                    true);
            $returnValue[] = $this->createBatimentProduction(BATIMENTPRODTROIS, 
                    [$this->createRessource(RESSOURCEUN, 450), $this->createRessource(RESSOURCEDEUX, 150)],
                    $this->createTechnologie(TECHNOLOGIEDEUX, $this->createRessource(RESSOURCEDEUX, 1500), $niveau = 2), 
                    $this->createRessource(RESSOURCETROIS, 15), 
                    $this->createRessource(RESSOURCEUN, 600),
                    true);
            return $returnValue;
        }
        
        /*
         * METHODS TO CREATE BASIC OBJECTS WITH PARAMS, MANDATORY OR NOT
         */
        
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
        public function createTechnologie($nom, $ressource, $niveau = 0) {
            $returnValue = new Technologie();
            
            $returnValue->setNom($nom);
            $returnValue->setNiveau($niveau);
            
            if (is_array($ressource)){
                foreach ($ressource as $r){
                    if (is_a($r,'Ressource')){
                        $returnValue->addRessourceCout($r);
                    }
                }
            }
            else if (is_a($ressource,'Ressource')){
                $returnValue->addRessourceCout($ressource);
            }
            
            return $returnValue;
        }
        
        /*
         * $niveau, $attaque et $defense ne sont pas obligatoires, considérés 0 si pas passés
         * $cout doit être une Ressource ou un tableau de Ressource
         * $technologie doit être une Technologie ou un tableau de Technologie
         */
        public function createBatimentDefense($nom, $cout, $technologie, $niveau = 0, $attaque = 0, $defense = 0) {
            $returnValue = new BatimentDefense();
            
            $returnValue->setniveau($niveau);
            $returnValue->setNom($nom);
            
            if (is_array($cout)){
                foreach ($cout as $c){
                    if (is_a($c,'Ressource')){
                        $returnValue->addRessourceCout($c);
                    }
                }
            }
            else if (is_a($cout,'Ressource')){
                $returnValue->addRessourceCout($cout);
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
            
            $returnValue->setAttaque($attaque);
            $returnValue->setDefense($defense);
            
            return $returnValue;
        }
        
        /*
         * $niveau n'est pas obligatoire, considéré comme 0 si pas passé
         * $actif doit être un boolean
         * $cout et $prixReparation doivent être une Ressource ou un tableau de Ressource
         * $technologie doit être une Technologie ou un tableau de Technologie
         * $productionType doit être une Ressource
         */
        public function createBatimentProduction($nom, $cout, $technologie, Ressource $productionType, $prixReparation, $actif, $niveau = 0) {
            $returnValue = new BatimentProduction();
            
            $returnValue->setniveau($niveau);
            $returnValue->setActif($actif);
            $returnValue->setNom($nom);
            $returnValue->setProductionType($productionType);
            
            if (is_array($cout)){
                foreach ($cout as $c){
                    if (is_a($c,'Ressource')){
                        $returnValue->addRessourceCout($c);
                    }
                }
            }
            else if (is_a($cout,'Ressource')){
                $returnValue->addRessourceCout($cout);
            }
            
            if(!is_null($technologie)){
                if (is_array($technologie)){
                    foreach ($technologie as $t){
                        if (is_a($t,'Technologie')){
                            $returnValue->addTechNeeded($t);
                        }
                    }
                }
                else if (is_a($technologie,'Technologie')){
                    $returnValue->addTechNeeded($technologie);
                }
            }
            
            if (is_array($prixReparation)){
                foreach ($prixReparation as $c){
                    if (is_a($c,'Ressource')){
                        $returnValue->addPrixReparation($c);
                    }
                }
            }
            else if (is_a($prixReparation,'Ressource')){
                $returnValue->addPrixReparation($prixReparation);
            }
            
            return $returnValue;
        }
        
        /*
         * $niveau n'est pas obligatoire, considéré comme 0 si pas passé
         * $type doit être un string
         * $cout doit être une Ressource ou un tableau de Ressource
         * $batDef doit être un BatimentDefense ou 0
         * $batProd doit être un BatimentProduction ou 0
         * Il ne peut pas y avoir ET un BatimentDefense ET un BatimentProduction
         * => SOIT l'un, SOIT l'autre SOIT aucun
         */
        public function createUnite($type, $cout, $technologie, $batDef = 0, $batProd = 0, $niveau = 0){
            $returnValue = new Unite();
            
            $returnValue->setniveau($niveau);
            $returnValue->setType($type);
            
            if (is_array($cout)){
                foreach ($cout as $c){
                    if (is_a($c,'Ressource')){
                        $returnValue->addRessourceCout($c);
                    }
                }
            }
            else if (is_a($cout,'Ressource')){
                $returnValue->addRessourceCout($cout);
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
            
            if (($batDef !== 0 && $batProd === 0) || ($batDef == 0 && $batProd !== 0) || ($batDef === 0 && $batProd === 0)){
                $returnValue->setAffectationDefense($batDef);
                $returnValue->setAffectationProduction($batProd);
            }
            
            return $returnValue;
        }
        
        /*
         * $pseudo, $password, $faction, $coordonnee sont des strings
         * $ressources doit être un tableau de Ressource
         * $technologies doit être un tableau de Technologie
         * $batimentsProd doit être un tableau de BatimentProduction
         * $batimentsDef doit être un tableau de BatimentDefense
         * $unites doit être un tableau d'Unite
         * CETTE FONCTION NE DEVRAIT JAMAIS ETRE APPELEE, D'AUTRES FONCTIONS
         * SONT LA POUR S'OCCUPER DE LA CREATION DES UTILISATEURS
         */
        public function createUser($pseudo, $password, $faction, $coordonnee, $ressources, $technologies, $batimentsDef, $batimentsProd, $unites){
            $returnValue = new User();
            
            $returnValue->setPseudo($pseudo);
            $returnValue->setPassword($password);
            $returnValue->setFaction($faction);
            $returnValue->setCoordonnee($coordonnee);
            $returnValue->setRessources(array());
            $returnValue->setTechnologies(array());
            $returnValue->setBatimentsDefense(array());
            $returnValue->setBatimentsProduction(array());
            $returnValue->setUnites(array());
            
            if (is_array($ressources)){
                foreach ($ressources as $r){
                    if (is_a($r,'Ressource')){
                        $returnValue->addRessource($r);
                    }
                }
            }
            
            if (is_array($technologies)){
                foreach ($technologies as $t){
                    if (is_a($t,'Technologie')){
                        $returnValue->addTechnologie($t);
                    }
                }
            }
            
            if (is_array($batimentsDef)){
                foreach ($batimentsDef as $b){
                    if (is_a($b,'BatimentDefense')){
                        $returnValue->addBatimentDefense($b);
                    }
                }
            }
            
            if (is_array($batimentsProd)){
                foreach ($batimentsProd as $b){
                    if (is_a($b,'BatimentProduction')){
                        $returnValue->addBatimentProduction($b);
                    }
                }
            }
            
            if (is_array($unites)){
                foreach ($unites as $u){
                    if (is_a($u,'Unite')){
                        $returnValue->addUnite($u);
                    }
                }
            }
            
            return $returnValue;
        }
        
        
        
    }
?>