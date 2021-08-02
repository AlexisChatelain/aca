<?php

/*---------------------------------------------------------------*/
/*
    Titre : Dump (sauvegarde) avec PHP d'une base de donnée mysqli                                                        
                                                                                                                          
    URL   : https://phpsources.net/code_s.php?id=612
    Auteur           : miragoo                                                                                            
    Date édition     : 28 Oct 2010                                                                                        
    Date mise à jour : 13 Sept 2019                                                                                      
    Rapport de la maj:                                                                                                    
    - fonctionnement du code vérifié                                                                                    
*/
/*---------------------------------------------------------------*/
	require('../config/database.php');
	$mode=2;
	  
    $entete  = "-- ----------------------\n";
    $entete .= "-- dump de la base ".DB_NAME." au ".date("d-M-Y")."\n";
    $entete .= "-- ----------------------\n\n\n";
    $creations = "";
    $insertions = "\n\n";
    $listeTables= $db->query("show tables");
    while($table=$listeTables->fetch_array())
    {
        // structure ou la totalité de la BDD
        if($mode == 1 || $mode == 2)
        {
            $creations .= "-- -----------------------------\n";
            $creations .= "-- Structure de la table ".strval($table[0])."\n";
            $creations .= "-- -----------------------------\n";
            $listeCreationsTables = $db->query("show create table ".$table[0]);
			while($creationTable =$listeCreationsTables->fetch_array())
            {
              $creations .= $creationTable[1].";\n\n";
            }
        }
        // données ou la totalité
        if($mode > 1)
        {
			$donnees= $db->query("SELECT * FROM ".$table[0]);
            $insertions .= "-- -----------------------------\n";
            $insertions .= "-- Contenu de la table ".$table[0]."\n";
            $insertions .= "-- -----------------------------\n";
			while($nuplet=$donnees->fetch_array())
            {
                $insertions .= "INSERT INTO ".$table[0]." VALUES(";
                for($i=0; $i < mysqli_num_fields($donnees); $i++)
                {
                  if($i != 0)
                     $insertions .=  ", ";
                
				if($nuplet[$i]=="")	
					$insertions .=  "null";
				else if($donnees->fetch_field_direct($i)->type != 3 || 
					$donnees->fetch_field_direct($i)->type != 4) #longblob ou varchar ou char ou datetime
                     $insertions .=  "'".addslashes($nuplet[$i]). "'";
				else 
					$insertions .=  addslashes($nuplet[$i]);
				
                }
                $insertions .=  ");\n";
            }
            $insertions .= "\n";
        }
    }
 
	$suppr_utilisateur= $db->query("DELETE FROM utilisateur WHERE confirmation<>'OK' AND TIMEDIFF(NOW(), date_inscription)>'24:00:00';");
	$suppr_cheques= $db->query("DELETE FROM cheques WHERE cheques.confirmation<>'OK' AND cheques.id_dossier IN (SELECT id_dossier FROM dossier WHERE TIMEDIFF(NOW(), date_creation)>'24:00:00');");
	$suppr_dossier= $db->query("DELETE FROM dossier WHERE confirmation<>'OK' AND TIMEDIFF(NOW(), date_creation)>'24:00:00';");	 
	$suppr_commande= $db->query("DELETE FROM commande WHERE confirmation=0;");	 
	
	
	mysqli_close($db);
	
	#LES SAUVEGARDES DE LA BASE DE DONNEES DE SITE DU CLUB
	
	
	
    $fichierDump = fopen(date("Y-m-d")." save.sql", "wb");
    fwrite($fichierDump, $entete);
    fwrite($fichierDump, $creations);
    fwrite($fichierDump, $insertions);
    fclose($fichierDump);
	echo "Tache OK";
    
    

?>

