<?php
    require_once('Includes.php');
    
    class GestionController{
        private $_DAOUser;
        private $_globalController;
        
        public function ___construct(){
            $this->_DAOUser = new DAOUser();
            $this->_globalController = new GlobalController();
        }
        
        /*
         * $toUpdate = l'utilisateur entier
         * $idTechnologie = l'id de la technologie à upgrade
         * Retourne l'utilisateur entier modifié
         */
        public function upgradeTechnologie(User $toUpgrade, $idTechnologie){
            foreach($toUpgrade->getTechnologies() as $tech){
                if ($tech->getId() == $idTechnologie){
                    $coutTech = $tech->getCout();
                    $ressourcesUser = $toUpgrade->getRessources();
                    $isUpgradeOk = true;
                    
                    foreach($coutTech as $res){
                        foreach($ressourcesUser as $resUser){
                            if ($res->getNom() == $resUser->getNom()){
                                if ($res->getQuantite() * ($tech->getNiveau() + 1) > $resUser->getQuantite()){
                                    $isUpgradeOk = false;
                                    break;
                                }
                            }
                        }
                    }
                    
                    if ($isUpgradeOk){
                        $newLevel = $tech->getNiveau() + 1;
                        $tech->setNiveau($newLevel);
                        
                        foreach($coutTech as $res){
                            foreach($ressourcesUser as $resUser){
                                if ($res->getNom() == $resUser->getNom()){
                                    $resUser->addQuantite(0 - $res->getQuantite());
                                }
                            }
                        }
                    }
                }
            }
            
            return $this->DAOUser->updateUser($toUpgrade);
        }
        
        /*
         * $user = l'utilisateur entier
         * $typeBatiment = le nom (constante) du batiment à upgrade
         * Retourne l'utilisateur entier modifié
         * fonctionne pour les batimentDefense et les batimentProduction
         */
        public function updateBatiment(User $user, $typeBatiment){
            foreach($user->getBatimentsProduction() as $batiment){
                if ($batiment->getNom() == $typeBatiment){
                    if ($this->_globalController->compareTechnologies($user->getTechnologies, $batiment->getTechNeeded())){
                        if ($this->_globalController->compareRessources($user->getRessources, $batiment->getCout())){
                            $level = $batiment->getNiveau();
                            $batiment->setNiveau($level + 1);
                            $cout = $batiment->getCout();
                            foreach($cout as $res){
                                foreach($ressourcesUser as $resUser){
                                    if ($res->getNom() == $resUser->getNom()){
                                        $resUser->addQuantite(0 - ($res->getQuantite() * ($level + 1)));
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            foreach($user->getBatimentsDefense() as $batiment){
                if ($batiment->getNom() == $typeBatiment){
                    if ($this->_globalController->compareTechnologies($user->getTechnologies, $batiment->getTechNeeded())){
                        if ($this->_globalController->compareRessources($user->getRessources, $batiment->getCout())){
                            $level = $batiment->getNiveau();
                            $batiment->setNiveau($level + 1);
                            $cout = $batiment->getCout();
                            foreach($cout as $res){
                                foreach($ressourcesUser as $resUser){
                                    if ($res->getNom() == $resUser->getNom()){
                                        $resUser->addQuantite(0 - ($res->getQuantite() * ($level + 1)));
                                    }
                                }
                            }
                        }
                    }
                }
            }
            
            return $this->DAOUser->updateUser($user);
        }
        
        /*
         * $user = l'utilisateur entier
         * $typeBatiment = le nom (constante) du batiment à upgrade
         * $nombre = le nombre d'unités à créer
         * Retourne l'utilisateur entier modifié
         */
        public function createUnite(User $user, $typeUnite, $nombre){
            switch($typeUnite){
                case UNITEUN:
                    if ($this->_globalController->compareTechnologies($user->getTechnologies, [$this->_globalController->createTechnologie(TECHNOLOGIETROIS, $this->createRessource(RESSOURCETROIS, 500), 2)])){
                        if ($this->_globalController->compareRessources($user->getRessources, [$this->_globalController->createRessource(RESSOURCETROIS, 1000*$nombre)])){
                            for($i=0; $i<$nombre; $i++){
                                $user->addUnite($this->_globalController->createUnite($typeUnite, [$this->_globalController->createRessource(RESSOURCETROIS, 1000)], [$this->_globalController->createTechnologie(TECHNOLOGIETROIS, $this->createRessource(RESSOURCETROIS, 500), 2)], $niveau = 1));
                            }
                        }
                    }
                    break;
                case UNITEDEUX:
                    if ($this->_globalController->compareTechnologies($user->getTechnologies, [$this->_globalController->createTechnologie(TECHNOLOGIETROIS, $this->createRessource(RESSOURCETROIS, 500), 4)])){
                        if ($this->_globalController->compareRessources($user->getRessources, [$this->_globalController->createRessource(RESSOURCETROIS, 2500*$nombre)])){
                            for($i=0; $i<$nombre; $i++){
                                $user->addUnite($this->_globalController->createUnite($typeUnite, [$this->_globalController->createRessource(RESSOURCETROIS, 2500)], [$this->_globalController->createTechnologie(TECHNOLOGIETROIS, $this->createRessource(RESSOURCETROIS, 500), 4)], $niveau = 1));
                            }
                        }
                    }
                    break;
            }
            
            return $this->DAOUser->updateUser($user);
        }
        
        /*
         * $user = l'utilisateur entier
         * $idBatimentProd = l'id du batimentProduction assigné
         * $idBatimentDef = l'id du batimentDefense assigné
         * Au moins un des deux idBatiment doit être à 0, sinon l'affectation ne se fait pas
         * $idUnite = l'id de l'unite à assigner
         * Retourne l'utilisateur entier modifié
         */
        public function assignUnitToBatiment(User $user, $idUnite, $idBatimentProd, $idBatimentDef){
            if (!($idBatimentDef == 0 && $idBatimentProd == 0)){
                $DAOBatimentDefense = new DAOBatimentDefense();
                $DAOBatimentProduction = new DAOBatimentProduction();
                foreach($user->getUnites() as $unit){
                    if ($unit->getId() == $idUnite){
                        if ($idBatimentDef != 0){
                            $unit->setAffectationDefense($DAOBatimentDefense->getBatimentDefenseById($idBatimentDef));
                            $unit->setAffectationProduction(null);
                        }
                        else if ($idBatimentProd != 0){
                            $unit->setAffectationProduction($DAOBatimentroduction->getBatimentProductionById($idBatimentProd));
                            $unit->setAffectationDefense(null);
                        }
                    }
                }
            }
            
            return $this->DAOUser->updateUser($user);
        }
    }
?>