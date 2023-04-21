
<h2>Suivi paiement fiche frais</h2>

<h3>Sélectionner une fiche de frais : </h3>
                                                                                                                                             
<br>   
<div class="row">
    <div class="panel panel-info">
        <div class="panel-heading">Liste des fiches de frais</div>
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th class="date">id visiteur</th>
                    <th class="mois">mois</th> 
                    <th class="action">Detail</th> 
                </tr>
            </thead>  
            <tbody>
            <?php foreach ($ficheFrais as $uneFicheFrais) { ?>
                
                    
                <tr>
                    <td> <?php echo $uneFicheFrais['idvisiteur'] ?></td>
                    <td> <?php echo $uneFicheFrais['mois'] ?></td>
                    <td><a href="index.php?uc=suivrePaiementFicheFrais&action=detailFicheValidee&idvisiteur=<?php echo $uneFicheFrais['idvisiteur']?>&mois=<?=$uneFicheFrais['mois']?>"> 
                           Fiches de frais </a></td>
                </tr>
                <?php
            }
           
            ?>
            </tbody>  
        </table>
    </div>
</div>



