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
 * @version 1.0, Jan 21, 2017 - 4:07:38 PM
 */
class FiguresPage
        extends DataObjectPage {

    public function canCreate($member = false) {
        if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) {
            $member = Member::currentUserID();
        }

        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
    }

}

class FiguresPage_Controller
        extends DataObjectPage_Controller {

    private static $allowed_actions = array(
        'edit',
        'show',
        'Form_EditPerson',
        'doEditPerson',
        'Form_EditSettings',
        'doEditSettings',
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
        'Form_SingleWife',
        'doSingleWife',
        'Form_AddSpouse',
        'doAddSpouse',
        'Form_DeletePerson',
        'doDeletePerson',
    );
    private static $url_handlers = array(
        'edit/$ID/$form' => 'edit',
        'show/$ID' => 'show',
    );

    public function init() {
        parent::init();

        Requirements::css("genealogist/css/vendors/jquery.modal.css");
        Requirements::css("genealogist/css/profile.css");

        if ($this->isRTL()) {
            Requirements::css("genealogist/css/profile-rtl.css");
        }

        Requirements::css(THIRDPARTY_DIR . '/jquery-ui-themes/smoothness/jquery-ui.css');
        Requirements::javascript(THIRDPARTY_DIR . '/jquery-ui/jquery-ui.js');

        Requirements::javascript("genealogist/js/vendors/jquery.modal.js");
        Requirements::javascript("genealogist/js/genealogy.ajax.js");
        Requirements::customScript(<<<JS
            jQuery(document).ready(function () {
                rebindAjaxmodal();
            });
JS
        );
    }

    protected function getObjectsList() {
        if ($this->isAdmin()) {
            return DataObject::get('Person')
                            ->sort('RAND()');
        } else {
            return DataObject::get('Person')
                            ->filterAny(array(
                                'ClassName' => 'Clan',
                                'CanViewType' => 'Anyone',
                            ))->sort('IndexedName ASC');
        }
    }

    protected function getPageLength() {
        return 24;
    }

    protected function searchObjects($list, $keywords) {
        $pieces = GenealogistSearchHelper::explode_keywords($keywords);

        return GenealogistSearchHelper::search_objects($list, $pieces['NameSeries'], $pieces['ClanID']);
    }

    /**
     * Checks if the user is an authorized member
     * @return boolean true if the user is an authorized member
     */
    public function isAdmin() {
        return GenealogistHelper::is_genealogists();
    }

    private $formTemplates = array(
        'self' => 'Person_Edit_Self',
        'settings' => 'Person_Edit_Settings',
        'parents' => 'Person_Edit_Parents',
        'children' => 'Person_Edit_Children',
        'spouses' => 'Person_Edit_Spouses',
        'delete' => 'Person_Delete'
    );

    /// Actions ///
    public function edit() {
        $id = $this->getRequest()->param('ID');
        $form = $this->getRequest()->param('form');

        if ($id) {
            $person = DataObject::get_by_id('Person', (int) $id);
        } else {
            $person = $this->getClans()->first();
        }

        if (!$person || !$person->canEdit()) {
            return $this->httpError(404, 'That person could not be found!');
        }

        if ($person) {
            switch ($form) {
                case 'self':
                case 'settings':
                case 'parents':
                case 'children':
                case 'spouses':
                case 'delete':
                    $renderer = array($this->formTemplates[$form]);
                    break;

                default:
                    $renderer = $this->getRequest()->isAjax() ?
                            array('Person_Edit') :
                            array('FiguresPage_Edit', 'Page');
                    break;
            }

            return $this
                            ->customise(array(
                                'Person' => $person,
                                'Title' => $person->Name
                            ))
                            ->renderWith($renderer);
        } else {
            return $this->httpError(404, 'That person could not be found!');
        }
    }

    public function show() {
        $id = $this->getRequest()->param('ID');
        $single = DataObject::get('Person')->filter(array(
                    'ID' => $id
                ))->first();

        if (!$single || !$single->canView()) {
            return $this->httpError(404, 'That person could not be found!');
        }

        $align = $this->isRTL() == 'rtl' ? 'right' : 'left';

        Requirements::customScript(<<<JS
            $(document).ready(function () {
                $('.imgBox').imgZoom({
                    boxWidth: 500,
                    boxHeight: 500,
                    marginLeft: 5,
                    align: '{$align}',
                    origin: 'data-origin'
                });
            });
JS
        );

        if ($single) {
            return $this
                            ->customise(array(
                                'Person' => $single,
                                'Title' => $single->Title
                            ))
                            ->renderWith(array('FiguresPage_Profile', 'FiguresPage'));
        } else {
            return $this->httpError(404, 'That object could not be found!');
        }
    }

    /// Forms ///
    public function Form_EditPerson($personID) {
        if ($personID instanceof SS_HTTPRequest) {
            $id = $personID->postVar('PersonID');
        } else {
            $id = $personID;
        }

        $person = DataObject::get_by_id('Person', (int) $id);

        $towns = DataObject::get('Town')->map();

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('PersonID', 'PersonID', $id), //
                TextField::create('Name', _t('Genealogist.NAME', 'Name'), $person->Name), //
                CheckboxField::create('IsPrivate', _t('Genealogist.IS_PRIVATE', 'Is Private'), $person->IsPrivate), //
                TextField::create('NickName', _t('Genealogist.NICKNAME', 'NickName'), $person->NickName), //
                TextField::create('Note', _t('Genealogist.NOTE', 'Note'), $person->Note), //
                TextField::create('BirthDate', _t('Genealogist.BIRTHDATE', 'Birth Date'), $person->BirthDate), //
                DropdownField::create(
                        'BirthPlaceID', //
                        _t('Genealogist.BIRTHPLACE', 'Birth Place'), //
                        $towns, //
                        $person->BirthPlaceID
                )->setEmptyString(_t('Genealogist.BIRTHPLACE', 'Birth Place')), //
                CheckboxField::create('BirthDateEstimated', _t('Genealogist.BIRTHDATE_ESTIMATED', 'Birth Date Estimated'), $person->BirthDateEstimated), //
                TextField::create('DeathDate', _t('Genealogist.DEATHDATE', 'Death Date'), $person->DeathDate), //
                DropdownField::create(
                        'DeathPlaceID', //
                        _t('Genealogist.DEATHPLACE', 'Death Place'), //
                        $towns, //
                        $person->DeathPlaceID
                )->setEmptyString(_t('Genealogist.DEATHPLACE', 'Death Place')), //
                DropdownField::create(
                        'BurialPlaceID', //
                        _t('Genealogist.BURIALPLACE', 'Burial Place'), //
                        $towns, //
                        $person->BurialPlaceID
                )->setEmptyString(_t('Genealogist.BURIALPLACE', 'Burial Place')), //
                CheckboxField::create('DeathDateEstimated', _t('Genealogist.DEATHDATE_ESTIMATED', 'Death Date Estimated'), $person->DeathDateEstimated), //
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

        $person = DataObject::get_by_id('Person', (int) $id);

        $form->saveInto($person);
        $person->write();

        return $this->owner->redirectBack();
    }

    public function Form_EditSettings($personID) {
        if ($personID instanceof SS_HTTPRequest) {
            $id = $personID->postVar('PersonID');
        } else {
            $id = $personID;
        }

        $person = DataObject::get_by_id('Person', (int) $id);

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

        $viewerGroupsField = ListboxField::create("ViewerGroups", _t('Genealogist.VIEWER_GROUPS', "Viewer Groups"))
                ->setMultiple(true)
                ->setSource($groupsMap)
                ->setValue(null, $person)
                ->setAttribute('data-placeholder', _t('Genealogist.GROUP_PLACEHOLDER', 'Click to select group'));

        $viewerMembersField = ListboxField::create("ViewerMembers", _t('Genealogist.VIEWER_MEMBERS', "Viewer Users"))
                ->setMultiple(true)
                ->setSource($membersMap)
                ->setValue(null, $person)
                ->setAttribute('data-placeholder', _t('Genealogist.MEMBER_PLACEHOLDER', 'Click to select user'));

        $editorGroupsField = ListboxField::create("EditorGroups", _t('Genealogist.EDITOR_GROUPS', "Editor Groups"))
                ->setMultiple(true)
                ->setSource($groupsMap)
                ->setValue(null, $person)
                ->setAttribute('data-placeholder', _t('Genealogist.GROUP_PLACEHOLDER', 'Click to select group'));

        $editorMembersField = ListboxField::create("EditorMembers", _t('Genealogist.EDITOR_MEMBERS', "Editor Users"))
                ->setMultiple(true)
                ->setSource($membersMap)
                ->setValue(null, $person)
                ->setAttribute('data-placeholder', _t('Genealogist.MEMBER_PLACEHOLDER', 'Click to select user'));

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('PersonID', 'PersonID', $id), //
                DropdownField::create(
                        'CanViewType', //
                        _t('Genealogist.MOTHER', 'Mother'), //
                        singleton('Person')->dbObject('CanViewType')->enumValues(), $person->CanViewType
                ), //
                $viewerGroupsField, //
                $viewerMembersField, //
                DropdownField::create(
                        'CanEditType', //
                        _t('Genealogist.MOTHER', 'Mother'), //
                        singleton('Person')->dbObject('CanEditType')->enumValues(), $person->CanEditType
                ), //
                $editorGroupsField, //
                $editorMembersField
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doEditSettings', _t('Genealogist.SAVE', 'Save'))
        );

        // Create Validators
        $validator = new RequiredFields();

        return new Form($this, 'Form_EditSettings', $fields, $actions, $validator);
    }

    public function doEditSettings($data, $form) {
        $id = $data['PersonID'];

        $person = DataObject::get_by_id('Person', (int) $id);
        var_dump($data['CanViewType']);
        $form->saveInto($person);
        var_dump($person->CanViewType);
        $person->write();

        return $this->owner->redirectBack();
    }

    /// Append Father int the tree ///
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

    /// Change Father ///
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
                        ->setSourceSort('CHAR_LENGTH(IndexedName) ASC')
                        ->setLimit(20)
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

    /// Change Mother ///
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
                    )->setEmptyString(_t('Genealogist.CHOOSE_MOTHER', 'Choose Mother'));
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

    /// Add Sons ///
    public function Form_AddSons($parentID = null) {
        if ($parentID instanceof SS_HTTPRequest) {
            $id = $parentID->postVar('ParentID');
        } else {
            $id = $parentID;
        }

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('ParentID', 'ParentID', $id), //
                TextField::create('Names', _t('Genealogist.SONS_NAMES', 'Sons Names (Use | to seperate the names)'))
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

    /// Add Daughters ///
    public function Form_AddDaughters($parentID = null) {
        if ($parentID instanceof SS_HTTPRequest) {
            $id = $parentID->postVar('ParentID');
        } else {
            $id = $parentID;
        }

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('ParentID', 'ParentID', $id), //
                TextField::create('Names', _t('Genealogist.DAUGHTERS_NAMES', 'Daughters Names (Use | to seperate the names)'))
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

    /// Has single wife ///
    public function Form_SingleWife($personID) {
        if ($personID instanceof SS_HTTPRequest) {
            $id = $personID->postVar('PersonID');
        } else {
            $id = $personID;
        }

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('PersonID', 'PersonID', $id)
        );

        // Create action
        $actions = new FieldList(
                $button = new FormAction('doSingleWife', _t('Genealogist.ASSIGN_MOTHER', 'Assign Mother To All Children'))
        );
        $button->setAttribute('class', 'btn btn-danger');

        // Create Validators
        $validator = new RequiredFields();

        return new Form($this, 'Form_SingleWife', $fields, $actions, $validator);
    }

    public function doSingleWife($data, $form) {
        $id = $data['PersonID'];
        GenealogistHelper::single_wife($id);

        return $this->owner->redirectBack();
    }

    /// Add spouse ///
    public function Form_AddSpouse($personID) {
        if ($personID instanceof SS_HTTPRequest) {
            $id = $personID->postVar('PersonID');
        } else {
            $id = $personID;
        }

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('PersonID', 'PersonID', $id), //
                TextField::create('Name', _t('Genealogist.SPOUSE_NAME', 'Spouse Name'))
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doAddSpouse', _t('Genealogist.ADD', 'Add'))
        );

        // Create Validators
        $validator = new RequiredFields('Name');

        return new Form($this, 'Form_AddSpouse', $fields, $actions, $validator);
    }

    public function doAddSpouse($data, $form) {
        $id = $data['PersonID'];
        $spouseID = null;
        $spouseName = $data['Name'];

        GenealogistHelper::add_spouse($id, $spouseID, $spouseName);

        return $this->owner->redirectBack();
    }

    /// Delete Person ///
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
        $button->useButtonTag = true;
        $button->addExtraClass('btn btn-danger');

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

    /// Utils ///
    public function getClans() {
        return GenealogistHelper::get_all_clans();
    }

    public function getPerson($id) {
        return GenealogistHelper::get_person($id);
    }

    public function getRootClans() {
        return GenealogistHelper::get_root_clans();
    }

}
