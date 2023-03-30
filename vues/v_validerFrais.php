
<h2>Fiches de frais visiteurs </h2>

<h3>Sélectionner un mois : </h3>
<form action="index.php?uc=validerFrais&action=detailFiche" method="post" role="form">
    <div class="form-group">
        <select name="mois" class="form-select" aria-label="Default select example">
        <?php foreach ($moisVisiteur as $unMoisVisiteur) { ?>
            <option value="<?php echo $unMoisVisiteur['annee'].$unMoisVisiteur['mois'] ?>"> <?php echo $unMoisVisiteur['mois'].'/'.$unMoisVisiteur['annee'] ?></option>
            <?php
            }
            ?>
           
        </select>
    </div>


<h3>Sélectionner un visiteur: </h3>

    <div class="form-group">
        <select name="visiteur"class="form-select" aria-label="Default select example">
        <?php foreach ($visiteur as $unVisiteur) { ?>
                <option value="<?php echo $unVisiteur['id'] ?>"> <?php echo $unVisiteur['nom']."  ".$unVisiteur['prenom'] ?></option>
            <?php
            }
            ?>
            </option>
        </select>
    </div>
   



<input id="ex" type="submit" value="Executer" class="btn btn-primary" role="button">
</form>

