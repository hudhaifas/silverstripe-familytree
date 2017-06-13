/* Before dev/build */
/* Rename related fields */
RENAME TABLE person_viewergroups TO gender_viewergroups;
RENAME TABLE person_viewermembers TO gender_viewermembers;

RENAME TABLE person_editorgroups TO gender_editorgroups;
ALTER TABLE `gender_editorgroups` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

RENAME TABLE person_editormembers TO gender_editormembers;
ALTER TABLE `gender_editormembers` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

RENAME TABLE `town_townclans` TO `town_townbranches`;
ALTER TABLE `town_townbranches` CHANGE `ClanID` `BranchID` INT(11) NOT NULL DEFAULT '0';

/* PersonlaStats */
RENAME TABLE personalstats TO genderstats;
ALTER TABLE `genderstats` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

/* Collectable_People */
ALTER TABLE `collectable_people` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

ALTER TABLE `male` CHANGE `TribeID` `ClanID` INT(11) NOT NULL DEFAULT '0';

/* Clan to Branch */
RENAME TABLE clan TO branch;
ALTER TABLE `branch` CHANGE `IsMainClan` `IsClan` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';

/* Tribe to Clan*/
RENAME TABLE tribe TO clan;
RENAME TABLE `town_towntribes` TO `town_townbranchs`;

ALTER TABLE `genderstats` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';


/* ****************************************************************************************** */
/* After dev/build*/
/* Copy all fields from the Person entity */
INSERT INTO gender(ID, ClassName, LastEdited, Created, Prefix, Name, NickName, Postfix, Note, Comments, Biography, CanViewType, CanEditType, PhotoID, StatsID, IndexedName, IndexedAncestors)
		   SELECT ID, ClassName, LastEdited, Created, Prefix, Name, NickName, Postfix, Note, Comments, Biography, CanViewType, CanEditType, PhotoID, StatsID, IndexedName, IndexedAncestors
FROM person

/* Delete duplicated fields */
ALTER TABLE person
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
	DROP COLUMN StatsID;
	DROP COLUMN IndexedName,
	DROP COLUMN IndexedAncestors;
	
UPDATE `gender` SET `ClassName` = 'Branch' WHERE `ClassName` = 'Clan'
UPDATE `gender` SET `ClassName` = 'Clan' WHERE `ClassName` = 'Tribe'
UPDATE `genderstats` SET `ClassName` = 'GenderStats';
