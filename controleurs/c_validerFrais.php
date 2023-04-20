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
                }elseif($jour > 10){
                    $jour=$pdo->getDateHF($_POST['id'.$i]);
                    
                }

     
            }
        }
   
         
        $fraisForfaitVisiteur = $pdo->getLesFraisForfait(  $visiteur, $mois);
        $fraisHorsForfaitVisiteur = $pdo->getLesFraisHorsForfait(  $visiteur, $mois);
        
        include 'vues/v_validerDetail.php';
        break;

    case 'validerFiche':
        $mois=$_SESSION['mois'];
        $visiteur=$_SESSION['user'];
        $pdo->majEtatFicheFrais($visiteur ,$mois,"VA");

        include 'vues/v_ficheValidee.php';
        
        break;

   

    
}