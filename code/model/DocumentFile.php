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
 * @version 1.0, Jan 3, 2017 - 9:34:12 AM
 */
class DocumentFile
        extends DataObject
        implements SingleDataObject {

    private static $db = array(
        'Title' => 'Varchar(255)',
        'Date' => 'Date',
        'Description' => 'Text',
        'Collector' => 'Varchar(255)',
        'Texts' => 'HTMLText',
        'IsPrivate' => 'Boolean',
    );
    private static $has_one = array(
        'Document' => 'Image',
    );
    private static $has_many = array(
    );
    private static $many_many = array(
        'Tags' => 'DocumentTag',
        'People' => 'Person',
    );
    private static $searchable_fields = array(
        'Title' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
        'Description' => array(
            'field' => 'TextField',
            'filter' => 'PartialMatchFilter',
        ),
    );
    private static $summary_fields = array(
        'ThumbDocument',
        'Title',
        'Date',
        'Description',
    );

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['Document'] = _t('Genealogist.DOCUMENT', 'Document');
        $labels['ThumbDocument'] = _t('Genealogist.DOCUMENT', 'Document');

        $labels['Title'] = _t('Genealogist.TITLE', 'Title');
        $labels['Description'] = _t('Genealogist.DESCRIPTION', 'Description');
        $labels['Texts'] = _t('Genealogist.TEXTS', 'Texts');
        $labels['Collector'] = _t('Genealogist.COLLECTOR', 'Collector');
        $labels['Person'] = _t('Genealogist.PERSON', 'Person');
        $labels['Date'] = _t('Genealogist.DATE', 'Date');
        $labels['Tags'] = _t('Genealogist.TAGS', 'Tags');

        return $labels;
    }

    public function getCMSFields() {
        $self = & $this;

        $this->beforeUpdateCMSFields(function ($fields) use ($self) {
            if ($field = $fields->fieldByName('Root.Main.Document')) {
                $field->getValidator()->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
                $field->setFolderName("genealogist/docs");
            }

            if ($field = $fields->fieldByName('Root.Main.Date')) {
                $field->setConfig('showcalendar', true);
                $field->setConfig('dateformat', 'dd-MM-yyyy');
            }

            $self->reorderField($fields, 'Document', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'Title', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'Date', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'Collector', 'Root.Main', 'Root.Main');

            $fields->removeFieldFromTab('Root', 'People');
            $peopleField = TagField::create(
                            'People', //
                            _t('Genealogist.PEOPLE', 'People'), //
                            Person::get(), //
                            $self->People()
            );
            $fields->addFieldToTab('Root.Main', $peopleField);

            $fields->removeFieldFromTab('Root', 'Tags');
            $tagsField = TagField::create(
                            'Tags', //
                            _t('Genealogist.TAGS', 'Tags'), //
                            DocumentTag::get(), //
                            $self->Tags()
            );
            $fields->addFieldToTab('Root.Main', $tagsField);

            $self->reorderField($fields, 'Description', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'Texts', 'Root.Main', 'Root.Main');
            $self->reorderField($fields, 'IsPrivate', 'Root.Main', 'Root.Main');
        });

        $fields = parent::getCMSFields();

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
        return $this->hasPermission();
    }

    public function canView($member = false) {
        return $this->hasPermission();
    }

    public function canDelete($member = false) {
        return $this->hasPermission();
    }

    public function canEdit($member = false) {
        return $this->hasPermission();
    }

    function Link($action = null) {
        return DocPage::get()->first()->Link("doc/$this->ID");
    }

    /**
     * Checks if the user is an authorized member
     * @return boolean true if the user is an authorized member
     */
    public function hasPermission() {
        return GenealogistHelper::is_genealogists();
    }

    public function ThumbDocument() {
        return $this->Document()->CMSThumbnail();
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

    public function getRelated() {
        return DocumentFile::get()->sort('RAND()');
    }

    public function getObjectSummary() {
        $lists = array();
        if ($this->Date) {
            $lists[] = array(
                'Title' => _t('Genealogist.DATE', 'Date'),
                'Value' => $this->Date
            );
        }

        if ($this->Collector) {
            $lists[] = array(
                'Title' => _t('Genealogist.COLLECTOR', 'Collector'),
                'Value' => $this->Collector
            );
        }

        if ($this->Collector) {
            $lists[] = array(
                'Title' => _t('Genealogist.DESCRIPTION', 'Description'),
                'Value' => '<br />' . $this->Description
            );
        }

        return new ArrayList($lists);
    }

    public function getObjectImage() {
        return $this->Document();
    }

    public function getObjectLink() {
        return DocPage::get()->first()->Link("show/$this->ID");
    }

    public function getObjectRelated() {
        return DocumentFile::get()->sort('RAND()');
    }

    public function isObjectDisabled() {
        return false;
    }

    public function getObjectTabs() {
        $lists = array();
        if ($this->Texts) {
            $lists[] = array(
                'Title' => _t('Genealogist.TEXTS', 'Texts'),
                'Content' => $this->Texts
            );
        }

        if ($this->People()->Count()) {
            $lists[] = array(
                'Title' => _t('Genealogist.PEOPLE', 'People'),
                'Content' => $this
                        ->customise(array(
//                            'Results' => $this->People()->where("`PublicFigure` = 1 OR `ClassName` = 'Clan'")
                            'Results' => $this->People()->sort('Name ASC')
                        ))
                        ->renderWith('List_Grid')
            );
        }

        return new ArrayList($lists);
    }

    public function getObjectTitle() {
        return $this->getTitle();
    }

}
