<?php

/*
 * MIT License
 *
 * Copyright (c) 2016 Hudhaifa Shatnawi
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Jun 10, 2017 - 10:40:13 PM
 */
class Gender
        extends DataObject
        implements SingleDataObject {

    private static $db = array(
        'Prefix' => 'Varchar(255)',
        'Name' => 'Varchar(255)',
        'NickName' => 'Varchar(255)',
        'Postfix' => 'Varchar(255)',
        // Notes
        'Note' => 'Varchar(255)',
        'Comments' => 'Text',
        // Biography
        'Biography' => 'HTMLText',
        // Indexing
        'IndexedName' => 'Text',
        'IndexedAncestors' => 'Text',
        // Order
        'YearOrder' => 'Int',
        // Permession Level
        "CanViewType" => "Enum('Anyone, LoggedInUsers, OnlyTheseUsers, Inherit', 'Inherit')",
        "CanEditType" => "Enum('LoggedInUsers, OnlyTheseUsers, Inherit', 'Inherit')",
    );
    private static $has_one = array(
        'Photo' => 'Image',
        'Stats' => 'GenderStats',
    );
    private static $has_many = array(
        // Suggestions
        'Suggestions' => 'Suggestion',
    );
    private static $many_many = array(
        "ViewerGroups" => "Group",
        "EditorGroups" => "Group",
        "ViewerMembers" => "Member",
        "EditorMembers" => "Member",
    );
    private static $defaults = array(
        "CanViewType" => "Inherit",
        "CanEditType" => "Inherit"
    );
    private static $searchable_fields = array(
        'Name' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
    );
    protected static $cache_permissions = array();
    protected static $cache_names = array();

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Photo'] = _t('Genealogist.PHOTO', 'Photo');

        $labels['AliasName'] = _t('Genealogist.NAME', 'Name');
        $labels['Prefix'] = _t('Genealogist.PREFIX', 'Prefix');
        $labels['Name'] = _t('Genealogist.NAME', 'Name');
        $labels['Postfix'] = _t('Genealogist.POSTFIX', 'Postfix');
        $labels['NickName'] = _t('Genealogist.NICKNAME', 'NickName');

        $labels['Parents'] = _t('Genealogist.PARENTS', 'Parents');
        $labels['Father'] = _t('Genealogist.FATHER', 'Father');
        $labels['Father.Name'] = _t('Genealogist.FATHER_NAME', 'Father Name');
        $labels['Mother'] = _t('Genealogist.MOTHER', 'Mother');
        $labels['Mother.Name'] = _t('Genealogist.MOTHER_NAME', 'Mother Name');

        $labels['Husband'] = _t('Genealogist.HUSBAND', 'Husband');
        $labels['Wife'] = _t('Genealogist.WIFE', 'Wife');

        $labels['ResidencePlace'] = _t('Genealogist.RESIDENCEPLACE', 'Residence Place');
        $labels['BirthDate'] = _t('Genealogist.BIRTHDATE', 'Birth Date');
        $labels['BirthPlace'] = _t('Genealogist.BIRTHPLACE', 'Birth Place');
        $labels['BirthDateEstimated'] = _t('Genealogist.BIRTHDATE_ESTIMATED', 'Birth Date Estimated');
        $labels['DeathDate'] = _t('Genealogist.DEATHDATE', 'Death Date');
        $labels['DeathPlace'] = _t('Genealogist.DEATHPLACE', 'Death Place');
        $labels['DeathDateEstimated'] = _t('Genealogist.DEATHDATE_ESTIMATED', 'Death Date Estimated');
        $labels['BurialPlace'] = _t('Genealogist.BURIALPLACE', 'Burial Place');
        $labels['Age'] = _t('Genealogist.AGE', 'Age');
        $labels['IsDead'] = _t('Genealogist.ISDEAD', 'Is Dead');
        $labels['MarriageDate'] = _t('Genealogist.MARRIAGEDATE', 'Marriage Date');

        $labels['Note'] = _t('Genealogist.NOTE', 'Note');
        $labels['Comments'] = _t('Genealogist.COMMENTS', 'Comments');

        $labels['Biography'] = _t('Genealogist.BIOGRAPHY', 'Biography');

        $labels['Clan'] = _t('Genealogist.CLAN', 'Clan');
        $labels['Corporation'] = _t('Genealogist.CORPORATION', 'Corporation');
        $labels['Corporations'] = _t('Genealogist.CORPORATIONS', 'Corporations');
        $labels['BranchTowns'] = _t('Genealogist.TOWNS', 'Towns');
        $labels['ClanTowns'] = _t('Genealogist.TOWNS', 'Towns');
        $labels['ClanTowns'] = _t('Genealogist.TOWNS', 'Towns');
        $labels['IsClan'] = _t('Genealogist.IS_CLAN', 'Is Clan');

        // Tabs
        $labels['Children'] = _t('Genealogist.CHILDREN', 'Children');
        $labels['Sons'] = _t('Genealogist.SONS', 'Sons');
        $labels['Daughters'] = _t('Genealogist.DAUGHTERS', 'Daughters');
        $labels['Husbands'] = _t('Genealogist.HUSBANDS', 'Husbands');
        $labels['Wives'] = _t('Genealogist.WIVES', 'Wives');
        $labels['Suggestions'] = _t('Genealogist.SUGGESTIONS', 'Suggestions');
        $labels['Events'] = _t('Genealogist.EVENTS', 'Events');
        $labels['RelatedEvents'] = _t('Genealogist.RELATED_EVENTS', 'Related Events');
        $labels['Collectables'] = _t('Genealogist.COLLECTABLES', 'Collectables');
        $labels['Branches'] = _t('Genealogist.BRANCHES', 'Branches');

        // Settings
        $labels['IsPublicFigure'] = _t('Genealogist.PUBLIC_FIGURE', 'Is Public Figure');
        $labels['CanViewType'] = _t('Genealogist.CAN_VIEW_TYPE', 'Who can view this person');
        $labels['CanEditType'] = _t('Genealogist.CAN_EDIT_TYPE', 'Who can edit this person');

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->removeFieldFromTab('Root.Main', 'StatsID');
        $fields->removeFieldFromTab('Root.Main', 'YearOrder');
        $fields->removeFieldFromTab('Root.Main', 'IndexedName');
        $fields->removeFieldFromTab('Root.Main', 'IndexedAncestors');

        $this->reorderField($fields, 'Photo', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Prefix', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Name', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'NickName', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Postfix', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Note', 'Root.Main', 'Root.Main');

        $this->getSettingsFields($fields);

        $biographyTab = new Tab('BiographyTab', _t('Genealogist.BIOGRAPHY', 'Biography'));
        $fields->insertAfter('Main', $biographyTab);
        $this->reorderField($fields, 'Biography', 'Root.Main', 'Root.BiographyTab');
        $this->reorderField($fields, 'Comments', 'Root.Main', 'Root.BiographyTab');

        return $fields;
    }

    public function getSettingsFields(&$fields) {
        // Prepare groups and members lists
        $groupsMap = array();
        foreach (Group::get() as $group) {
            // Listboxfield values are escaped, use ASCII char instead of &raquo;
            $groupsMap[$group->ID] = $group->getBreadcrumbs(' > ');
        }
        asort($groupsMap);

        $membersMap = array();
        foreach (Member::get() as $member) {
            // Listboxfield values are escaped, use ASCII char instead of &raquo;
            $membersMap[$member->ID] = $member->getTitle();
        }
        asort($membersMap);

        // Remove existing fields
        $fields->removeFieldFromTab('Root.ViewerGroups', 'ViewerGroups');
        $fields->removeFieldFromTab('Root', 'ViewerGroups');
        $fields->removeFieldFromTab('Root.ViewerMembers', 'ViewerMembers');
        $fields->removeFieldFromTab('Root', 'ViewerMembers');
        $fields->removeFieldFromTab('Root.EditorGroups', 'EditorGroups');
        $fields->removeFieldFromTab('Root', 'EditorGroups');
        $fields->removeFieldFromTab('Root.EditorMembers', 'EditorMembers');
        $fields->removeFieldFromTab('Root', 'EditorMembers');

        // Prepare Settings tab
        $settingsTab = new Tab('SettingsTab', _t('Genealogist.SETTINGS', 'Settings'));
        $fields->insertAfter('Main', $settingsTab);

        $this->reorderField($fields, 'IsPublicFigure', 'Root.Main', 'Root.SettingsTab');
        $this->reorderField($fields, 'CanViewType', 'Root.Main', 'Root.SettingsTab');

        $viewerGroupsField = ListboxField::create("ViewerGroups", _t('Genealogist.VIEWER_GROUPS', "Viewer Groups"))
                ->setMultiple(true)
                ->setSource($groupsMap)
                ->setAttribute('data-placeholder', _t('Genealogist.GROUP_PLACEHOLDER', 'Click to select group'));
        $fields->addFieldToTab('Root.SettingsTab', $viewerGroupsField);

        $viewerMembersField = ListboxField::create("ViewerMembers", _t('Genealogist.VIEWER_MEMBERS', "Viewer Users"))
                ->setMultiple(true)
                ->setSource($membersMap)
                ->setAttribute('data-placeholder', _t('Genealogist.MEMBER_PLACEHOLDER', 'Click to select user'));
        $fields->addFieldToTab('Root.SettingsTab', $viewerMembersField);


        $this->reorderField($fields, 'CanEditType', 'Root.Main', 'Root.SettingsTab');

        $editorGroupsField = ListboxField::create("EditorGroups", _t('Genealogist.EDITOR_GROUPS', "Editor Groups"))
                ->setMultiple(true)
                ->setSource($groupsMap)
                ->setAttribute('data-placeholder', _t('Genealogist.GROUP_PLACEHOLDER', 'Click to select group'));
        $fields->addFieldToTab('Root.SettingsTab', $editorGroupsField);

        $editorMembersField = ListboxField::create("EditorMembers", _t('Genealogist.EDITOR_MEMBERS', "Editor Users"))
                ->setMultiple(true)
                ->setSource($membersMap)
                ->setAttribute('data-placeholder', _t('Genealogist.MEMBER_PLACEHOLDER', 'Click to select user'));
        $fields->addFieldToTab('Root.SettingsTab', $editorMembersField);
    }

    protected function personConfigs($showFather = false, $showMother = true, $allowCreate = true, $married = false) {
        $config = GridFieldConfig::create();
        $config->addComponent(new GridFieldPaginator(25));
        $config->addComponent(new GridFieldButtonRow('before'));
        $config->addComponent(new GridFieldToolbarHeader());
        $config->addComponent(new GridFieldTitleHeader());
        $config->addComponent(new GridFieldFilterHeader());
        if ($allowCreate) {
            $config->addComponent(new GridFieldAddNewInlineButton());
        }
        $config->addComponent(new GridFieldAddExistingAutocompleter('buttons-before-right', array('IndexedName', 'Name')));
        $config->addComponent(new GridFieldDetailForm());
//        $config->addComponent(new GridFieldAddNewMultiClass());
//        $config->addComponent(new GridFieldAddNewButton());

        $columns = array();
        $columns['Name'] = array(
            'title' => _t('Genealogist.NAME', 'Name'),
            'field' => 'TextField'
        );
        $columns['NickName'] = array(
            'title' => _t('Genealogist.NICKNAME', 'NickName'),
            'field' => 'TextField'
        );
        $columns['IsDead'] = array(
            'title' => _t('Genealogist.ISDEAD', 'Is Dead'),
            'field' => 'CheckboxField'
        );

        if ($showFather) {
            $columns['Parents'] = array(
                'title' => _t('Genealogist.FATHER_NAME', 'Father Name'),
                'callback' => function($record, $column, $grid) {
                    $field = ReadonlyField::create($column);
                    $father = $record->getParents();
                    $field->setValue($father);
                    return $field;
                }
            );
        }

        if ($showMother) {
            $columns['MotherID'] = array(
                'title' => _t('Genealogist.MOTHER_NAME', 'Mother Name'),
                'callback' => function($record, $column, $grid) {
                    if ($record->Father()->exists()) {
                        $mothers = $record->Father()->Wives()->map();
                        return DropdownField::create($column)
                                        ->setSource($mothers)
                                        ->setValue($record->MotherID)
                                        ->setEmptyString(_t('Genealogist.CHOOSE_MOTHER', 'Choose Mother'));
                    }

                    return ReadonlyField::create($column);
                }
            );
        }

        $columns['BirthDate'] = array(
            'title' => _t('Genealogist.BIRTHDATE', 'Birth Date'),
            'callback' => function($record, $column, $grid) {
                $field = DateField::create($column);
                $field->setConfig('showcalendar', true);
                $field->setConfig('dateformat', 'dd-MM-yyyy');
                return $field;
            }
        );
        $columns['BirthDateEstimated'] = array(
            'field' => 'CheckboxField'
        );
        $columns['DeathDate'] = array(
            'title' => _t('Genealogist.DEATHDATE', 'Death Date'),
            'callback' => function($record, $column, $grid) {
                $field = DateField::create($column);
                $field->setConfig('showcalendar', true);
                $field->setConfig('dateformat', 'dd-MM-yyyy');
                return $field;
            }
        );
        $columns['DeathDateEstimated'] = array(
            'field' => 'CheckboxField'
        );
        $columns['Note'] = array(
            'title' => _t('Genealogist.NOTE', 'Note'),
            'field' => 'TextField'
        );
        if ($married) {
            $columns['MarriageDate'] = array(
                'title' => _t('Genealogist.MARRIAGEDATE', 'Marriage Date'),
                'callback' => function($record, $column, $grid) {
                    $field = DateField::create($column);
                    $field->setConfig('showcalendar', true);
                    $field->setConfig('dateformat', 'dd-MM-yyyy');
                    return $field;
                }
            );
        }

        $edit = new GridFieldEditableColumns();
        $edit->setDisplayFields($columns);

        $config->addComponent($edit);

        $config->addComponent(new GridFieldEditButton());
        $config->addComponent(new GridFieldDeleteAction(true));

        return $config;
    }

    /// Permissions ///
    public function canCreate($member = null) {
        return false;
    }

    public function canView($member = false) {
        if (!$member) {
            $member = Member::currentUserID();
        }

        if ($member && is_numeric($member)) {
            $member = DataObject::get_by_id('Member', $member);
        }

        $cachedPermission = self::cache_permission_check('view', $member, $this->ID);
        if (isset($cachedPermission)) {
            return $cachedPermission;
        }

        if ($this->canEdit($member)) {
            return self::cache_permission_check('view', $member, $this->ID, true);
        }

        $extended = $this->extendedCan('canViewPersons', $member);
        if ($extended !== null) {
            return self::cache_permission_check('view', $member, $this->ID, $extended);
        }

        if (!$this->CanViewType || $this->CanViewType == 'Anyone') {
            return self::cache_permission_check('view', $member, $this->ID, true);
        }

        // check for inherit
        if ($this->CanViewType == 'Inherit') {
            if ($this->FatherID && !$this->Father()->isBranch()) {
                return self::cache_permission_check('view', $member, $this->ID, $this->Father()->canView($member));
            }
        }

        // check for any logged-in users
        if ($this->CanViewType === 'LoggedInUsers' && $member) {
            return self::cache_permission_check('view', $member, $this->ID, true);
        }

        // check for specific groups && users
        if ($this->CanViewType === 'OnlyTheseUsers' && $member && ($member->inGroups($this->ViewerGroups()) || $this->ViewerMembers()->byID($member->ID))) {
            return self::cache_permission_check('view', $member, $this->ID, true);
        }

        return self::cache_permission_check('view', $member, $this->ID, false);
    }

    public function canDelete($member = false) {
        if (!$member) {
            $member = Member::currentUserID();
        }

        if ($member && is_numeric($member)) {
            $member = DataObject::get_by_id('Member', $member);
        }

        $cachedPermission = self::cache_permission_check('delete', $member, $this->ID);
        if (isset($cachedPermission)) {
            return $cachedPermission;
        }

        if ($member && Permission::checkMember($member, "ADMIN")) {
            return true;
        }

        $extended = $this->extendedCan('canDeletePersons', $member);
        if ($extended !== null) {
            return $extended;
        }

        return false;
    }

    public function canEdit($member = false) {
        if (!$member) {
            $member = Member::currentUserID();
        }

        if ($member && is_numeric($member)) {
            $member = DataObject::get_by_id('Member', $member);
        }

        $cachedPermission = self::cache_permission_check('edit', $member, $this->ID);
        if (isset($cachedPermission)) {
            return $cachedPermission;
        }

        if ($member && Permission::checkMember($member, "ADMIN")) {
            return self::cache_permission_check('edit', $member, $this->ID, true);
        }

        if ($member && $this->hasMethod('CreatedBy') && $member == $this->CreatedBy()) {
            return self::cache_permission_check('edit', $member, $this->ID, true);
        }

        $extended = $this->extendedCan('canEditPersons', $member);
        if ($extended !== null) {
            return self::cache_permission_check('edit', $member, $this->ID, $extended);
        }

        // check for inherit
        if ($this->CanEditType == 'Inherit') {
            if ($this->FatherID) {
                return self::cache_permission_check('edit', $member, $this->ID, $this->Father()->canEdit($member));
            }
        }

        // check for any logged-in users with CMS access
        if ($this->CanEditType === 'LoggedInUsers' && Permission::checkMember($member, $this->config()->required_permission)) {
            return self::cache_permission_check('edit', $member, $this->ID, true);
        }

        // check for specific groups
        if ($this->CanEditType === 'OnlyTheseUsers' && $member && ($member->inGroups($this->EditorGroups()) || $this->EditorMembers()->byID($member->ID))) {
            return self::cache_permission_check('edit', $member, $this->ID, true);
        }

        return self::cache_permission_check('edit', $member, $this->ID, false);
    }

    public function canViewSons($member = false) {
        if (!$member) {
            $member = Member::currentUserID();
        }

        if (!$member || !$this->canView()) {
            return false;
        }

        $flag = $this->canView();
        foreach ($this->Sons() as $son) {
            $flag = $flag || $son->canView();
        }
        return $flag;
    }

    public function canViewDaughters($member = false) {
        if (!$member) {
            $member = Member::currentUserID();
        }

        if (!$member || !$this->canView()) {
            return false;
        }

        $flag = $this->canView();
        foreach ($this->Daughters() as $daughter) {
            $flag = $flag || $daughter->canView();
        }
        return $flag;
    }

    public function ViewableSons() {
        $count = 0;
        foreach ($this->Sons() as $son) {
            $count += $son->canView() ? 1 : 0;
        }
        return $count;
    }

    public function ViewableDaughters() {
        $count = 0;
        foreach ($this->Daughters() as $daughter) {
            $count += $daughter->canView() ? 1 : 0;
        }
        return $count;
    }

    protected function onBeforeWrite() {
        parent::onBeforeWrite();

        $this->trim('Name');
        $this->trim('NickName');
    }

    // Cache
    public static function cache_permission_check($typeField, $member, $personID, $result = null) {
        if (!$member) {
            $member = Member::currentUserID();
        }

        if ($member && is_numeric($member)) {
            $member = DataObject::get_by_id('Member', $member);
        }

        $memberID = $member ? $member->ID : '?';

        // This is the name used on the permission cache
        // converts something like 'CanEditType' to 'edit'.
        $cacheKey = strtolower($typeField) . "-$memberID-$personID";

        if (isset(self::$cache_permissions[$cacheKey])) {
            $cachedValues = self::$cache_permissions[$cacheKey];
            return $cachedValues;
        }

        self::$cache_permissions[$cacheKey] = $result;

        return self::$cache_permissions[$cacheKey];
    }

    public static function cache_name_check($nameType, $personID, $result = null) {
        // This is the name used on the permission cache
        // converts something like 'CanEditType' to 'edit'.
        $cacheKey = strtolower($nameType) . "-$personID";

        if (isset(self::$cache_names[$cacheKey])) {
            $cachedValues = self::$cache_names[$cacheKey];
            return $cachedValues;
        }

        self::$cache_names[$cacheKey] = $result;

        return self::$cache_names[$cacheKey];
    }

    /// Links ///
    /**
     * Return the link for this {@link Person} object
     * @param string $action Optional controller action (method).
     * @return string
     */
    private function personLink($action = null) {
        return GenealogyPage::get()->first()->Link($action);
    }

    function Link($action = null) {
        return Director::get_current_page()->Link($action);
    }

    function InfoLink($action = null) {
        return $this->personLink("person-info/$this->ID");
    }

    function SuggestLink($action = null) {
        return $this->personLink("suggest/$this->ID");
    }

    function EditLink($action = null) {
        return FiguresPage::get()->first()->Link("edit/$this->ID/$action");
    }

    function TreeLink($action = null) {
        return $this->personLink("$this->ID");
    }

    function ShowLink($action = null) {
        return FiguresPage::get()->first()->Link("show/$this->ID");
//        return $this->personLink("show/$this->ID");
    }

    /// Names ///
    public function getTitle() {
        $cachedName = self::cache_name_check('title-name', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $name = $this->getFullName();

        return self::cache_name_check('title-name', $this->ID, $name);
    }

    public function getFirstName() {
        $cachedName = self::cache_name_check('first-name', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $name = $this->Name;
        return self::cache_name_check('first-name', $this->ID, $name);
    }

    /**
     * Returns the formated person's name
     * @return strnig
     */
    public function getPersonName() {
        $cachedName = self::cache_name_check('person-name', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $name = $this->getFirstName();

        if ($this->NickName) {
            $name .= " ({$this->NickName})";
        }

        return self::cache_name_check('person-name', $this->ID, $name);
    }

    /**
     * Returns the person's name and nickname
     * @return string
     */
    public function getAliasName() {
        $cachedName = self::cache_name_check('alias-name', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $name = '';

        if ($this->Prefix) {
            $name .= "{$this->Prefix} ";
        }
        $name .= $this->getFirstName();

        if ($this->NickName) {
            $name .= " ({$this->NickName})";
        }

        if ($this->Postfix) {
            $name .= " {$this->Postfix}";
        }

        return self::cache_name_check('alias-name', $this->ID, $name);
    }

    /**
     * Returns the person's full name
     * @return string
     */
    public function getFullName($withChildOf = true) {
        $cachedName = self::cache_name_check('full-name-' . $withChildOf, $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $name = $this->getPersonName();

        return self::cache_name_check('full-name-' . $withChildOf, $this->ID, $name);
    }

    /**
     * Returns the person's brief name
     * @return string
     */
    public function getBriefName() {
        $cachedName = self::cache_name_check('brief-name', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $name = $this->getPersonName();

        return self::cache_name_check('brief-name', $this->ID, $name);
    }

    /**
     * Returns the person's short name
     * @return string
     */
    public function getShortName() {
        $cachedName = self::cache_name_check('short-name', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $name = $this->getPersonName();

        return self::cache_name_check('short-name', $this->ID, $name);
    }

    /**
     * Returns the person's full name
     * @return string
     */
    public function toIndexName() {
        $cachedName = self::cache_name_check('indexed-name', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $name = $this->Name;

        return self::cache_name_check('indexed-name', $this->ID, $name);
    }

    public function ThumbPhoto() {
        return $this->Photo()->CMSThumbnail();
    }

    // Types
    /**
     * Checks if this person is male
     * @return boolean
     */
    public function isMale() {
        return $this instanceof Male;
    }

    /**
     * Checks if this person is female
     * @return boolean
     */
    public function isFemale() {
        return $this instanceof Female;
    }

    /**
     * Checks if this person is a branch
     * @return boolean
     */
    public function isBranch() {
        return $this instanceof Branch;
    }

    /**
     * Checks if this person is a clan
     * @return boolean
     */
    public function isClan() {
        return $this instanceof Clan;
    }

    // Descendants
    public function getAllDescendants() {
        return GenealogistHelper::get_all_descendants($this);
    }

    public function getDescendantsPublicFigures() {
        return GenealogistHelper::get_descendants_public_figures($this);
    }

    /// Counters ///
    /**
     * Counts the of all descendants
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public function DescendantsCount($state = 0) {
        if ($this->Stats()->exists()) {
            return $this->MalesCount($state) + $this->FemalesCount($state);
        }
        return GenealogistCountersHelper::count_descendants($this, $state);
    }

    /**
     * Counts the of all male descendants
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public function MalesCount($state = 0) {
        if ($this->Stats()->exists()) {
            return $state ? $this->Stats()->LiveMales : $this->Stats()->Males;
        }
        return GenealogistCountersHelper::count_males($this, $state);
    }

    /**
     * Counts the of all female descendants
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public function FemalesCount($state = 0) {
        if ($this->Stats()->exists()) {
            return $state ? $this->Stats()->LiveFemales : $this->Stats()->Females;
        }
        return GenealogistCountersHelper::count_females($this, $state);
    }

    /**
     * Counts the of sons
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public function SonsCount($state = 0) {
        if ($this->Stats()->exists()) {
            return $state ? $this->Stats()->LiveSons : $this->Stats()->Sons;
        }
        return GenealogistCountersHelper::count_sons($this, $state);
    }

    /**
     * Counts the of daughters
     * @param int $state either 1/$STATE_ALIVE or 2/$STATE_DEAD or 0
     * @return number
     */
    public function DaughtersCount($state = 0) {
        if ($this->Stats()->exists()) {
            return $state ? $this->Stats()->LiveDaughters : $this->Stats()->Daughters;
        }
        return GenealogistCountersHelper::count_daughters($this, $state);
    }

    /// Utils ///
    function reorderField($fields, $name, $fromTab, $toTab, $disabled = false) {
        $field = $fields->fieldByName($fromTab . '.' . $name);

        if ($field) {
            $fields->removeFieldFromTab($fromTab, $name);
            $fields->addFieldToTab($toTab, $field);

            if ($disabled) {
                $field = $field->performDisabledTransformation();
            }
        }

        return $field;
    }

    function removeField($fields, $name, $fromTab) {
        $field = $fields->fieldByName($fromTab . '.' . $name);

        if ($field) {
            $fields->removeFieldFromTab($fromTab, $name);
        }

        return $field;
    }

    function trim($field) {
        if ($this->$field) {
            $this->$field = trim($this->$field);
        }
    }

    public function toString() {
        return $this->getTitle();
    }

    /// UI ///
    public function CSSClasses($stopAtClass = 'DataObject') {
        $classes = strtolower(parent::CSSClasses($stopAtClass));

        $classes .= $this->IsDead ? ' dead' : '';

        return $classes;
    }

    public function CSSBirth() {
        $year = null;
        if ($this->BirthDate) {
            $date = new Date();
            $date->setValue($this->BirthDate);

            $year = $date->Year();
        } else if ($this->Stats()->exists()) {
            $year = $this->Stats()->MinYear;
        }

        return $year == 0 ? null : $year;
    }

    public function CSSDeath() {
        $year = null;
        if ($this->DeathDate) {
            $date = new Date();
            $date->setValue($this->DeathDate);

            $year = $date->Year();
        } else if ($this->Stats()->exists()) {
            $year = $this->Stats()->MaxYear;
        }

        return $year == 0 ? null : $year;
    }

    public function getAliasSummary() {
        return $this->renderWith('Person_Alias');
    }

    public function getDescendants() {
        if (filter_input(INPUT_GET, 'ancestral') == 1) {
            return $this->getAncestors();
        }

        return $this->renderWith('Person_Node_Descendants');
    }

    private function getAncestors() {
        return $this->renderWith('Person_Node_Ancestors');
    }

    public function getDescendantsLeaves() {
        $default1 = array('options' => array('default' => 1));

        $males = filter_input(INPUT_GET, 'm', FILTER_VALIDATE_INT, $default1);
        $malesSeed = filter_input(INPUT_GET, 'ms', FILTER_VALIDATE_INT, $default1);

        $html = '';

        if ($males && !$malesSeed) {
            foreach ($this->Sons() as $child) {
                $html .= $child->getSelfLeaf();
            }
        } else if ($males && $malesSeed) {
            foreach ($this->Sons() as $child) {
                $html .= $child->getDescendants();
            }
        }

        // Do NOT show daughters if no permission
//        if (!$this->canView()) {
//            return $html;
//        }

        $default0 = array('options' => array('default' => 0));
        $females = filter_input(INPUT_GET, 'f', FILTER_VALIDATE_INT, $default0);
        $femalesSeed = filter_input(INPUT_GET, 'fs', FILTER_VALIDATE_INT, $default0);

        if ($females && !$femalesSeed) {
            foreach ($this->Daughters() as $child) {
                $html .= $child->getSelfLeaf();
            }
        } else if ($females && $femalesSeed) {
            foreach ($this->Daughters() as $child) {
                $html .= $child->getDescendants();
            }
        }

        return $html;
    }

    public function AncestorsLeaves() {
        $html = '';

        $father = $this->Father();
        if ($father && $father->exists()) {
            $html .= $father->getAncestors();
        }

        if ($this->isMale()) {
            $clan = $this->Clan();
            if ($clan && $clan->exists()) {
                $html .= $clan->getAncestors();
            }
        }

        $mother = $this->Mother();
        if ($mother && $mother->exists()) {
            $html .= $mother->getAncestors();
        }

        return $html;
    }

    private function getSelfLeaf() {
        return $this->renderWith('Person_Node_Single');
    }

    public function isMalesOnly() {
        return !$this->canView() && $this->isFemale();
    }

    /// JSON for future work
    public function toJSON() {
        $js = $this->buildJSON();
//        var_dump($js);

        return json_encode($js, JSON_UNESCAPED_UNICODE);
    }

    private function buildJSON() {
        $person = array();
        $person['name'] = $this->getPersonName();
        $person['title'] = $this->getAliasName();

        if ($this->Children()->exists()) {
            $person['children'] = array();

            foreach ($this->Children() as $child) {
                $person['children'][] = $child->toJSON();
            }
        }

        return $person;
    }

    public function __debugInfo() {
        return array(
            $this->ID . ' : ' . $this->getFirstName()
        );
    }

    // SingleDataObject 
    public function getObjectSummary() {
        return $this->renderWith('Person_Summary');
    }

    public function getObjectImage() {
        return $this->Photo();
    }

    public function getObjectDefaultImage() {
        return "genealogist/images/default-person.png";
    }

    public function getObjectLink() {
        return FiguresPage::get()->first()->Link("show/$this->ID");
    }

    public function getObjectRelated() {
//        return DataObject::get('Person', "`PublicFigure` = 1 OR `ClassName` = 'Branch'")->sort('RAND()');
        return null;
    }

    public function isObjectDisabled() {
        return !$this->canView() && !$this->IsPublicFigure;
    }

    public function getObjectTabs() {
        $lists = array();

        if ($this->Biography) {
            $lists[] = array(
                'Title' => _t('Genealogist.BIOGRAPHY', 'Biography'),
                'Content' => $this->Biography
            );
        }

        if ($this->canEdit() && $this->Suggestions()->Count()) {
            $lists[] = array(
                'Title' => _t('Genealogist.SUGGESTIONS', 'Suggestions'),
                'Content' => $this->renderWith('Person_Suggestions')
            );
        }

        $this->extend('extraTabs', $lists);

        return new ArrayList($lists);
    }

    public function getObjectTitle() {
        return $this->getTitle();
    }

}
