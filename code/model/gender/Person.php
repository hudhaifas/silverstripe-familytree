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
        extends Gender {

    private static $db = array(
        // Birth
        'BirthDate' => 'Date',
        'BirthDateEstimated' => 'Boolean',
        'DeathDate' => 'Date',
        'DeathDateEstimated' => 'Boolean',
        'IsDead' => 'Boolean',
        // Indexing
        'IndexedName' => 'Text',
        'IndexedAncestors' => 'Text',
        // Order
        'ChildOrder' => 'Int',
        'YearOrder' => 'Int',
        // Permession Level
        'IsPublicFigure' => 'Boolean',
    );
    private static $has_one = array(
        'Father' => 'Male',
        'Mother' => 'Female',
        'BirthPlace' => 'Town',
        'DeathPlace' => 'Town',
        'BurialPlace' => 'Town',
        'ResidencePlace' => 'Town',
    );
    private static $has_many = array(
        // Descendants
        'Sons' => 'Male',
        'Daughters' => 'Female',
        // Events
        'Events' => 'PersonalEvent.Person',
        'RelatedEvents' => 'PersonalEvent.RelatedPerson',
    );
    private static $many_many = array(
    );
    private static $belongs_many_many = array(
    );
    private static $defaults = array(
    );
    private static $summary_fields = array(
        'AliasName',
        'Parents',
        'Mother.Name',
        'BirthDate',
        'Age',
        'Note',
        'MarriageDate',
    );
    private static $default_sort = 'ChildOrder';
    public static $STATE_ALIVE = 1;
    public static $STATE_DEAD = 2;

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        $fields->removeFieldFromTab('Root.Main', 'ParentID');
        $fields->removeFieldFromTab('Root.Main', 'ChildOrder');
        $fields->removeFieldFromTab('Root.Main', 'YearOrder');
        $fields->removeFieldFromTab('Root.Main', 'IndexedName');
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

        $this->getCMSEvents($fields);

        $this->reorderField($fields, 'TribeID', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'FatherID', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'MotherID', 'Root.Main', 'Root.Main');

        return $fields;
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

        $this->reorderField($fields, 'ResidencePlaceID', 'Root.Main', 'Root.DatesTab');

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

    protected function onBeforeWrite() {
        parent::onBeforeWrite();

        if ($this->DeathDate) {
            $this->IsDead = 1;
        }
    }

    /// Getters ///
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

        if ($this->isMale() && $this->Tribe()->exists()) {
            $name .= ' ' . $this->Tribe()->getTribeName();
        }

        if (!$this->Father()->exists()) {
            return $name;
        }

        if ($withChildOf) {
            $childOf = '';
            if ($this->Father()->isBranch()) {
                $childOf = _t('Genealogist.SONS_OF');
            } else {
                $childOf = $this->isFemale() ? _t('Genealogist.DAUGHTER_OF') : _t('Genealogist.SON_OF');
            }
            $name .= " {$childOf} {$this->Father()->getFullName($withChildOf)}";
        } else {
            $name .= " {$this->Father()->getFullName($withChildOf)}";
        }

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
        $name .= " {$this->getBranchName()}{$this->getTribeName()}";

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

        if ($this->getTribeName()) {
            $name .= " {$this->getTribeName()}";
        } else {
            $name .= " {$this->getRootBranch()}";
        }

        return self::cache_name_check('short-name', $this->ID, $name);
    }

    /**
     * Returns the person's branch names
     * @return string
     */
    public function getBranchName() {
        $cachedName = self::cache_name_check('branch-name', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $name = '';
        if ($this->Father()->exists()) {
            $name .= " {$this->Father()->getBranchName()}";
        }

        return self::cache_name_check('branch-name', $this->ID, $name);
    }

    public function getTribeName() {
        $cachedName = self::cache_name_check('tribe-name', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $name = '';

        if ($this->Father()->exists() && $this->Father()->getTribeName()) {
            $name .= $this->Father()->getTribeName();
        }

        return self::cache_name_check('tribe-name', $this->ID, $name);
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
        if ($this->Father()->exists()) {
            $name .= " {$this->Father()->toIndexName()}";
        }

        return self::cache_name_check('indexed-name', $this->ID, $name);
    }

    /**
     * Returns the full name series of the person's parents.
     * @return string
     */
    public function getParents() {
        $cachedName = self::cache_name_check('parents-name', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $person = $this;
        $name = '';

        while ($person->Father()->exists()) {
            $person = $person->Father();
            $name .= ' ' . $person->getFirstName();
        }

        return self::cache_name_check('parents-name', $this->ID, $name);
    }

    public function getRootBranch() {
        $cachedName = self::cache_name_check('root-branch', $this->ID);
        if (isset($cachedName)) {
            return $cachedName;
        }

        $person = $this;

        $branch = null;
        while ($person->Father()->exists()) {
            $person = $person->Father();
            if ($person->isBranch()) {
                $branch = $person;
            }
        }

        $name = $branch ? $branch->Name : '';
        return self::cache_name_check('root-branch', $this->ID, $name);
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

    /**
     * Returns all sons and daughters
     * @return ArrayList
     */
    public function getChildren() {
        return GenealogistHelper::get_children($this);
    }

    /**
     * Checks if this person is older than 18 years
     * @return boolean
     */
    public function isAdult() {
        return $this->getAge() > 18;
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

    public function getObjectTabs() {
        $lists = parent::getObjectTabs();

        if ($this->Events()->Count()) {
            $item = array(
                'Title' => _t('Genealogist.LIFESTORY', 'Life Story'),
                'Content' => $this->renderWith('Person_Lifestory')
            );
            $lists->add($item);
        }

        $item = array(
            'Title' => _t('Genealogist.FAMILY', 'Family'),
            'Content' => $this->renderWith('Person_Family')
        );
        $lists->add($item);

        return $lists;
    }

}
