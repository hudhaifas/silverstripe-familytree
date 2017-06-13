/* Before dev/build */
/* Rename related fields */
RENAME TABLE person_viewergroups TO gender_viewergroups;
RENAME TABLE person_viewermembers TO gender_viewermembers;
RENAME TABLE person_editorgroups TO gender_editorgroups;
RENAME TABLE person_editormembers TO gender_editormembers;

/* PersonlaStats */
RENAME TABLE personalstats TO genderstats;
UPDATE `genderstats` SET `ClassName` = 'GenderStats';
ALTER TABLE `genderstats` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

/* Collectable_People */
ALTER TABLE `collectable_people` CHANGE `PersonID` `GenderID` INT(11) NOT NULL DEFAULT '0';

/* After dev/build*/
/* Copy all fields from the Person entity */
INSERT INTO genealogybase(ID, ClassName, LastEdited, Created, Prefix, Name, NickName, Postfix, Note, Comments, Biography, CanViewType, CanEditType, PhotoID, StatsID)
		   SELECT ID, ClassName, LastEdited, Created, Prefix, Name, NickName, Postfix, Note, Comments, Biography, CanViewType, CanEditType, PhotoID, StatsID
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
	
/* Phase 2 */

/* Clan to Branch */
RENAME TABLE clan TO branch;
UPDATE `gender` SET `ClassName` = 'Branch' WHERE `ClassName` = 'Clan'
ALTER TABLE `branch` CHANGE `IsMainClan` `IsClan` TINYINT(1) UNSIGNED NOT NULL DEFAULT '0';

RENAME TABLE `town_townbranchs` TO `ss4test`.`town_townbranches`;
RENAME TABLE `branch_towns` TO `ss4test`.`branch_towns`;

/* Tribe to Clan*/
RENAME TABLE tribe TO clan;
UPDATE `gender` SET `ClassName` = 'Clan' WHERE `ClassName` = 'Tribe'
RENAME TABLE `ss4test`.`town_towntribes` TO `ss4test`.`town_townbranchs`;





