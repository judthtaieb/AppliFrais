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
        $unVisiteur = filter_input(INPUT_POST, 'visiteur', FILTER_SANITIZE_ENCODED);
        $fraisForfaitVisiteur = $pdo->getLesFraisForfait(  $unVisiteur, '202303');
        //var_dump( $fraisForfaitVisiteur);
        $fraisHorsForfaitVisiteur = $pdo->getLesFraisHorsForfait(  $unVisiteur, '202303');
        //var_dump($fraisHorsForfaitVisiteur);
        include 'vues/v_validerDetail.php';
        
        break;

    case 'modifierForfait':
        $mois = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_ENCODED);
        $idVisiteur = filter_input(INPUT_GET ,'visiteur', FILTER_SANITIZE_ENCODED);
        $pdo->majFraisForfait($idVisiteur, $mois,$_POST);

        var_dump($_POST);
        break;

    case 'supprimerFraisHF':
       
        $unVisiteur = filter_input(INPUT_GET ,'visiteur', FILTER_SANITIZE_ENCODED);
     
        $idFraisHF=filter_input(INPUT_POST, 'id', FILTER_SANITIZE_ENCODED);
        $fraisHorsForfaitVisiteur = $pdo->getLesFraisHorsForfait($unVisiteur, "202212");
        //var_dump ($fraisHorsForfaitVisiteur);
        $nb = count ( $fraisHorsForfaitVisiteur );
        //echo "<hr>".$nb."</hr>";
        for ($i = 1 ; $i<=$nb ; $i++){
            if (isset($_POST['id'.$i]) ){
                $libelle=$pdo->getLibelle($_POST['id'.$i]);
                $nvlibelle= "REFUSE :" . $libelle;
                $idL=$_POST['id'.$i];
                $pdo->majLibelle($nvlibelle,$idL);
                echo $nvlibelle;
                echo $idL;
            }
        }
   
        //$maj=$pdo->majFraisHorsForfait($idFraisHF);  
        //var_dump($_POST);
        break;
    case 'validerFiche':
        $mois = filter_input(INPUT_POST, 'mois', FILTER_SANITIZE_ENCODED);
        $unVisiteur = filter_input(INPUT_POST, 'idvisiteur', FILTER_SANITIZE_ENCODED);
        $pdo->majEtatFicheFrais($unVisiteur,'202212',"VA");

        include 'vues/v_ficheValidee.php';
        //var_dump($_GET);
        break;
    }