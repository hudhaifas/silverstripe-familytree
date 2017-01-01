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
 * @version 1.0, Dec 10, 2016 - 12:27:32 PM
 */
class GenealogistPage
        extends AbstractGenealogy {

    private static $has_many = array(
    );
    private static $icon = "genealogist/images/genealogy.png";

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        return $fields;
    }

    public function canCreate($member = false) {
        if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) {
            $member = Member::currentUserID();
        }

        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
    }

}

class GenealogistPage_Controller
        extends AbstractGenealogy_Controller {

    private static $allowed_actions = array(
        'edit',
        'Form_AddFather',
        'doAddFather',
        'Form_ChangeFather',
        'doChangeFather',
        'Form_ChangeMother',
        'doChangeMother',
        'Form_AddSons',
        'doAddSons',
        'Form_AddDaughters',
        'doAddDaughters',
        'Form_EditPerson',
        'doEditPerson',
        'Form_DeletePerson',
        'doDeletePerson',
    );
    private static $url_handlers = array(
        'edit/$ID' => 'edit',
    );

    public function init() {
        parent::init();
    }

    /// Actions ///
    public function edit() {
        $id = $this->getRequest()->param('ID');

        if ($id) {
            $person = DataObject::get_by_id('Person', (int) $id);
        } else {
            $person = $this->getClans()->first();
        }

        if ($person) {
            return $this
                            ->customise(array(
                                'Person' => $person,
                                'Title' => $person->Name
                            ))
                            ->renderWith(array('GenealogistPage_Edit', 'Page'));
        } else {
            return $this->httpError(404, 'That person could not be found!');
        }
    }

    /// Forms ///
    public function Form_AddFather($personID = null) {
        if ($personID instanceof SS_HTTPRequest) {
            $id = $personID->postVar('PersonID');
        } else {
            $id = $personID;
        }

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('PersonID', 'PersonID', $id), //
                TextField::create('Name', _t('Genealogist.FATHER_NAME', 'Father Name'))
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doAddFather', _t('Genealogist.ADD', 'Add'))
        );

        // Create Validators
        $validator = new RequiredFields('Name');

        return new Form($this, 'Form_AddFather', $fields, $actions, $validator);
    }

    public function doAddFather($data, $form) {
        $personID = $data['PersonID'];
        $fatherName = $data['Name'];

        GenealogistHelper::add_father($personID, $fatherName);

        return $this->owner->redirectBack();
    }

    /////
    public function Form_ChangeFather($personID = null) {
        if ($personID instanceof SS_HTTPRequest) {
            $id = $personID->postVar('PersonID');
        } else {
            $id = $personID;
        }

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('PersonID', 'PersonID', $id), //
                AutoPersonField::create(
                        'FatherID', //
                        _t('Genealogist.FATHER', 'Father'), //
                        '', //
                        null, //
                        null, //
                        'Male', //
                        array('IndexedName', 'Name', 'NickName') //
                )
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doChangeFather', _t('Genealogist.UPDATE', 'Update'))
        );

        // Create Validators
        $validator = new RequiredFields('FatherID');

        return new Form($this, 'Form_ChangeFather', $fields, $actions, $validator);
    }

    public function doChangeFather($data, $form) {
        $personID = $data['PersonID'];
        $fatherID = $data['FatherID'];

        GenealogistHelper::change_father($personID, $fatherID);

        return $this->owner->redirectBack();
    }

    /////
    public function Form_ChangeMother($personID = null) {
        if ($personID instanceof SS_HTTPRequest) {
            $id = $personID->postVar('PersonID');
        } else {
            $id = $personID;
        }

        $person = GenealogistHelper::get_person($id);
        $mothers = array();
        if ($person->Father()->exists()) {
//            die($person->Name);
            $mothers = $person->Father()->Wives()->map();
        }
        if (count($mothers)) {
            $motherField = DropdownField::create(
                            'MotherID', //
                            _t('Genealogist.MOTHER', 'Mother'), //
                            $mothers, //
                            $person->MotherID
                    )->setEmptyString(_t('Librarian.CHOOSE_MOTHER', 'Choose Mother'));
        } else {
            $motherField = AutoPersonField::create(
                            'MotherID', //
                            _t('Genealogist.MOTHER', 'Mother'), //
                            '', //
                            null, //
                            null, //
                            'Female', //
                            array('IndexedName', 'Name', 'NickName') //
            );
        }
        // Create fields          
        $fields = new FieldList(
                HiddenField::create('PersonID', 'PersonID', $id), //
                $motherField
//                AutoPersonField::create(
//                        'MotherID', //
//                        _t('Genealogist.MOTHER', 'Mother'), //
//                        '', //
//                        null, //
//                        null, //
//                        'Female', //
//                        array('IndexedName', 'Name', 'NickName') //
//                )
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doChangeMother', _t('Genealogist.UPDATE', 'Update'))
        );

        // Create Validators
        $validator = new RequiredFields('MotherID');

        return new Form($this, 'Form_ChangeMother', $fields, $actions, $validator);
    }

    public function doChangeMother($data, $form) {
        $personID = $data['PersonID'];
        $methorID = $data['MotherID'];

        GenealogistHelper::change_mother($personID, $methorID);

        return $this->owner->redirectBack();
    }

    /////
    public function Form_AddSons($parentID = null) {
        if ($parentID instanceof SS_HTTPRequest) {
            $id = $parentID->postVar('ParentID');
        } else {
            $id = $parentID;
        }

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('ParentID', 'ParentID', $id), //
                TextareaField::create('Names', _t('Genealogist.SONS_NAMES', 'Sons Names (Use | to seperate the names)'))
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doAddSons', _t('Genealogist.ADD', 'Add'))
        );

        // Create Validators
        $validator = new RequiredFields('Names');

        return new Form($this, 'Form_AddSons', $fields, $actions, $validator);
    }

    public function doAddSons($data, $form) {
        $id = $data['ParentID'];
        $names = $data['Names'];

        GenealogistHelper::add_sons($id, $names);

        return $this->owner->redirectBack();
    }

    /////
    public function Form_AddDaughters($parentID = null) {
        if ($parentID instanceof SS_HTTPRequest) {
            $id = $parentID->postVar('ParentID');
        } else {
            $id = $parentID;
        }

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('ParentID', 'ParentID', $id), //
                TextareaField::create('Names', _t('Genealogist.DAUGHTERS_NAMES', 'Daughters Names (Use | to seperate the names)'))
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doAddDaughters', _t('Genealogist.ADD', 'Add'))
        );

        // Create Validators
        $validator = new RequiredFields('Names');

        return new Form($this, 'Form_AddDaughters', $fields, $actions, $validator);
    }

    public function doAddDaughters($data, $form) {
        $id = $data['ParentID'];
        $names = $data['Names'];

        GenealogistHelper::add_daughters($id, $names);

        return $this->owner->redirectBack();
    }

    /////
    public function Form_EditPerson($personID) {
        if ($personID instanceof SS_HTTPRequest) {
            $id = $personID->postVar('PersonID');
        } else {
            $id = $personID;
        }

        $person = DataObject::get_by_id('Person', (int) $id);

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('PersonID', 'PersonID', $id), //
                TextField::create('Name', _t('Genealogist.NAME', 'Name'), $person->Name), //
                TextField::create('NickName', _t('Genealogist.NICKNAME', 'NickName'), $person->NickName), //
                TextField::create('Note', _t('Genealogist.NOTE', 'Note'), $person->Note), //
                TextField::create('BirthDate', _t('Genealogist.BIRTHDATE', 'Birth Date'), $person->BirthDate), //
                TextField::create('DeathDate', _t('Genealogist.DEATHDATE', 'Death Date'), $person->DeathDate), //
                CheckboxField::create('IsDead', _t('Genealogist.ISDEAD', 'Is Dead'), $person->IsDead), //
                TextareaField::create('Comments', _t('Genealogist.COMMENTS', 'Comments'), $person->Comments) //
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doEditPerson', _t('Genealogist.SAVE', 'Save'))
        );

        // Create Validators
        $validator = new RequiredFields();

        return new Form($this, 'Form_EditPerson', $fields, $actions, $validator);
    }

    public function doEditPerson($data, $form) {
        $id = $data['PersonID'];
        $name = $data['Name'];
        $nickname = $data['NickName'];
        $note = $data['Note'];
        $birthdate = $data['BirthDate'];
        $deathdate = $data['DeathDate'];
        $isDead = $data['IsDead'];
        $comments = $data['Comments'];

        $person = DataObject::get_by_id('Person', (int) $id);

        $person->Name = $name;
        $person->NickName = $nickname;
        $person->Note = $note;
        $person->BirthDate = $birthdate;
        $person->DeathDate = $deathdate;
        $person->IsDead = $isDead;
        $person->Comments = $comments;

        $person->write();

        return $this->owner->redirectBack();
    }

    /////
    public function Form_DeletePerson($personID) {
        if ($personID instanceof SS_HTTPRequest) {
            $id = $personID->postVar('PersonID');
        } else {
            $id = $personID;
        }

//        $person = DataObject::get_by_id('Person', (int) $id);
        // Create fields          
        $fields = new FieldList(
                HiddenField::create('PersonID', 'PersonID', $id)
        );

        // Create action
        $actions = new FieldList(
                $button = new FormAction('doDeletePerson', _t('Genealogist.DELETE', 'Delete'))
        );
        $button->setAttribute('class', 'btn btn-danger');

        // Create Validators
        $validator = new RequiredFields();

        return new Form($this, 'Form_DeletePerson', $fields, $actions, $validator);
    }

    public function doDeletePerson($data, $form) {
        $id = $data['PersonID'];
        $person = DataObject::get_by_id('Person', (int) $id);

        $parent = $person->FatherID;

        GenealogistHelper::delete_person($id);

        return $this->owner->redirect($this->Link('edit/' . $parent));
    }

}
