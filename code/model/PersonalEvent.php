<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Mar 27, 2017 - 1:33:25 PM
 */
class PersonalEvent
        extends DataObject
        implements SingleDataObject {

    private static $db = array(
        'EventTitle' => 'Varchar(255)',
        'EventDate' => 'Date',
        'DatePrecision' => 'Enum("Accurate, Estimated, Calculated", "Accurate")',
//        'EventPlace' => 'Varchar(255)',
        'EventContent' => 'HTMLText',
        'EventType' => 'Enum("Birth, Death, Marriage, Resident, Graduated, Custom", "Custom")',
        'IsEdited' => 'Boolean',
        'Relation' => 'Varchar(25)',
        'Age' => 'Varchar(255)',
    );
    private static $has_one = array(
        'Person' => 'Person',
        'RelatedPerson' => 'Person',
        'EventPlace' => 'Town',
    );
    private static $summary_fields = array(
        'Title',
        'Person.Name',
        'RelatedPerson.Name',
        'EventDate',
        'EventPlace.Title',
        'Age',
        'EventType',
    );
    private static $default_sort = 'EventDate';

    public function fieldLabels($includerelations = true) {
        $labels = parent::fieldLabels($includerelations);

        $labels['DatePrecision'] = _t('Genealogist.Date_Precision', 'Date Precision');
        $labels['IsEdited'] = _t('Genealogist.Date_Precision', 'Date Precision');

        $labels['Title'] = _t('Genealogist.EVENT_TITLE', 'Event Title');
        $labels['EventTitle'] = _t('Genealogist.EVENT_TITLE', 'Event Title');
        $labels['Person.Name'] = _t('Genealogist.PERSON', 'Person');
        $labels['RelatedPerson.Name'] = _t('Genealogist.RELATED_PERSON', 'Related Person');
        $labels['EventDate'] = _t('Genealogist.EVENT_DATE', 'Event Date');
        $labels['EventPlace'] = _t('Genealogist.EVENT_PLACE', 'Event Place');
        $labels['Age'] = _t('Genealogist.AGE', 'Age');
        $labels['EventType'] = _t('Genealogist.EVENT_TYPE', 'Event Type');

        return $labels;
    }

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        if ($field = $fields->fieldByName('Root.Main.EventDate')) {
            $field->setConfig('showcalendar', true);
            $field->setConfig('dateformat', 'dd-MM-yyyy');
        }
        return $fields;
    }

    protected function onBeforeWrite() {
        parent::onBeforeWrite();

        if ($this->EventDate) {
            $this->Age = GenealogistEventsHelper::age_at_event(
                            GenealogistEventsHelper::get_birth_date($this->Person()), //
                            $this->EventDate
            );
        }

        $content = GenealogistEventsHelper::generate_event_content($this, $this->Person(), $this->RelatedPerson());
        if ($content) {
            $this->EventContent = $content;
        }
    }

    public function canCreate($member = null) {
        return $this->Person() && $this->Person()->canCreate($member) && $this->RelatedPerson() && $this->RelatedPerson()->canCreate($member);
    }

    public function canView($member = false) {
        return $this->Person() && ($this->Person()->canView($member) || $this->Person()->IsPublicFigure) &&
                $this->RelatedPerson() && ($this->RelatedPerson()->canView($member) || $this->RelatedPerson()->IsPublicFigure);
    }

    public function canDelete($member = false) {
        return $this->Person() && $this->Person()->canDelete($member) && $this->RelatedPerson() && $this->RelatedPerson()->canDelete($member);
    }

    public function canEdit($member = false) {
        return $this->Person() && $this->Person()->canEdit($member) && $this->RelatedPerson() && $this->RelatedPerson()->canEdit($member);
    }

    public function getTitle() {
        return _t('Genealogist.' . $this->EventTitle, $this->EventTitle);
    }

    public function getPlaceTitle() {
        return $this->EventPlace()->getTitle($this->EventDate);
    }

    public function getEventAgo() {
        if ($this->EventDate) {
            $date = new Date();
            $date->setValue($this->EventDate);
            return $date->TimeDiff();
        }

        return null;
    }

    public function getContent() {
        return _t('Genealogist.' . $this->EventContent, $this->EventContent);
//        return GenealogistEventsHelper::generate_event_content($this->Person(), $this->RelatedPerson(), $this->EventType, $this->EventRelation, $this->DatePrecision);
    }

    public function getDateValue() {
        if ($this->DatePrecision == 'Accurate') {
//            $value = _t("Genealogist.ON_DATE", 'on {date}', array(
//                'age' => $this->EventDate
//            ));
            $value = $this->EventDate;
        } else {
            $date = new DateTime($this->EventDate);
//            $value = _t("Genealogist.IN_DATE", 'in {date}', array(
//                'age' => $date->format('Y')
//            ));
            $value = $date->format('Y');
        }
        return $value;
    }

    public function isAccurateDate() {
        return $this->DatePrecision == 'Accurate';
    }

    public function isEstimatedDate() {
        return $this->DatePrecision == 'Estimated';
    }

    public function isCalculatedDate() {
        return $this->DatePrecision == 'Calculated';
    }

    public function getObjectImage() {
        return $this->Person()->Photo();
    }

    public function getObjectDefaultImage() {
        return $this->Person()->getObjectDefaultImage();
    }

    public function getObjectLink() {
        return $this->Person()->getObjectLink();
    }

    public function getObjectRelated() {
        return null;
    }

    public function getObjectSummary() {
        return $this->Person()->getObjectSummary();
    }

    public function getObjectTabs() {
        return null;
    }

    public function getObjectTitle() {
//        return $this->getTitle();
//        return $this->EventType . ' ' . $this->Person()->getFullName();
        return $this->Person()->getFullName();
    }

    public function isObjectDisabled() {
        return !$this->canView();
    }

}
