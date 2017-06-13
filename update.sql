/* Update _config.yml */
-- Collectable:
--   extensions:
--     - GenderExtension
--     - TownExtension
--       
-- Person:
--   extensions:
--     - CollectableExtension

/* Before dev/build */
/* Rename related fields */
RENAME TABLE person_viewergroups TO gender_viewergroups;
ALTER TABLE `gender_viewergroups` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

RENAME TABLE person_viewermembers TO gender_viewermembers;
ALTER TABLE `gender_viewermembers` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

RENAME TABLE person_editorgroups TO gender_editorgroups;
ALTER TABLE `gender_editorgroups` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

RENAME TABLE person_editormembers TO gender_editormembers;
ALTER TABLE `gender_editormembers` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

RENAME TABLE `Town_TownClans` TO `Town_TownBranches`;
ALTER TABLE `Town_TownBranches` CHANGE `ClanID` `BranchID` INT(11) NOT NULL DEFAULT '0';

/* PersonlaStats */
RENAME TABLE PersonalStats TO GenderStats;
ALTER TABLE `GenderStats` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

/* Collectable_People */
ALTER TABLE `Collectable_People` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `Male` CHANGE `TribeID` `ClanID` INT(11) NOT NULL DEFAULT '0';

/* Clan to Branch */
RENAME TABLE Clan TO Branch;
ALTER TABLE `Branch` CHANGE `IsMainClan` `IsClan` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';

/* Tribe to Clan*/
RENAME TABLE Tribe TO Clan;
RENAME TABLE `Town_TownTribes` TO `Town_TownBranchs`;

/* ****************************************************************************************** */
/* ****************************************************************************************** */
/* ****************************************************************************************** */
/* ****************************************************************************************** */
/* After dev/build*/
/* Copy all fields from the Person entity */
INSERT INTO Gender(ID, ClassName, LastEdited, Created, Prefix, Name, NickName, Postfix, Note, Comments, Biography, CanViewType, CanEditType, PhotoID, StatsID, IndexedName, IndexedAncestors, YearOrder)
		   SELECT ID, ClassName, LastEdited, Created, Prefix, Name, NickName, Postfix, Note, Comments, Biography, CanViewType, CanEditType, PhotoID, StatsID, IndexedName, IndexedAncestors, YearOrder
FROM Person

/* Delete duplicated fields */
ALTER TABLE Person
	DROP COLUMN Prefix,
	DROP COLUMN Name,
	DROP COLUMN NickName,
	DROP COLUMN Postfix,
	DROP COLUMN Note,
	DROP COLUMN Comments,
	DROP COLUMN Biography,
	DROP COLUMN CanViewType,
	DROP COLUMN CanEditType,
	DROP COLUMN PhotoID,
	DROP COLUMN StatsID,
	DROP COLUMN IndexedName,
	DROP COLUMN IndexedAncestors,
	DROP COLUMN YearOrder;
	
UPDATE `Gender` SET `ClassName` = 'Branch' WHERE `ClassName` = 'Clan'
UPDATE `Gender` SET `ClassName` = 'Clan' WHERE `ClassName` = 'Tribe'
UPDATE `GenderStats` SET `ClassName` = 'GenderStats';
