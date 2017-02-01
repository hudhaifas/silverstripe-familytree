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
        extends AbstractGenealogy {

    private static $icon = "genealogist/images/genealogy.png";

    public function canCreate($member = false) {
        if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) {
            $member = Member::currentUserID();
        }

        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
    }

}

class GenealogyPage_Controller
        extends AbstractGenealogy_Controller {

    private static $allowed_actions = array(
        'info',
        'suggest',
        'Form_Suggest',
        'doSuggest',
        'Form_Kinship',
    );
    private static $url_handlers = array(
        'person-info/$ID' => 'info',
        'suggest/$ID' => 'suggest',
        'Form_Suggest' => 'Form_Suggest', // list all forms before the index in the handlers array
        'Form_Kinship' => 'Form_Kinship', // list all forms before the index in the handlers array
        '$ID/$Other' => 'index', // any action redirects to the index MUST be added at the end of the array
    );

    public function init() {
        parent::init();

        Requirements::css("genealogist/css/jquery.jOrgChart.css");
        Requirements::css("genealogist/css/jquery.jOrgChart-rtl.css");
        Requirements::css("genealogist/css/genealogy.css");
        Requirements::css("genealogist/css/export.css");

        Requirements::javascript("genealogist/js/jquery.jOrgChart.js");
        Requirements::javascript("genealogist/js/jquery.dragscroll.js");
        Requirements::javascript("genealogist/js/jquery.fullscreen.js");
        Requirements::javascript("genealogist/js/URI.js");
        Requirements::javascript("genealogist/js/html2canvas.js");
        Requirements::javascript("genealogist/js/genealogy.js");
    }

    /// Actions ///
    public function index(SS_HTTPRequest $request) {
        $id = $this->getRequest()->param('ID');
        $other = $this->getRequest()->param('Other');

        $data = $other ? $this->kinship($id, $other) : $this->tree($id);

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

    /// Sub Pages ///
    private function tree($id) {
        $person = $id ? DataObject::get_by_id('Person', (int) $id) : $this->getRootClans()->first();

        if (!$person) {
            return $this->httpError(404, 'No books could be found!');
        }

        $trees = array(
            ArrayData::create(array('Tree' => $person->getDescendantsLeaves()))
        );

        return array(
            'Trees' => new ArrayList($trees),
            'Cols' => 12,
        );
    }

    private function kinship($id, $other) {
        if ($id) {
            $p1 = DataObject::get_by_id('Person', (int) $id);
        }

        if ($other) {
            $p2 = DataObject::get_by_id('Person', (int) $other);
        }

        if (!$p1 || !$p2) {
            return $this->httpError(404, 'No books could be found!');
        }

        $kinships = GenealogistHelper::get_kinships($p1, $p2);
//        var_dump($kinships);
        $trees = array();
        foreach ($kinships as $kinship) {
            $trees[] = ArrayData::create(array('Tree' => $this->getKinshipLeaves($kinship)));
        }

        $count = count($trees);
        $columns = $count > 0 ? 12 / count($trees) : 12;
        $columns = $columns < 4 ? 4 : $columns;

        return array(
            'Trees' => new ArrayList($trees),
            'Cols' => $columns,
            'Title' => $p1->getFirstName() . ' : ' . $p2->getFirstName()
        );
    }

    private function getKinshipLeaves($kinships = array()) {
        $root = $kinships[0];
        $noFemales = !$this->hasPermission() && $root->isFemale();
        $name = $noFemales ? _t('Genealogist.MOTHER', 'Mother') : $root->getPersonName();
        $title = $noFemales ? '' : $root->getFullName();

        $html = <<<HTML
            <li class="{$root->CSSClasses()}">
                <a href="#" title="{$title}" data-url="{$root->InfoLink()}" class="info-item">{$name}</a>
                <ul>
                    {$this->appendLeaf($kinships[1])}
                    {$this->appendLeaf($kinships[2])}
                </ul>
            </li>
HTML;
//        return $html;
        return $this->appendParents($root, $html);
    }

    private function appendParents($person, $html3) {
        if (!$person || !$person->Father()->exists()) {
            return $html3;
        }

        $father = $person->Father();

        $html4 = <<<HTML
            <li class="{$father->CSSClasses()}">
                <a href="#" title="{$father->getFullName()}" data-url="{$father->InfoLink()}" class="info-item">{$father->getPersonName()}</a>
                <ul>
                    {$html3}
                </ul>
            </li>
HTML;

        return $this->appendParents($father, $html4);
    }

    private function appendLeaf($kinship = array(), $index = 0) {
        if ($index > count($kinship) || $kinship[$index] == false) {
            return '';
        }

        $person = $kinship[$index];
        $noFemales = !$this->hasPermission() && $person->isFemale();
        $title = $noFemales ? '' : $person->getFullName();

        $index++;
        $html = <<<HTML
            <li class="{$person->CSSClasses()}">
                <a href="#" title="{$title}" data-url="{$person->InfoLink()}" class="info-item">{$person->getPersonName()}</a>
                <ul>
                    {$this->appendLeaf($kinship, $index)}
                </ul>
            </li>
HTML;

        return $html;
    }

    /// Forms ///
    public function Form_Kinship($personID = null) {
        // Create fields
        $source = $this->hasPermission() ? 'Person' : 'Male';

        $link = $this->AbsoluteLink();
        $fields = new FieldList(
                AutoPersonField::create(
                        'Person1', //
                        _t('Genealogist.FIRST_PERSON', 'First Person'), //
                        '', //
                        null, //
                        null, //
                        $source, //
                        array('IndexedName', 'Name', 'NickName') //
                )->setSourceSort('CHAR_LENGTH(IndexedName) ASC'), //
                AutoPersonField::create(
                        'Person2', //
                        _t('Genealogist.SECOND_PERSON', 'Second Person'), //
                        '', //
                        null, //
                        null, //
                        $source, //
                        array('IndexedName', 'Name', 'NickName') //
                )->setSourceSort('CHAR_LENGTH(IndexedName) ASC')
        );

        // Create action
        $actions = new FieldList(
                $button = new FormAction('findKinship', _t('Genealogist.FIND', 'Find'))
        );
        $button->addExtraClass('kinship-btn');
        $button->setAttribute('data-url', $this->Link());

        // Create Validators
        $validator = new RequiredFields('Person1', 'Person2');

        return new Form($this, 'Form_Kinship', $fields, $actions, $validator);
    }

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

}
