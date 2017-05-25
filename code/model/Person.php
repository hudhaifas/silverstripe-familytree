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
 * This class presents every person in the genealogy tree.
 * 
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Nov 2, 2016 - 10:59:52 AM
 */
class Person
        extends DataObject
        implements SingleDataObject {

    private static $db = array(
        'Prefix' => 'Varchar(255)',
        'Name' => 'Varchar(255)',
        'NickName' => 'Varchar(255)',
        'Postfix' => 'Varchar(255)',
        // Birth
        'BirthDate' => 'Date',
//        'BirthPlace' => 'Varchar(255)',
        'BirthDateEstimated' => 'Boolean',
        'DeathDate' => 'Date',
//        'DeathPlace' => 'Varchar(255)',
        'DeathDateEstimated' => 'Boolean',
        'IsDead' => 'Boolean',
        // Notes
        'Note' => 'Varchar(255)',
        'Comments' => 'Text',
        // Biography
        'Biography' => 'HTMLText',
        // Indexing
        'IndexedName' => 'Text',
        'IndexedAncestors' => 'Text',
        // Order
        'ChildOrder' => 'Int',
        // Permession Level
        "CanViewType" => "Enum('Anyone, LoggedInUsers, OnlyTheseUsers, Inherit', 'Inherit')",
        "CanEditType" => "Enum('LoggedInUsers, OnlyTheseUsers, Inherit', 'Inherit')",
    );
    private static $has_one = array(
        'Photo' => 'Image',
        'Father' => 'Male',
        'Mother' => 'Female',
        'Stats' => 'PersonalStats',
        'BirthPlace' => 'Town',
        'DeathPlace' => 'Town',
        'BurialPlace' => 'Town',
    );
    private static $has_many = array(
        'Sons' => 'Male',
        'Daughters' => 'Female',
        'Events' => 'PersonalEvent.Person',
        'RelatedEvents' => 'PersonalEvent.RelatedPerson',
        'Suggestions' => 'Suggestion',
    );
    private static $many_many = array(
        "ViewerGroups" => "Group",
        "EditorGroups" => "Group",
        "ViewerMembers" => "Member",
        "EditorMembers" => "Member",
    );
    private static $belongs_many_many = array(
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
    private static $summary_fields = array(
        'AliasName',
        'Parents',
        'Mother.Name',
        'BirthDate',
        'Age',
        'Note',
    );
    private static $default_sort = 'ChildOrder';
    public static $STATE_ALIVE = 1;
    public static $STATE_DEAD = 2;
    private static $cache_permissions = array();

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

        $labels['BirthDate'] = _t('Genealogist.BIRTHDATE', 'Birth Date');
        $labels['BirthPlace'] = _t('Genealogist.BIRTHPLACE', 'Birth Place');
        $labels['BirthDateEstimated'] = _t('Genealogist.BIRTHDATE_ESTIMATED', 'Birth Date Estimated');
        $labels['DeathDate'] = _t('Genealogist.DEATHDATE', 'Death Date');
        $labels['DeathPlace'] = _t('Genealogist.DEATHPLACE', 'Death Place');
        $labels['DeathDateEstimated'] = _t('Genealogist.DEATHDATE_ESTIMATED', 'Death Date Estimated');
        $labels['BurialPlace'] = _t('Genealogist.BURIALPLACE', 'Burial Place');
        $labels['Age'] = _t('Genealogist.AGE', 'Age');
        $labels['IsDead'] = _t('Genealogist.ISDEAD', 'Is Dead');

        $labels['Note'] = _t('Genealogist.NOTE', 'Note');
        $labels['Comments'] = _t('Genealogist.COMMENTS', 'Comments');

        $labels['Biography'] = _t('Genealogist.BIOGRAPHY', 'Biography');

        $labels['Tribe'] = _t('Genealogist.TRIBE', 'Tribe');

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
        $labels['Clans'] = _t('Genealogist.CLANS', 'Clans');

        // Settings
        $labels['CanViewType'] = _t('Genealogist.CAN_VIEW_TYPE', 'Who can view this person');
        $labels['CanEditType'] = _t('Genealogist.CAN_EDIT_TYPE', 'Who can edit this person');

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->removeFieldFromTab('Root.Main', 'ParentID');
        $fields->removeFieldFromTab('Root.Main', 'IndexedName');
        $fields->removeFieldFromTab('Root.Main', 'StatsID');
        $fields->removeFieldFromTab('Root.Main', 'ChildOrder');
        $fields->removeFieldFromTab('Root.Main', 'IndexedAncestors');

        $fields->removeFieldFromTab('Root.Main', 'FatherID');
        $fields->addFieldsToTab('Root.Main', array(
            AutoPersonField::create(
                    'FatherID', //
                    _t('Genealogist.FATHER', 'Father'), //
                    '', //
                    null, //
                    null, //
                    'Male', //
                    array('IndexedName', 'Name', 'NickName') //
            )
        ));

        $fields->removeFieldFromTab('Root.Main', 'MotherID');
        $mothers = $this->Father()->Wives()->map();
        $fields->addFieldsToTab('Root.Main', array(
                    DropdownField::create(
                            'MotherID', //
                            _t('Genealogist.MOTHER', 'Mother') //
                    )
                    ->setSource($mothers)
                    ->setValue($this->MotherID)
                    ->setEmptyString(_t('Genealogist.CHOOSE_MOTHER', 'Choose Mother'))
        ));

        $this->reorderField($fields, 'Photo', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Prefix', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Name', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'NickName', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Postfix', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Note', 'Root.Main', 'Root.Main');

        $this->getCMSEvents($fields);
        $this->getSettingsFields($fields);

        $this->reorderField($fields, 'TribeID', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'FatherID', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'MotherID', 'Root.Main', 'Root.Main');

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

    protected function getCMSEvents(&$fields) {
        if ($field = $fields->fieldByName('Root.Main.BirthDate')) {
            $field->setConfig('showcalendar', true);
            $field->setConfig('dateformat', 'dd-MM-yyyy');
        }

        if ($field = $fields->fieldByName('Root.Main.DeathDate')) {
            $field->setConfig('showcalendar', true);
            $field->setConfig('dateformat', 'dd-MM-yyyy');
        }

        if ($field = $fields->fieldByName('Root.Main.Photo')) {
            $field->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
            $field->setFolderName("genealogist/photos");
        }

        $datesTab = new Tab('DatesTab', _t('Genealogist.EVENTS', 'Events'));
        $fields->insertAfter('Main', $datesTab);
        $this->reorderField($fields, 'BirthDate', 'Root.Main', 'Root.DatesTab');
        $this->reorderField($fields, 'BirthPlaceID', 'Root.Main', 'Root.DatesTab');
        $this->reorderField($fields, 'BirthDateEstimated', 'Root.Main', 'Root.DatesTab');
        $this->reorderField($fields, 'DeathDate', 'Root.Main', 'Root.DatesTab');
        $this->reorderField($fields, 'DeathPlaceID', 'Root.Main', 'Root.DatesTab');
        $this->reorderField($fields, 'DeathDateEstimated', 'Root.Main', 'Root.DatesTab');
        $this->reorderField($fields, 'IsDead', 'Root.Main', 'Root.DatesTab');
        $this->reorderField($fields, 'BurialPlaceID', 'Root.Main', 'Root.DatesTab');
        $fields->addFieldsToTab('Root.DatesTab', array(
            ReadonlyField::create('Age', _t('Genealogist.AGE', 'Age'), $this->getAge())
        ));

        if ($events = $fields->fieldByName('Root.Events.Events')) {
            $fields->removeFieldFromTab('Root.Events', 'Events');
            $fields->removeFieldFromTab('Root', 'Events');
            $fields->addFieldToTab('Root.DatesTab', ToggleCompositeField::create(
                            'EventsComposite', //
                            _t('Genealogist.EVENTS', 'Events'), //
                            $events
                    )
            );
        }

        if ($relatedEvents = $fields->fieldByName('Root.RelatedEvents.RelatedEvents')) {
            $fields->removeFieldFromTab('Root.RelatedEvents', 'RelatedEvents');
            $fields->removeFieldFromTab('Root', 'RelatedEvents');
            $fields->addFieldToTab('Root.DatesTab', ToggleCompositeField::create(
                            'EventsComposite', //
                            _t('Genealogist.RELATED_EVENTS', 'Related Events'), //
                            $relatedEvents
                    )
            );
        }
    }

    protected function personConfigs($showFather = false, $showMother = true, $allowCreate = true) {
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

        $edit = new GridFieldEditableColumns();
        $edit->setDisplayFields($columns);

        $config->addComponent($edit);

        $config->addComponent(new GridFieldEditButton());
        $config->addComponent(new GridFieldDeleteAction(true));

        return $config;
    }

    protected function getMotherField($record, $column) {
        if ($record->Father()->exists()) {
            $mothers = $record->Father()->Wives()->map();
            return DropdownField::create($column)
                            ->setSource($mothers)
                            ->setValue($record->MotherID)
                            ->setEmptyString(_t('Genealogist.CHOOSE_MOTHER', 'Choose Mother'));
        }

        return ReadonlyField::create($column);
    }

    public function getDefaultSearchContext() {
        $fields = $this->scaffoldSearchFields(array(
            'restrictFields' => array(
                'Name',
            )
        ));

        $filters = array(
            'Name' => new PartialMatchFilter('Name'),
        );

        return new SearchContext(
                $this->class, $fields, $filters
        );
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
            if ($this->FatherID && !$this->Father()->isClan()) {
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

    protected function onBeforeWrite() {
        parent::onBeforeWrite();

        $this->trim('Name');
        $this->trim('NickName');

        if ($this->DeathDate) {
            $this->IsDead = 1;
        }
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

    /// Getters ///
    public function getTitle() {
        return $this->getFullName();
    }

    public function getFirstName() {
        return $this->Name;
    }

    /**
     * Returns the formated person's name
     * @return strnig
     */
    public function getPersonName() {
//        return $this->getFirstName();
//        return $this->getAliasName();
        $name = '';

        $name .= $this->getFirstName();

        if ($this->NickName) {
            $name .= " ({$this->NickName})";
        }

        return $name;
    }

    /**
     * Returns the person's name and nickname
     * @return string
     */
    public function getAliasName() {
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

        return $name;
    }

    /**
     * Returns the person's full name
     * @return string
     */
    public function getFullName($withChildOf = true) {
        $name = $this->getPersonName();

        if ($this->isMale() && $this->Tribe()->exists()) {
            $name .= ' ' . $this->Tribe()->getTribeName();
        }

        if (!$this->Father()->exists()) {
            return $name;
        }

        if ($withChildOf) {
            $childOf = '';
            if ($this->Father()->isClan()) {
                $childOf = _t('Genealogist.SONS_OF');
            } else {
                $childOf = $this->isFemale() ? _t('Genealogist.DAUGHTER_OF') : _t('Genealogist.SON_OF');
            }
            $name .= " {$childOf} {$this->Father()->getFullName()}";
        } else {
            $name .= " {$this->Father()->getFullName()}";
        }

        return $name;
    }

    /**
     * Returns the person's brief name
     * @return string
     */
    public function getBriefName() {
        $name = $this->getPersonName();

        return "{$name} {$this->getClanName()}{$this->getTribeName()}";
    }

    /**
     * Returns the person's short name
     * @return string
     */
    public function getShortName() {
        $name = $this->getPersonName();

        if ($this->getTribeName()) {
            return "{$name} {$this->getTribeName()}";
        } else {
            return "{$name} {$this->getRootClan()}";
        }
    }

    /**
     * Returns the person's clan names
     * @return string
     */
    public function getClanName() {
        $name = '';
        if (!$this->Father()->exists()) {
            return $name;
        }

        return "{$name} {$this->Father()->getClanName()}";
    }

    public function getTribeName() {
        $name = '';

        if ($this->Father()->exists()) {
            $name .= $this->Father()->getTribeName();
        }

        return $name;
    }

    /**
     * Returns the person's full name
     * @return string
     */
    public function toIndexName() {
        $name = $this->Name;
        if (!$this->Father()->exists()) {
            return $name;
        }

        return "{$name} {$this->Father()->toIndexName()}";
    }

    /**
     * Returns the person's age
     * @return string
     */
    public function getAge() {
        if ($this->DeathDate && $this->BirthDate) {
            return $this->DeathDate - $this->BirthDate;
        } else if ($this->BirthDate) {
            $birth = new Date();
            $birth->setValue($this->BirthDate);
            return $birth->TimeDiff();
        }

        return null;
    }

    /**
     * Returns the full name series of the person's parents.
     * @return string
     */
    public function getParents() {
        $person = $this;
        $name = '';

        while ($person->Father()->exists()) {
            $person = $person->Father();
            $name .= ' ' . $person->getFirstName();
        }

        return $name;
    }

    /**
     * Returns the root of this person
     * @return Person
     */
    public function getRoot() {
        $person = $this;

        while ($person->Father()->exists()) {
            $person = $person->Father();
        }

        return $person;
    }

    public function getRootClan() {
        $person = $this;

        $clan = null;
        while ($person->Father()->exists()) {
            $person = $person->Father();
            if ($person->isClan()) {
                $clan = $person;
            }
        }

        return $clan ? $clan->Name : '';
    }

    /**
     * Returns all sons and daughters
     * @return ArrayList
     */
    public function getChildren() {
        GenealogistHelper::get_children($this);
    }

    public function ThumbPhoto() {
        return $this->Photo()->CMSThumbnail();
    }

    /**
     * Checks if this person is older than 18 years
     * @return boolean
     */
    public function isAdult() {
        return $this->getAge() > 18;
    }

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
     * Checks if this person is a clan
     * @return boolean
     */
    public function isClan() {
        return $this instanceof Clan;
    }

    /**
     * Checks if this person is a tribe
     * @return boolean
     */
    public function isTribe() {
        return $this instanceof Tribe;
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
        return GenealogistHelper::count_descendants($this, $state);
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
        return GenealogistHelper::count_males($this, $state);
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
        return GenealogistHelper::count_females($this, $state);
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
        return GenealogistHelper::count_sons($this, $state);
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
        return GenealogistHelper::count_daughters($this, $state);
    }

    public function getBirthYear() {
        if ($this->BirthDate) {
            $date = new Date();
            $date->setValue($this->BirthDate);

            return $date->Year();
        }
        return null;
    }

    public function getCalculatedBirthYear() {
        return $this->Stats()->MinYear;
    }

    public function getDeathYear() {
        if ($this->DeathDate) {
            $date = new Date();
            $date->setValue($this->DeathDate);

            return $date->Year();
        }
        return null;
    }

    public function getCalculatedDeathYear() {
        return $this->Stats()->MaxYear;
    }

    /// Events ///
    public function getLifeEvents() {
        return GenealogistEventsHelper::get_life_events($this);
    }

    public function getBirthEventDate() {
        return GenealogistEventsHelper::get_birth_date($this);
    }

    public function getDeathEventDate() {
        return GenealogistEventsHelper::get_death_date($this);
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
//        return DataObject::get('Person', "`PublicFigure` = 1 OR `ClassName` = 'Clan'")->sort('RAND()');
        return null;
    }

    public function isObjectDisabled() {
        return !$this->canView();
    }

    public function getObjectTabs() {
        $lists = array();

        $lists[] = array(
            'Title' => _t('Genealogist.LIFESTORY', 'Life Story'),
            'Content' => $this->renderWith('Person_Lifestory')
        );

        $lists[] = array(
            'Title' => _t('Genealogist.FAMILY', 'Family'),
            'Content' => $this->renderWith('Person_Family')
        );

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
