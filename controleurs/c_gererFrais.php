<?php
/**
 * Gestion des frais
 *
 * PHP Version 7
 *
 * @category  PPE
 * @package   GSB
 * @author    Réseau CERTA <contact@reseaucerta.org>
 * @author    José GIL <jgil@ac-nice.fr>
 * @copyright 2017 Réseau CERTA
 * @license   Réseau CERTA
 * @version   GIT: <0>
 * @link      http://www.reseaucerta.org Contexte « Laboratoire GSB »
 */
//on a besoin de l id du visiteur pr generer une fiche de frais 
$idVisiteur = $_SESSION['idVisiteur'];
//date() =>renvoie la date  du jour selon le format renseigne
//get mois recupere le mois au format aaaamm 
$mois = getMois(date('d/m/Y'));
//fonction substr va prendre en parametre la date et va recupere une partie de $mois =200012 du 0 au 4e caractere 
//ds ce cas numannee sera  2000
$numAnnee = substr($mois, 0, 4);
//ds ce cas ce st les 2 caracteres debutant a la position 4  cad ds 200012 c 12

$numMois = substr($mois, 4, 2);

//ds l url on lit le parametre action et le stocke ds une variable $action
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_ENCODED);


//va regarder la ction et agir differemment selon les cas 
switch ($action) {

    //lorsque l utilisateur choisi de saisir ses frais
    //si c est le premier frais du mois,on cree une nvlle ligne de frais(BDD)
case 'saisirFrais':
    if ($pdo->estPremierFraisMois($idVisiteur, $mois)) { 
        $pdo->creeNouvellesLignesFrais($idVisiteur, $mois);
    }
    break;
    //lorsque le visiteur veut valider ses frais forfaits,si la quantite des frais saisis  est valide ,on l inscrit 
case 'validerMajFraisForfait':
    $lesFrais = filter_input(INPUT_POST, 'lesFrais', FILTER_SANITIZE_ENCODED);
    $lesFrais=$_POST['lesFrais'];
    if (lesQteFraisValides($lesFrais)) {
        $pdo->majFraisForfait($idVisiteur, $mois, $lesFrais);
        //sinon on affiche une erreur
    } else {
        ajouterErreur('Les valeurs des frais doivent être numériques');
        include 'vues/v_erreurs.php';
    }
    break;
    //dans le cas ou la validation des frais est faite,on recupere les valeurs saisies et on les affiche ds un tableau
case 'validerCreationFrais':
    //recuper la date frais du formulaire grace a l attribut name de l input)
    $dateFrais = filter_input(INPUT_POST, 'dateFrais', FILTER_SANITIZE_SPECIAL_CHARS);
    $libelle = filter_input(INPUT_POST, 'libelle', FILTER_SANITIZE_SPECIAL_CHARS);
    $montant = filter_input(INPUT_POST, 'montant', FILTER_VALIDATE_FLOAT);
    var_dump($montant);
    valideInfosFrais($dateFrais, $libelle, $montant);
    //si les champs ne sont pas renseignés, on affiche la vue erreur
    if (nbErreurs() != 0) {
        include 'vues/v_erreurs.php';
    } else {
        //sinon on cree dans la base de donnees une ligne de fraisHF en recuperant les donnees renseignees ds le formulaire
        $pdo->creeNouveauFraisHorsForfait(
            $idVisiteur,
            $mois,
            $libelle,
            $dateFrais,
            $montant
        );
    }
    break;
    //dans le cas ou le visiteur supprime des frais , on supprime les donnees ds la base de donnees
case 'supprimerFrais':
    $idFrais = filter_input(INPUT_GET, 'idFrais', FILTER_SANITIZE_ENCODED);
    $pdo->supprimerFraisHorsForfait($idFrais);
    break;
}
$lesFraisHorsForfait = $pdo->getLesFraisHorsForfait($idVisiteur, $mois);


//qd on clique sur la page "renseigner fiche de frais",une insertion se fait ds la ligneforfait de la BDD
//contenant idvisiteur,mois et quantite a 0
//cette fonction renvoie une tableau contenant les elements de la fiche de frais
//qui est insere dans la table ligneforfait de la BDD 

$lesFraisForfait = $pdo->getLesFraisForfait($idVisiteur, $mois);

require 'vues/v_listeFraisForfait.php';

//rempli le tableau de la vue  renseigner frais ac les donnees saisies ds le formulaire de frais hf
//en respectant les conditions d affichage definies dans le html de v frais hf
//co la date en format francais (et non formatanglais utilise ds bdd)
//le formulaire hf est statique cad que par defaut on a rien et des qu on renseigne ca remplit le descriptif
//alros que forfait c dynamique
require 'vues/v_listeFraisHorsForfait.php';
