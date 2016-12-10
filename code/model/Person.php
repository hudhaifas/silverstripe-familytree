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
 * @version 1.0, Nov 2, 2016 - 10:59:52 AM
 */
class Person
        extends DataObject {

    private static $db = array(
        'Name' => 'Varchar(255)',
        'NickName' => 'Varchar(255)',
        'BirthDate' => 'Date',
        'DeathDate' => 'Date',
        'IsDead' => 'Boolean',
        'Note' => 'Varchar(255)',
    );
    private static $has_one = array(
        'Photo' => 'Image',
        'Father' => 'Male',
        'Mother' => 'Female',
        'Page' => 'GenealogyPage',
    );
    private static $has_many = array(
        'Sons' => 'Male',
        'Daughters' => 'Female',
    );
    private static $many_many = array(
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
        'Age',
        'Note',
    );
    public static $STATE_ALIVE = 1;
    public static $STATE_DEAD = 2;
    private static $access_groups = array('administrators', 'librarians', 'genealogists');

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Photo'] = _t('Genealogist.PHOTO', 'Photo');

        $labels['AliasName'] = _t('Genealogist.NAME', 'Name');
        $labels['Name'] = _t('Genealogist.NAME', 'Name');
        $labels['NickName'] = _t('Genealogist.NICKNAME', 'NickName');
        $labels['Parents'] = _t('Genealogist.PARENTS', 'Parents');
        $labels['Father'] = _t('Genealogist.FATHER', 'Father');
        $labels['Father.Name'] = _t('Genealogist.FATHER_NAME', 'Father Name');
        $labels['Mother'] = _t('Genealogist.MOTHER', 'Mother');
        $labels['Mother.Name'] = _t('Genealogist.MOTHER_NAME', 'Mother Name');
        $labels['Husband'] = _t('Genealogist.HUSBAND', 'Husband');
        $labels['Wife'] = _t('Genealogist.WIFE', 'Wife');
        $labels['Children'] = _t('Genealogist.CHILDREN', 'Children');
        $labels['Sons'] = _t('Genealogist.SONS', 'Sons');
        $labels['Daughters'] = _t('Genealogist.DAUGHTERS', 'Daughters');
        $labels['Page'] = _t('Genealogist.PAGE', 'Page');
        $labels['BirthDate'] = _t('Genealogist.BIRTHDATE', 'Birth Date');
        $labels['DeathDate'] = _t('Genealogist.DEATHDATE', 'Death Date');
        $labels['Age'] = _t('Genealogist.AGE', 'Age');
        $labels['IsDead'] = _t('Genealogist.ISDEAD', 'Is Dead');
        $labels['Note'] = _t('Genealogist.NOTE', 'Note');

        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            if ($field = $fields->fieldByName('Root.Main.BirthDate')) {
                $field->setConfig('showcalendar', true);
            }

            if ($field = $fields->fieldByName('Root.Main.DeathDate')) {
                $field->setConfig('showcalendar', true);
            }

            $fields->removeFieldFromTab('Root.Main', 'ParentID');

//            $self->reorderField($fields, 'Name', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'NickName', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'BirthDate', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'DeathDate', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'DeathDate', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'IsDead', 'Root.Main', 'Root.Main');
//            
//            $self->reorderField($fields, 'FatherID', 'Root.Main', 'Root.Main');
//            $self->reorderField($fields, 'MotherID', 'Root.Main', 'Root.Main');
//            
//            $self->reorderField($fields, 'FatherID', 'Root.Main', 'Root.Main');
        });

        $fields = parent::getCMSFields();

        return $fields;
    }

    public function canCreate($member = null) {
        return false;
    }

    public function canView($member = false) {
        return true;
    }

    public function canDelete($member = false) {
        return false;
    }

    public function canEdit($member = false) {
        return false;
    }

    protected function onBeforeWrite() {
        parent::onBeforeWrite();

        if ($this->DeathDate) {
            $this->IsDead = 1;
        }
    }

    protected function onBeforeDelete() {
        parent::onBeforeDelete();
    }

    public function getTitle() {
        return $this->getFullName();
    }

    public function getPersonName() {
//        return $this->Name;
        return $this->getAliasName();
    }

    public function getAliasName() {
        $name = $this->Name;
        if ($this->NickName) {
            $name .= ' (' . $this->NickName . ')';
        }
        return $name;
    }

    public function getFullName() {
        $name = $this->getPersonName();
        if (!$this->Father()->exists()) {
            return $name;
        }

        return $name . ' ' . $this->Father()->getFullName();
    }

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

    public function getParents() {
        $person = $this;
        $name = '';

        while ($person->Father()->exists()) {
            $person = $person->Father();
            $name .= ' ' . $person->Name;
        }

        return $name;
    }

    public function getRoot() {
        $person = $this;

        while ($person->Father()->exists()) {
            $person = $person->Father();
        }

        return $person;
    }

    function Link($action = null) {
        return Director::get_current_page()->Link("$this->ID");
    }

    function InfoLink($action = null) {
        return Director::get_current_page()->Link("person-info/$this->ID");
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

    public function ThumbCover() {
        return $this->Photo()->CMSThumbnail();
    }

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

    public function isAdult() {
        return $this->getAge() > 18;
    }

    public function isMale() {
        return $this instanceof Male;
    }

    public function isFemale() {
        return $this instanceof Female;
    }

    public function isClan() {
        return $this instanceof Clan;
    }

    public function CSSClasses($stopAtClass = 'DataObject') {
        $classes = strtolower(parent::CSSClasses($stopAtClass));

        $classes .= $this->IsDead ? ' dead' : '';

        return $classes;
    }

    public function toString() {
        return $this->getTitle();
    }

    public function hasPermission() {
        $member = Member::currentUser();
        return $member && $member->inGroups($this->config()->access_groups);
    }

    public function getHtmlUI($showFemales = 0, $showFemalesSeed = 0) {
        if ($this->hasPermission()) {
            if (isset($_GET['f'])) {
                $showFemales = $_GET['f'];
            }

            if (isset($_GET['fch'])) {
                $showFemalesSeed = $_GET['fch'];
            }
        }

        // Show only Male's children
        if ($this->isFemale() && !$showFemales) {
            return '';
        }

        $html = <<<HTML
            <li class="{$this->CSSClasses()}">
                <a href="#" title="{$this->getFullName()}" data-url="{$this->InfoLink()}" class="info-item">{$this->getPersonName()}</a>
                <ul>
                    {$this->getChildrenHtmlUI($showFemales, $showFemalesSeed)}
                </ul>
            </li>
HTML;

        return $html;
    }

    private function getChildrenHtmlUI($showFemales = 0, $showFemalesSeed = 0) {
        $html = '';

        if ($this->isFemale() && !$showFemalesSeed) {
            return '';
        }

        foreach ($this->getChildren() as $child) {
            $html .= $child->getHtmlUI($showFemales, $showFemalesSeed);
        }

//        if ($this->Sons()->exists()) {
//            foreach ($this->Sons()->sort('BirthDate ASC') as $child) {
//                $html .= $child->getHtmlUI();
//            }
//        }

        return $html;
    }

    public function getChildren() {
        $children = array();

        if ($this->Sons()->exists()) {
            foreach ($this->Sons() as $child) {
                $children[] = $child;
            }
        }

        if ($this->Daughters()->exists()) {
            foreach ($this->Daughters() as $child) {
                $children[] = $child;
            }
        }
        return (new ArrayList($children))->sort('BirthDate ASC');
    }

    public function OffspringCount($state = 0) {
        return $this->MalesCount($state) + $this->FemalesCount($state);
    }

    public function MalesCount($state = 0) {
        switch ($state) {
            case self::$STATE_ALIVE:
                $count = $this->isMale() && !$this->IsDead ? 1 : 0;
                break;

            case self::$STATE_DEAD:
                $count = $this->isMale() && $this->IsDead ? 1 : 0;
                break;

            default:
                $count = $this->isMale() ? 1 : 0;
                break;
        }

        foreach ($this->Sons() as $child) {
            $count += $child->MalesCount($state);
        }

        return $count;
    }

    public function FemalesCount($state = 0) {
        switch ($state) {
            case self::$STATE_ALIVE:
                $count = $this->isFemale() && !$this->IsDead ? 1 : 0;
                break;

            case self::$STATE_DEAD:
                $count = $this->isFemale() && $this->IsDead ? 1 : 0;
                break;

            default:
                $count = $this->isFemale() ? 1 : 0;
                break;
        }

        $count += $this->Daughters()->Count();

        foreach ($this->Sons() as $child) {
            $count += $child->FemalesCount($state);
        }

        return $count;
    }

    public function SonsCount($state = 0) {
        $count = 0;

        foreach ($this->Sons() as $child) {
            switch ($state) {
                case self::$STATE_ALIVE:
                    $count +=!$child->IsDead && !$child->isClan() ? 1 : 0;
                    break;

                case self::$STATE_DEAD:
                    $count += $child->IsDead && !$child->isClan() ? 1 : 0;
                    break;

                default:
//                    $count++;
                    $count +=!$child->isClan() ? 1 : 0;
                    break;
            }
        }

        return $count;
    }

    public function DaughtersCount($isAlive = true) {
        return $this->Daughters()->Count();
    }

}
