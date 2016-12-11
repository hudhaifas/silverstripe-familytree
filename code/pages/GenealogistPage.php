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
        extends Page {

    private static $has_many = array(
    );
    private static $icon = "genealogist/images/genealogy.png";

    public function getCMSFields() {
        $fields = parent::getCMSFields();

        return $fields;
    }

}

class GenealogistPage_Controller
        extends Page_Controller {

    private static $allowed_actions = array(
        'edit',
        'SearchPerson',
        'doSearchPerson',
        'Form_EditPerson',
        'doEditPerson',
        'Form_AddSons',
        'doAddSons',
        'Form_AddParent',
        'doAddParent',
    );
    private static $url_handlers = array(
        'info/$ID' => 'info',
        'edit/$ID' => 'edit',
    );

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
            return $this->httpError(404, 'That book could not be found!');
        }
    }

    /// Search Person ///
    public function SearchPerson() {
        $fields = new FieldList(
                TextField::create('SearchTerm', _t('Genealogist.SEARCH', 'Search'))
                        ->setAttribute('placeholder', 'City, State, Country, etc...')
        );

        // Create Validators
        $validator = new RequiredFields('SearchTerm');

        $form = new Form($this, 'SearchPerson', $fields, new FieldList(new FormAction('doSearchPerson')), $validator);
        $form->setTemplate('Form_SearchPerson');

        return $form;
    }

    public function doSearchPerson($data, $form) {
        $term = $data['SearchTerm'];
//        die('Hello Search: ' . $term);

        $people = GenealogistHelper::search_all_people($this->request, $term);
        $title = _t('Genealogist.SEARCH_RESULTS', 'Search Results') . ': ' . $term;

        if ($people) {
            $paginate = PaginatedList::create(
                            $people, $this->request
                    )->setPageLength(16)
                    ->setPaginationGetVar('s');


            return $this
                            ->customise(array(
                                'People' => $people,
                                'Results' => $paginate,
                                'Title' => $title
                            ))
                            ->renderWith(array('GenealogistPage', 'Page'));
        } else {
            return $this->httpError(404, 'No books could be found!');
        }
    }

    /// Forms ///
    public function Form_AddParent($sonID = null) {
        // Create fields          
        $fields = new FieldList(
                HiddenField::create('SonID', 'SonID', $sonID), //
                TextField::create('Name', _t('Genealogist.PARENT_NAME', 'Parent Name'))
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doAddParent', _t('Genealogist.ADD_PARENT', 'Add Parent'))
        );

        // Create Validators
        $validator = new RequiredFields();

        return new Form($this, 'Form_AddParent', $fields, $actions, $validator);
    }

    public function doAddParent($data, $form) {
        $id = $data['SonID'];
        $name = $data['Name'];

        GenealogistHelper::add_parent($id, $name);

        return $this->owner->redirectBack();
    }

    public function Form_AddSons($parentID = null) {
        // Create fields          
        $fields = new FieldList(
                HiddenField::create('ParentID', 'ParentID', $parentID), //
                TextareaField::create('Names', _t('Genealogist.SONS_NAMES', 'Sons Names'))
        );

        // Create action
        $actions = new FieldList(
                new FormAction('doAddSons', _t('Genealogist.ADD_SONS', 'Add Sons'))
        );

        // Create Validators
        $validator = new RequiredFields();

        return new Form($this, 'Form_AddSons', $fields, $actions, $validator);
    }

    public function doAddSons($data, $form) {
        $id = $data['ParentID'];
        $names = $data['Names'];

//        die('id: ' . $id);
        GenealogistHelper::add_sons($id, $names);

        return $this->owner->redirectBack();
    }

    public function Form_EditPerson($personID) {
        $person = DataObject::get_by_id('Person', (int) $personID);

        // Create fields          
        $fields = new FieldList(
                HiddenField::create('PersonID', 'PersonID', $personID), //
                TextField::create('Name', 'Name', $person->Name)
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
        return $this->owner->redirectBack();
    }

    /// Utils ///
    public function getDBVersion() {
        return DB::get_conn()->getVersion();
    }

    public function getClans() {
        return Clan::get();
    }

    public function getPerson($id) {
        return DataObject::get_by_id('Person', (int) $id);
    }

    public function getRootClans() {
        return GenealogistHelper::get_root_clans();
    }

}
