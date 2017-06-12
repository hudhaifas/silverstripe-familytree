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

