<?php
    require_once('Includes.php');
    
    class GestionTechnologieController{
        
        public function ___construct(){
            
        }
        
        /*
         * Doit vérifier que l'utilisateur possède les ressources nécessaires
         * Doit monter le niveau de la technologie de 1
         * Doit mettre à jour la technologie dans la bd (DAOTechnologie->update)
         * Doit soustraire les ressources de l'utilisateur et les mettre à jour dans la BD(DAOUser->update)
         * Retourne l'utilisateur entier modifié
         */
        public function upgradeTechnologie(User $toUpgrade){
            
        }
        
    }
?>