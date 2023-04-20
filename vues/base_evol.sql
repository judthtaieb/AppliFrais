--creation de la table type pour l utilisateur
CREATE TABLE `gsb_frais`.`type`
 ( `id` INT NOT NULL AUTO_INCREMENT , `libelle` VARCHAR(20) NOT NULL , PRIMARY KEY (`id`)) 
 ENGINE = MyISAM;

 --insertion dans la tble type des visteurs ou comptable
 INSERT INTO `type` (`id`, `libelle`) VALUES (NULL, 'visiteur');
 
 --ajout dans la table visiteur le champ type qui sera en reference de la table type
 ALTER TABLE `visiteur` ADD `type` INT NOT NULL AFTER `type`;

 --modifier le champ type a 1 cad visiteur
UPDATE visiteur SET type=1;

 --mettre la cle etrangere
ALTER TABLE `visiteur` ADD FOREIGN KEY (`type`) REFERENCES `type`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;//

 --inserer de nouveaux comptables
 INSERT INTO `visiteur` (`id`, `nom`, `prenom`, `login`, `mdp`, `adresse`, `cp`, `ville`, `dateembauche`, `type`) 
 VALUES ('c423', 'Dupont', 'Axel', 'adupont', '12345', '45', '93320', 'creteil', '2022-12-15', '2');

 --rechercher les comptables cad de type 2
 SELECT * FROM `visiteur` WHERE `type` = 2

 --script sql update
 UPDATE fichefrais SET idetat = 'CL' WHERE idetat ='CR'