<?php


$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_ENCODED);

switch ($action) {

    case 'validerFicheFrais':

        $visiteur = $pdo->getVisiteur();
        $moisVisiteur = $pdo->getLesMoisVisiteur();

        include 'vues/v_validerFrais.php';
        break;

    case 'detailFiche':

        $mois = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_ENCODED);
        $visiteur = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_ENCODED);

       $_SESSION['mois']=$mois;
       $_SESSION['user']=$visiteur;
       $moisVisiteur = $pdo->getLesMoisVisiteur();
       $lesInfosFicheFrais = $pdo->getLesInfosFicheFrais($visiteur, $mois);
    $numMois = substr($mois, 4, 2);
            $numAnnee = substr($mois, 0, 4);
    $libEtat = $lesInfosFicheFrais['libEtat'];
    $montantValide = $lesInfosFicheFrais['montantValide'];
    $dateModif = dateAnglaisVersFrancais($lesInfosFicheFrais['dateModif']);
    
         if (!$pdo->estPremierFraisMois($visiteur, $mois)) {
            $moisVisiteur = $pdo->getLesMoisVisiteur();
            $fraisForfaitVisiteur = $pdo->getLesFraisForfait(  $visiteur, $mois);
            $fraisHorsForfaitVisiteur = $pdo->getLesFraisHorsForfait(  $visiteur, $mois);
            include 'vues/v_validerDetail.php';
           
        } else {
            $error_message = "aucune fiche de frais n'est disponible pour le visiteur ce mois";
            //possibilite d'inclure les parametres $visiteur et $mois dans le message
    
            $visiteur = $pdo->getVisiteur();
            include 'vues/v_validerFrais.php';

            
        }
        
        
        break;

    case 'modifierForfait':
        //$mois = filter_input(INPUT_GET, 'mois', FILTER_SANITIZE_ENCODED);
        //$visiteur = filter_input(INPUT_GET ,'visiteur', FILTER_SANITIZE_ENCODED);
        $mois=$_SESSION['mois'];
        $visiteur=$_SESSION['user'];
        $fraisForfaitVisiteur = $pdo->getLesFraisForfait(  $visiteur, $mois);
        $fraisHorsForfaitVisiteur = $pdo->getLesFraisHorsForfait(  $visiteur, $mois);
        $pdo->majFraisForfait($visiteur, $mois,$_POST);
        include 'vues/v_validerDetail.php';
        break;

    case 'supprimerFraisHF':
       
       // var_dump($pdo->getDateHF(1));
       $mois=$_SESSION['mois'];
       $visiteur=$_SESSION['user'];
       $idFraisHF=filter_input(INPUT_POST, 'id', FILTER_SANITIZE_ENCODED);
       $fraisHorsForfaitVisiteur = $pdo->getLesFraisHorsForfait($visiteur , $mois);
       $nb = count ( $fraisHorsForfaitVisiteur );
       

       
        //boucle sur l ensemble des frais hf existants :id1,id2...
        for ($i = 1 ; $i<=$nb ; $i++){
            //si on selectionne un id
            if (isset($_POST['id'.$i])){
                //cas ou on refuse
                $libelle=$pdo->getLibelle($_POST['id'.$i]);
                
                if (strstr( $libelle,'REFUSE' )== false){
                $nvlibelle= "REFUSE :" . $libelle;
                $idL=$_POST['id'.$i];
                $pdo->majLibelle($nvlibelle,$idL);
                echo $nvlibelle;
                echo $idL;
                }

     
            }
        }
   
         
        $fraisForfaitVisiteur = $pdo->getLesFraisForfait(  $visiteur, $mois);
        $fraisHorsForfaitVisiteur = $pdo->getLesFraisHorsForfait(  $visiteur, $mois);
        
        include 'vues/v_validerDetail.php';
        break;

        case 'reporter':
            var_dump($_POST); 
            $levisiteur=$_SESSION['user'];
            $mois=$_SESSION['mois'];
    
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($levisiteur, $mois);
            
            // compte le nombre de ligne hors forfait pour l'utilisateur
            $nb=count($lesFraisHorsForfait);
          
            for ($i=1; $i<=$nb; $i++){
                // cas genéral ou l'on souhaite refusé un remboursement
               if (isset($_POST['id'.$i])){ 
    
                    // verifier si on à une date correcte < au 10 du mois en cours 
                    // la date du jour a verifier
                    $id=$_POST['id'.$i];
                    $date_jour_initial=$pdo->getDateHF($id);
                    echo $date_jour_initial;
                    $date_jour = new DateTimeImmutable($date_jour_initial);
    
                    // la date du mois de référence au 10 du mois au max
                    $annee_max=substr($_SESSION['mois'],0,4);
                    $mois_max=substr($_SESSION['mois'],4,2);
                    $jour_max=10;
                    $date_max = new DateTimeImmutable($annee_max."-".$mois_max."-".$jour_max);
                    // on verifie le nombre jour la date à verifier et le 10 du mois en cours
                    $interval = $date_max->diff($date_jour);
                    echo $interval->format('%a ');
                    // si on est >=  0 alors on est dans le systeme de report
    
                    // 1 Modifier le mois pour le mois dans hors forfait 
                    // on passe de 202304 à 202305
                
                    if (   $interval -> format('%a') >0 ) {
            
                    
                      $dates = new DateTime($annee_max."-".$mois_max."-".$jour_max);
                      $dates->modify('+1 month');
                    
                      $date_format= $dates->format('Ym');
                    
    
                    // avant la modification car il y a une contrainte d'intégrité
                    // 2 creer si elle n existe pas nouvelle ligne fiche forfait
                    // 3 nouvelle ligne frais forfait
                     $pdo->creeNouvellesLignesFrais($_SESSION['user'], $date_format);
    
                    // 1 Modification de la date
                      $pdo->setMoisencoursFraisHF($date_format,$id);
                    }  
    
               }
            }
    
            
            $lesFraisForfait = $pdo->getLesFraisForfait($levisiteur, $mois);
            $lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($levisiteur, $mois);
            
       
            include 'vues/v_valider_detail.php';
          
    
    
    
    
    
            break;    

    case 'validerFiche':
        $mois=$_SESSION['mois'];
        $visiteur=$_SESSION['user'];
        $pdo->majEtatFicheFrais($visiteur ,$mois,"VA");

        include 'vues/v_ficheValidee.php';
        
        break;
     
   

    
}