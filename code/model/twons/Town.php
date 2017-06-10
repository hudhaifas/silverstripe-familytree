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
 * @version 1.0, May 23, 2017 - 11:13:04 AM
 */
class Town
        extends DataObject
        implements SingleDataObject {

    private static $db = array(
        'TownID' => 'Varchar(255)',
        'DefaultName' => 'Varchar(255)',
        // Map
        'Latitude' => 'Varchar(255)',
        'Longitude' => 'Varchar(255)',
        'Zoom' => 'Varchar(255)',
        // Biography
        'Biography' => 'HTMLText',
        // Permession Level
        "CanViewType" => "Enum('Anyone, LoggedInUsers, OnlyTheseUsers', 'Anyone')",
        "CanEditType" => "Enum('LoggedInUsers, OnlyTheseUsers', 'OnlyTheseUsers')",
    );
    private static $has_one = array(
        'Photo' => 'Image',
    );
    private static $has_many = array(
        'TwonNames' => 'TwonName',
        'Events' => 'PersonalEvent',
        'Births' => 'Person.BirthPlace',
        'Deaths' => 'Person.DeathPlace',
        'Buried' => 'Person.BurialPlace',
    );
    private static $many_many = array(
        'TownClans' => 'Clan',
        "ViewerGroups" => "Group",
        "EditorGroups" => "Group",
        "ViewerMembers" => "Member",
        "EditorMembers" => "Member",
    );
    private static $defaults = array(
        "Zoom" => 15,
        "CanViewType" => "Anyone",
        "CanEditType" => "OnlyTheseUsers"
    );
    private static $summary_fields = array(
        'TownID',
        'Title',
    );
    private static $default_sort = 'Latitude, Longitude';
    private static $cache_permissions = array();
    private static $apiKey = "AIzaSyB3XOLhZ8e3iI_rnBGQonCUw7Dz0CtDFyE";

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Photo'] = _t('Genealogist.PHOTO', 'Photo');
        $labels['Title'] = _t('Genealogist.TOWN_NAME', 'Town Name');
        $labels['TownID'] = _t('Genealogist.TOWN_ID', 'Town ID');
        $labels['DefaultName'] = _t('Genealogist.TOWN_DEFAULT_NAME', 'Default Name');
        $labels['TwonNames'] = _t('Genealogist.TWON_NAMES', 'Other Names');
        $labels['Latitude'] = _t('Genealogist.LATITUDE', 'Latitude');
        $labels['Longitude'] = _t('Genealogist.LONGITUDE', 'Longitude');

        $labels['Events'] = _t('Genealogist.EVENTS', 'Events');
        $labels['Births'] = _t('Genealogist.BIRTHS', 'Births');
        $labels['Deaths'] = _t('Genealogist.DEATHS', 'Deaths');
        $labels['Buried'] = _t('Genealogist.BURIED', 'Buried');
        $labels['TownClans'] = _t('Genealogist.TOWN_CLANS', 'Town Clans');
        $labels['Coordinates'] = _t('Genealogist.COORDINATES', 'Coordinates');

        // Settings
        $labels['CanViewType'] = _t('Genealogist.CAN_VIEW_TYPE', 'Who can view this person');
        $labels['CanEditType'] = _t('Genealogist.CAN_EDIT_TYPE', 'Who can edit this person');

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->removeFieldFromTab('Root.Main', 'Latitude');
        $fields->removeFieldFromTab('Root.Main', 'Longitude');
        $fields->removeFieldFromTab('Root.Main', 'Zoom');

        $this->reorderField($fields, 'PhotoID', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'TownID', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'DefaultName', 'Root.Main', 'Root.Main');
        $fields->addFieldToTab('Root.Main', new GoogleMapField($this, _t('Genealogist.COORDINATES', 'Coordinates'), array(
            'api_key' => self::$apiKey
        )));
        $this->reorderField($fields, 'Biography', 'Root.Main', 'Root.Main');

//        $this->reorderField($fields, 'Latitude', 'Root.Main', 'Root.Main');
//        $this->reorderField($fields, 'Longitude', 'Root.Main', 'Root.Main');
//        $this->reorderField($fields, 'Zoom', 'Root.Main', 'Root.Main');

        $this->getSettingsFields($fields);

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

    public function getTitle($date = null) {
        if (!$date) {
//            $date = '1946-12-22';
            return $this->DefaultName ? $this->DefaultName : $this->TownID;
        }

        foreach ($this->TwonNames() as $townName) {
            if ($townName->isBetween($date)) {
                return $townName->Name;
            }
        }

        return $this->DefaultName ? $this->DefaultName : $this->TownID;
    }

    /// Permissions ///
    public function canCreate($member = false) {
        if (!$member) {
            $member = Member::currentUserID();
        }

        if ($member && is_numeric($member)) {
            $member = DataObject::get_by_id('Member', $member);
        }

        $cachedPermission = self::cache_permission_check('create', $member, $this->ID);
        if (isset($cachedPermission)) {
            return $cachedPermission;
        }

        if ($member && Permission::checkMember($member, "ADMIN")) {
            return true;
        }

        $extended = $this->extendedCan('canCreateTowns', $member);
        if ($extended !== null) {
            return $extended;
        }

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

        $extended = $this->extendedCan('canViewTowns', $member);
        if ($extended !== null) {
            return self::cache_permission_check('view', $member, $this->ID, $extended);
        }

        if (!$this->CanViewType || $this->CanViewType == 'Anyone') {
            return self::cache_permission_check('view', $member, $this->ID, true);
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

        $extended = $this->extendedCan('canDeleteTowns', $member);
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

        $extended = $this->extendedCan('canEditTowns', $member);
        if ($extended !== null) {
            return self::cache_permission_check('edit', $member, $this->ID, $extended);
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

    ///
    public function getAllBorn() {
        return GenealogistTownHelper::get_town_born($this);
    }

    public function getBornPublicFigures() {
        return GenealogistTownHelper::get_town_born_public_figures($this);
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

    public function getObjectDefaultImage() {
        if ($this->Latitude && $this->Longitude) {
            $gmapsParams = array(
                'center' => "{$this->Latitude},{$this->Longitude}",
                'zoom' => $this->Zoom,
                'scale' => 1,
                'size' => '500x410',
                'maptype' => 'roadmap',
                'key' => self::$apiKey,
                'format' => 'png',
                'visual_refresh' => true,
            );

//            return "https://maps.googleapis.com/maps/api/staticmap?center={$this->Latitude},{$this->Longitude}&zoom=15&scale=1&size=500x410&maptype=roadmap&format=png&visual_refresh=true";
//            return "//maps.googleapis.com/maps/api/staticmap?center={$this->Latitude},{$this->Longitude}&zoom=15&scale=1&size=500x410&maptype=roadmap&key={$apiKey}&format=png&visual_refresh=true";
            return "//maps.googleapis.com/maps/api/staticmap?" . http_build_query($gmapsParams);
        }
        return "genealogist/images/default-town.png";
    }

    public function getObjectImage() {
        return $this->Photo();
    }

    public function getObjectLink() {
        return TwonsPage::get()->first()->Link("show/$this->ID");
    }

    public function getObjectRelated() {
        if ($this->Latitude && $this->Longitude) {
            // Sort by distance
            $sort = "(POW((Longitude-{$this->Longitude}),2) + POW((Latitude-{$this->Latitude}),2))";
        } else {
            $sort = 'RAND()';
        }

        return $this->get()
                        ->filter(array(
                            'ID:Negation' => $this->ID
                        ))
                        ->sort($sort)
                        ->filterByCallback(function($record) {
                            return $record->canView();
                        });
    }

    public function getObjectSummary() {
        return $this->renderWith('Town_Summary');
    }

    public function getObjectTabs() {
        $lists = array();

        if ($this->Biography) {
            $lists[] = array(
                'Title' => _t('Genealogist.BIOGRAPHY', 'Biography'),
                'Content' => $this->Biography
            );
        }

        $namesCount = $this->TwonNames()->Count();
        if ($namesCount) {
            $lists[] = array(
                'Title' => _t('Genealogist.HISTORICAL_NAMES', 'Historical Names') . " ({$namesCount})",
                'Content' => $this->renderWith('Town_Names')
            );
        }

        $clansCount = $this->TownClans()->Count();
        if ($clansCount) {
            $lists[] = array(
                'Title' => _t('Genealogist.TRIBES_CLANS', 'Tribes & Clans') . " ({$clansCount})",
                'Content' => $this
                        ->customise(array(
                            'Results' => $this->TownClans()
                        ))
                        ->renderWith('List_Grid')
            );
        }

        $this->extend('extraTabs', $lists);

        return new ArrayList($lists);
    }

    public function getObjectTitle() {
        return $this->getTitle();
    }

    public function isObjectDisabled() {
        return !$this->canView();
    }

}
