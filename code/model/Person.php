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
        'DeathDate' => 'Date',
        'IsDead' => 'Boolean',
        // Notes
        'Note' => 'Varchar(255)',
        'Comments' => 'Text',
        // Biography
        'PublicFigure' => 'Boolean',
        'Biography' => 'HTMLText',
        'IsPrivate' => 'Boolean',
        // Indexing
        'IndexedName' => 'Text',
        // Order
        'SortOrder'=>'Int'
    );
    private static $has_one = array(
        'Photo' => 'Image',
        'Father' => 'Male',
        'Mother' => 'Female',
        'Stats' => 'PersonStats',
    );
    private static $has_many = array(
        'Sons' => 'Male',
        'Daughters' => 'Female',
        'Suggestions' => 'Suggestion',
    );
    private static $many_many = array(
    );
    private static $belongs_many_many = array(
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
    private static $default_sort = 'SortOrder';
    public static $STATE_ALIVE = 1;
    public static $STATE_DEAD = 2;

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
        $labels['Husbands'] = _t('Genealogist.HUSBANDS', 'Husbands');
        $labels['Wife'] = _t('Genealogist.WIFE', 'Wife');
        $labels['Wives'] = _t('Genealogist.WIVES', 'Wives');

        $labels['Children'] = _t('Genealogist.CHILDREN', 'Children');
        $labels['Sons'] = _t('Genealogist.SONS', 'Sons');
        $labels['Daughters'] = _t('Genealogist.DAUGHTERS', 'Daughters');

        $labels['BirthDate'] = _t('Genealogist.BIRTHDATE', 'Birth Date');
        $labels['DeathDate'] = _t('Genealogist.DEATHDATE', 'Death Date');
        $labels['Age'] = _t('Genealogist.AGE', 'Age');
        $labels['IsDead'] = _t('Genealogist.ISDEAD', 'Is Dead');
        $labels['Note'] = _t('Genealogist.NOTE', 'Note');
        $labels['Comments'] = _t('Genealogist.COMMENTS', 'Comments');

        $labels['Biography'] = _t('Genealogist.BIOGRAPHY', 'Biography');
        $labels['PublicFigure'] = _t('Genealogist.PUBLIC_FIGURE', 'Public Figure');
        $labels['IsPrivate'] = _t('Genealogist.IS_PRIVATE', 'Hide Information');

        $labels['Suggestions'] = _t('Genealogist.SUGGESTIONS', 'Suggestions');

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

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

        $fields->removeFieldFromTab('Root.Main', 'ParentID');
        $fields->removeFieldFromTab('Root.Main', 'IndexedName');
        $fields->removeFieldFromTab('Root.Main', 'StatsID');

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
        $fields->addFieldsToTab('Root.Main', array(
            AutoPersonField::create(
                    'MotherID', //
                    _t('Genealogist.MOTHER', 'Mother'), //
                    '', //
                    null, //
                    null, //
                    'Female', //
                    array('IndexedName', 'Name', 'NickName') //
            )
        ));

        $this->reorderField($fields, 'Photo', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Prefix', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Name', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'NickName', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Postfix', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'Note', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'BirthDate', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'DeathDate', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'IsDead', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'DeathDate', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'FatherID', 'Root.Main', 'Root.Main');
        $this->reorderField($fields, 'MotherID', 'Root.Main', 'Root.Main');

        $biographyTab = new Tab('BiographyTab', _t('Genealogist.BIOGRAPHY', 'Biography'));
        $fields->insertAfter('Main', $biographyTab);
        $this->reorderField($fields, 'PublicFigure', 'Root.Main', 'Root.BiographyTab');
        $this->reorderField($fields, 'IsPrivate', 'Root.Main', 'Root.BiographyTab');
        $this->reorderField($fields, 'Biography', 'Root.Main', 'Root.BiographyTab');

        $detailsTab = new Tab('DetailsTab', _t('Genealogist.DETAILS', 'Details'));
        $fields->insertAfter('Wives', $detailsTab);
        $this->reorderField($fields, 'Comments', 'Root.Main', 'Root.DetailsTab');

        return $fields;
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
        return true;
    }

    public function canDelete($member = false) {
        return false;
    }

    public function canEdit($member = false) {
        return true;
    }

    /**
     * Checks if the user is an authorized member
     * @return boolean true if the user is an authorized member
     */
    public function hasPermission() {
        return GenealogistHelper::is_genealogists();
    }

    protected function onBeforeWrite() {
        parent::onBeforeWrite();

        $this->trim('Name');
        $this->trim('NickName');

        if ($this->DeathDate) {
            $this->IsDead = 1;
        }
    }

    protected function onBeforeDelete() {
        parent::onBeforeDelete();
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
        return FiguresPage::get()->first()->Link("edit/$this->ID");
    }

    function TreeLink($action = null) {
        return $this->personLink("$this->ID");
    }

    function ShowLink($action = null) {
        return $this->personLink("show/$this->ID");
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
        return $this->getAliasName();
    }

    /**
     * Returns the person's name and nickname
     * @return string
     */
    public function getAliasName() {
        $name = $this->getFirstName();

        if ($this->NickName) {
            $name .= ' (' . $this->NickName . ')';
        }

        return $name;
    }

    /**
     * Returns the person's full name
     * @return string
     */
    public function getFullName() {
        $name = $this->getPersonName();
        if (!$this->Father()->exists()) {
            return $name;
        }

        return $name . ' ' . $this->Father()->getFullName();
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

    /**
     * Returns all sons and daughters
     * @return ArrayList
     */
    public function getChildren() {
//        $children = array();
//
//        if ($this->Sons()->exists()) {
//            foreach ($this->Sons() as $child) {
//                $children[] = $child;
//            }
//        }
//
//        if ($this->Daughters()->exists()) {
//            foreach ($this->Daughters() as $child) {
//                $children[] = $child;
//            }
//        }
//
//        return (new ArrayList($children))->sort('BirthDate ASC');
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

    /// UI ///
    public function CSSClasses($stopAtClass = 'DataObject') {
        $classes = strtolower(parent::CSSClasses($stopAtClass));

        $classes .= $this->IsDead ? ' dead' : '';

        return $classes;
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
    public function getDescendantsLeaves($males = 1, $malesSeed = 1, $females = 0, $femalesSeed = 0) {
        if (isset($_GET['ancestral']) && $_GET['ancestral'] == 1) {
            return $this->getAncestorsLeaves();
        }

        if (isset($_GET['m'])) {
            $males = $_GET['m'];
        }

        if (isset($_GET['ms'])) {
            $malesSeed = $_GET['ms'];
        }

        if ($this->hasPermission()) {
            if (isset($_GET['f'])) {
                $females = $_GET['f'];
            }

            if (isset($_GET['fs'])) {
                $femalesSeed = $_GET['fs'];
            }
        }

        $html = <<<HTML
            <li class="{$this->CSSClasses()}">
                <a href="#" title="{$this->getFullName()}" data-url="{$this->InfoLink()}" class="info-item">{$this->getPersonName()}</a>
                <ul>
                    {$this->getChildrenLeaves($males, $malesSeed, $females, $femalesSeed)}
                </ul>
            </li>
HTML;

        return $html;
    }

    private function getChildrenLeaves($males = 1, $malesSeed = 1, $females = 0, $femalesSeed = 0) {
        $html = '';

        if ($males && !$malesSeed) {
            foreach ($this->Sons() as $child) {
                $html .= $child->getSelfLeaf();
            }
        } else if ($males && $malesSeed) {
            foreach ($this->Sons() as $child) {
                $html .= $child->getDescendantsLeaves($males, $malesSeed, $females, $femalesSeed);
            }
        }

        if ($females && !$femalesSeed) {
            foreach ($this->Daughters() as $child) {
                $html .= $child->getSelfLeaf();
            }
        } else if ($females && $femalesSeed) {
            foreach ($this->Daughters() as $child) {
                $html .= $child->getDescendantsLeaves($males, $malesSeed, $females, $femalesSeed);
            }
        }

        return $html;
    }

    private function getSelfLeaf() {
        $html = <<<HTML
            <li class="{$this->CSSClasses()}">
                <a href="#" title="{$this->getFullName()}" data-url="{$this->InfoLink()}" class="info-item">{$this->getPersonName()}</a>
            </li>
HTML;

        return $html;
    }

    private function getAncestorsLeaves() {
        $noFemales = !$this->hasPermission() && $this->isFemale();
        $name = $this->getPersonName();
        $title = $noFemales ? '' : $this->getFullName();

        $html = <<<HTML
            <li class="{$this->CSSClasses()}">
                <a href="#" title="{$title}" data-url="{$this->InfoLink()}" class="info-item">{$name}</a>
                <ul>
                    {$this->getParentsLeaves()}
                </ul>
            </li>
HTML;

        return $html;
    }

    private function getParentsLeaves() {
        $html = '';

        $father = $this->Father();
        if ($father && $father->exists()) {
            $html .= $father->getAncestorsLeaves();
        }

        $mother = $this->Mother();
        if ($mother && $mother->exists()) {
            $html .= $mother->getAncestorsLeaves();
        }

        return $html;
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

    public function getObjectLink() {
        return FiguresPage::get()->first()->Link("show/$this->ID");
    }

    public function getObjectRelated() {
        return DataObject::get('Person', "`PublicFigure` = 1 OR `ClassName` = 'Clan'")->sort('RAND()');
//        return DataObject::get('Person')->sort('RAND()');
    }

    public function isObjectDisabled() {
        return $this->IsPrivate || !($this->PublicFigure || $this->hasPermission());
    }

    public function getObjectTabs() {
        $lists = array();
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

        $this->extend('extraTabs', $lists);

        return new ArrayList($lists);
    }

    public function getObjectTitle() {
        return $this->getTitle();
    }

}