<?php
    require_once('Includes.php');
    
    class GestionTechnologieController{
        
        public function ___construct(){
            
        }
        
        /*
         * Doit v�rifier que l'utilisateur poss�de les ressources n�cessaires
         * Doit monter le niveau de la technologie de 1
         * Doit mettre � jour la technologie dans la bd (DAOTechnologie->update)
         * Doit soustraire les ressources de l'utilisateur et les mettre � jour dans la BD(DAOUser->update)
         * Retourne l'utilisateur entier modifi�
         */
        public function upgradeTechnologie(User $toUpgrade){
            
        }
        
    }
?>