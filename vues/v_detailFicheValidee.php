<hr>
<form method="post" action="uc=suivrePaiementFicheFrais&action=misePaiementFiche>" role="form">
<div class="panel panel-info">
    <div class="panel-heading">Eléments forfaitisés</div>
    <table class="table table-bordered table-responsive">
        <tr>
            <?php
            foreach ($fraisForfaitVisiteur as $unFraisForfaitVisiteur) {
                $libelle = $unFraisForfaitVisiteur['libelle']; ?>
                <th> <?php echo htmlspecialchars($libelle) ?></th>
                <?php
            }
            ?>
        </tr>
        <tr>
            <?php
            foreach ($fraisForfaitVisiteur as $unFraisForfaitVisiteur) {
                $quantite = $unFraisForfaitVisiteur['quantite'];
                $idFrais = $unFraisForfaitVisiteur['idfrais'];
                 ?>
                <td class="qteForfait"><?php echo $quantite ?> 
              
                </td>
                <?php
            }
            ?>
            
        </tr>
    </table>
          
   
</div>
<?php var_dump($etat);?>

</form> 
<form method="post" action="index.php?uc=validerFrais&action=supprimerFraisHF&visiteur=<?= $unVisiteur ?>" role="form">
<div class="panel panel-info">
    <div class="panel-heading">Descriptif des éléments hors forfait </div>
    <table class="table table-bordered table-responsive">
        <tr>
            <th class="date">Date</th>
            <th class="libelle">Libellé</th>
            <th class='montant'>Montant</th>
                         
        </tr>
        <?php
        foreach ($fraisHorsForfaitVisiteur as $unFraisHorsForfaitVisiteur) {
            $date = $unFraisHorsForfaitVisiteur['date'];
            $libelle = htmlspecialchars($unFraisHorsForfaitVisiteur['libelle']);
            $montant = $unFraisHorsForfaitVisiteur['montant']; ?>
            <tr>
                <td><?php echo $date ?></td>
                <td><?php echo $libelle ?></td>
                <td><?php echo $montant ?></td>

            </tr>
            <?php
        }
        ?>
    </table>
  

</div>
<?php
if($etat =="MP"){?>
<a href = "index.php?uc=suivrePaiementFicheFrais&action=remboursee&visiteur=<?php echo $unVisiteur?>&mois=<?=$mois?>">
<button id="ok" type="submit" value="Valider" class="btn btn-primary" role="button"> Rembourser la fiche </button></a>
<?php }else{?>
    
<a href = "index.php?uc=suivrePaiementFicheFrais&action=misePaiementFiche&visiteur=<?php echo $unVisiteur?>">
<button id="ok" type="submit" value="Valider" class="btn btn-primary" role="button">Mise en paiement </button></a>
<?php }?>

</form>
