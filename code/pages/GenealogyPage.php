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
 * @version 1.0, Nov 2, 2016 - 2:45:38 PM
 */
class GenealogyPage
        extends Page {

    private static $has_many = array(
        'Roots' => 'Person'
    );
    private static $icon = "genealogist/images/genealogy.png";

    public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.Roots', GridField::create(
                        'Roots', //
                        'Roots', //
                        $this->Roots(), //
                        GridFieldConfig_RecordEditor::create() //
        ));

        return $fields;
    }

    public function canCreate($member = false) {
        if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) {
            $member = Member::currentUserID();
        }

        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
    }

}

class GenealogyPage_Controller
        extends Page_Controller {

    private static $allowed_actions = array(
        'info',
        'suggest',
        'Form_Suggest',
        'doSuggest',
    );
    private static $url_handlers = array(
        'person-info/$ID' => 'info',
        'suggest/$ID' => 'suggest',
        'Form_Suggest' => 'Form_Suggest', // list all forms before the index in the handlers array
        '$ID/$Other' => 'index', // any action redirects to the index MUST be added at the end of the array
    );

    public function init() {
        parent::init();

        Requirements::css("genealogist/css/jquery.jOrgChart.css");
        Requirements::css("genealogist/css/genealogy.css");
        Requirements::css("genealogist/css/genealogy-rtl.css");

        Requirements::javascript("genealogist/js/jquery.jOrgChart.js");
        Requirements::javascript("genealogist/js/jquery.dragscroll.js");
        Requirements::javascript("genealogist/js/jquery.fullscreen.js");
        Requirements::javascript("genealogist/js/URI.js");
//        Requirements::javascript("genealogist/js/html2canvas.js");
        Requirements::javascript("genealogist/js/genealogy.js");
    }

    /// Actions ///
    public function index(SS_HTTPRequest $request) {
        $id = $this->getRequest()->param('ID');
        $other = $this->getRequest()->param('Other');

        if ($other) {
            return $this->relation($id, $other);
        }

        if ($id) {
            $root = DataObject::get_by_id('Person', (int) $id);
        } else {
            $root = $this->Roots()->first();
        }

        $data = array(
            'Clans' => $root,
//            'Title' => $root->Name,
        );

        if ($request->isAjax()) {
            return $this
                            ->customise($data)
                            ->renderWith('TheTree');
        }

        return $data;
    }

    public function info() {
        $id = $this->getRequest()->param('ID');
        $person = GenealogistHelper::get_person($id);

        return $person->renderWith("Side_Info");
    }

    public function suggest() {
        $id = $this->getRequest()->param('ID');
        $person = null;

        if ($id) {
            $person = DataObject::get_by_id('Person', (int) $id);
        }

        if ($person) {
            return $this
                            ->customise(array(
                                'Person' => $person,
                                'Title' => $person->FullName
                            ))
                            ->renderWith(array('GenealogyPage_Suggest', 'Page'));
        } else {
            return $this
                            ->customise(array())
                            ->renderWith(array('GenealogyPage_SuggestAny', 'Page'));
//            return $this->httpError(404, 'That person could not be found!');
        }
    }

    private function relation($id, $other) {
        if ($id) {
            $p1 = DataObject::get_by_id('Person', (int) $id);
        }

        if ($other) {
            $p2 = DataObject::get_by_id('Person', (int) $other);
        }

        $ancestors = GenealogistHelper::get_common_ancestors($p1, $p2);

        if ($p1 && $p2) {
            return $this
                            ->customise(array(
                                'Ancestors' => $ancestors,
                                'Person1' => $p1,
                                'Person2' => $p2,
                                'Title' => $p1->Name . ' : ' . $p2->Name
                            ))
                            ->renderWith(array('GenealogyPage_Relation', 'Page'));
        } else {
            return $this->httpError(404, 'That person could not be found!');
        }
    }

/// Forms ///
    public function Form_Suggest($personID = null) {
        $subjects = array(
            'General' => _t('Genealogist.SUBJECT', 'Subject'),
            'Name' => _t('Genealogist.NAME', 'Name'),
            'Father' => _t('Genealogist.FATHER', 'Father'),
            'Mother' => _t('Genealogist.MOTHER', 'Mother'),
            'Spouse' => _t('Genealogist.SPOUSE', 'Spouse'),
            'Sons' => _t('Genealogist.SONS', 'Sons'),
            'Daughters' => _t('Genealogist.DAUGHTERS', 'Daughters'),
            'BirthDate' => _t('Genealogist.BIRTHDATE', 'Birth Date'),
            'DeathDate' => _t('Genealogist.DEATHDATE', 'Death Date'),
        );

        // Create fields
        $fields = new FieldList(
                HiddenField::create('PersonID', 'PersonID', $personID), //
                TextField::create('Name', _t('Genealogist.YOUR_NAME', 'Your Name')), //
                EmailField::create('Email', _t('Genealogist.EMAIL', 'Email')), //
                TextField::create('Phone', _t('Genealogist.PHONE', 'Phone')), //
                DropdownField::create('Subject', _t('Genealogist.SUBJECT', 'Subject'), $subjects), //
                TextareaField::create('Message', _t('Genealogist.MESSAGE', 'Message'))
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doSuggest', _t('Genealogist.SEND', 'Send'))
        );

        // Create Validators
        $validator = new RequiredFields('Name', 'Message');

        return new Form($this, 'Form_Suggest', $fields, $actions, $validator);
    }

    public function doSuggest($data, $form) {
        $personID = $data['PersonID'];
        $name = $data['Name'];
        $email = $data['Email'];
        $phone = $data['Phone'];
        $subject = $data['Subject'];
        $message = $data['Message'];

        GenealogistHelper::suggest_change($name, $email, $phone, $personID, $subject, $message);

        return $this->owner->redirectBack();
    }

    public function getDBVersion() {
        return DB::get_conn()->getVersion();
    }

    public function getClans() {
        return GenealogistHelper::get_all_clans();
    }

    public function getPerson($id) {
        return GenealogistHelper::get_person($id);
    }

    public function getRootClans() {
        return GenealogistHelper::get_root_clans();
    }

    /**
     * Checks if the user is an authorized member
     * @return boolean true if the user is an authorized member
     */
    public function hasPermission() {
        return GenealogistHelper::is_genealogists();
    }

}
