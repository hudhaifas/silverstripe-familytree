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

    private static $group_code = 'genealogists';
    private static $group_title = 'Genealogists';
    private static $group_permission = 'CMS_ACCESS_CMSMain';
    private static $co_group_code = 'co-genealogists';
    private static $co_group_title = 'Co-Genealogists';
    private static $icon = "genealogist/images/icn-genealogy.png";

    public function canCreate($member = false) {
        if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) {
            $member = Member::currentUserID();
        }

        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
    }

    protected function onBeforeWrite() {
        parent::onBeforeWrite();
        $this->getUserGroup();
        $this->getCoUserGroup();
    }

    /**
     * Returns/Creates the genealogists group to assign CMS access.
     *
     * @return Group Librarians group
     */
    protected function getUserGroup() {
        $code = $this->config()->group_code;

        $group = Group::get()->filter('Code', $code)->first();

        if (!$group) {
            $group = new Group();
            $group->Title = $this->config()->group_title;
            $group->Code = $code;

            $group->write();

            $permission = new Permission();
            $permission->Code = $this->config()->group_permission;

            $group->Permissions()->add($permission);
        }

        return $group;
    }

    /**
     * Returns/Creates the genealogists group to assign CMS access.
     *
     * @return Group Librarians group
     */
    protected function getCoUserGroup() {
        $code = $this->config()->co_group_code;

        $group = Group::get()->filter('Code', $code)->first();

        if (!$group) {
            $group = new Group();
            $group->Title = $this->config()->co_group_title;
            $group->Code = $code;

            $group->write();
        }

        return $group;
    }

}

class GenealogyPage_Controller
        extends Page_Controller {

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

        Requirements::css("genealogist/css/vendors/bootstrap-slider.css");
        Requirements::css("genealogist/css/vendors/introjs.css");
        Requirements::css("genealogist/css/vendors/jquery.modal.css");
        Requirements::css("genealogist/css/genealogy.css");

        if ($this->isRTL()) {
            Requirements::css("genealogist/css/vendors/introjs-rtl.css");
            Requirements::css("genealogist/css/genealogy-rtl.css");
        }

        Requirements::javascript("genealogist/js/vendors/jquery.dragscroll.js");
        Requirements::javascript("genealogist/js/vendors/jquery.fullscreen.js");
        Requirements::javascript("genealogist/js/vendors/jquery.panzoom.js");
        Requirements::javascript("genealogist/js/vendors/jquery-scrollto.js");
        Requirements::javascript("genealogist/js/vendors/jquery.modal.js");

        Requirements::javascript("genealogist/js/vendors/URI.js");
        Requirements::javascript("genealogist/js/vendors/html2canvas.js");
        Requirements::javascript("genealogist/js/vendors/bootstrap-slider.js");
        Requirements::javascript("genealogist/js/vendors/intro.js");

        Requirements::javascript("genealogist/js/jquery.jOrgChart.js");
        Requirements::javascript("genealogist/js/genealogy.timeline.js");
        Requirements::javascript("genealogist/js/genealogy.controls.js");
        Requirements::javascript("genealogist/js/genealogy.ajax.js");
        Requirements::javascript("genealogist/js/genealogy.js");
    }

    /// Actions ///
    public function index(SS_HTTPRequest $request) {
        $id = $this->getRequest()->param('ID');
        $other = $this->getRequest()->param('Other');

        if (!$id) {
            return array(
                'LandingPage' => true,
                'RandBranch' => $this->getBranches()->sort('rand()')->first(),
                'RandFigure' => $this->getFigures()->sort('rand()')->first()
            );
        }

        if ($request->isAjax()) {
            $data = $other ? $this->kinship($id, $other) : $this->tree($id);
            return $this
                            ->customise($data)
                            ->renderWith('TheTree');
        }

        $title = $this->getSubTitle($id, $other);

        return array(
            'Tree' => false,
            'Title' => $title
        );
    }

    public function info() {
        $id = $this->getRequest()->param('ID');
        $person = GenealogistHelper::get_person($id);

        if (!$person) {
            return $this->httpError(404, 'That person could not be found!');
        } else {
            return $person->renderWith("TheTree_InfoCard");
        }
    }

    public function suggest() {
        $id = $this->getRequest()->param('ID');
        $person = null;

        if ($id) {
            $person = DataObject::get_by_id('Gender', (int) $id);
        }

        if ($person) {
            return $this
                            ->customise(array(
                                'Gender' => $person,
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
        $person = $id ? DataObject::get_by_id('Gender', (int) $id) : $this->getRootBranches()->last();

        if (!$person) {
            return $this->httpError(404, 'That person could not be found!');
        }

        $isAncestral = $this->getRequest()->getVar('ancestral') ? 1 : 0;

        $config = SiteConfig::current_site_config();
        $title = $config->Title . ' - ';
        $title .= $this->getSubTitle($person);

        return array(
            'Tree' => $person->getDescendants(),
            'MultiRoot' => false,
            'ShowTimeline' => !$isAncestral,
            'Collapsible' => !$isAncestral,
            'PageTitle' => $title
        );
    }

    private function kinship($id, $other) {
        if ($id) {
            $p1 = DataObject::get_by_id('Gender', (int) $id);
        }

        if ($other) {
            $p2 = DataObject::get_by_id('Gender', (int) $other);
        }

        if (!$p1 || !$p2) {
            return $this->httpError(404, 'That person could not be found!');
        }

        $kinships = GenealogistHelper::get_kinships($p1, $p2);

        $roots = array();
        foreach ($kinships as $kinship) {
            $roots[] = $this->getKinshipLeaves($kinship);
        }

        $config = SiteConfig::current_site_config();
        $title = $config->Title . ' - ';
        $title .= $this->getSubTitle($p1, $p2);

        return array(
            'Tree' => $this->virtualRoot($roots),
            'MultiRoot' => true,
            'ShowTimeline' => false,
            'Collapsible' => false,
            'PageTitle' => $title//$p1->getShortName() . ' : ' . $p2->getShortName()
        );
    }

    private function getSubTitle($p1, $p2 = null) {
        if (is_numeric($p1)) {
            $p1 = $p1 ? DataObject::get_by_id('Gender', (int) $p1) : $this->getRootBranches()->last();
        }
        if (is_numeric($p2)) {
            $p2 = DataObject::get_by_id('Gender', (int) $p2);
        }

        if (!$p1 && !$p2) {
            return $this->httpError(404, 'That person could not be found!');
        }
        
        if ($p2) {
            $title = _t('Genealogist.KINSHIP_OF', //
                    "Kinships Between {value1} & {value2}", //
                    array(
                'value1' => $p1->getShortName(),
                'value2' => $p2->getShortName(),
                    )
            );
            return $title;
        } else {
            $isAncestral = $this->getRequest()->getVar('ancestral') ? 1 : 0;
            $title = $isAncestral ?
                    _t('Genealogist.ANCESTORS_OF', //
                            "Family Ancestors of {value}", //
                            array(
                        'value' => $p1->getShortName()
                            )
                    ) :
                    _t('Genealogist.TREE_OF', //
                            "Family Tree of {value}", //
                            array(
                        'value' => $p1->getShortName()
                            )
            );
            return $title;
        }
    }

    private function virtualRoot($trees) {
        $leaves = '';
        foreach ($trees as $tree) {
            $leaves .= $tree;
        }

        return <<<HTML
            <li>
                <a href="#" class="info-item">Root</a>
                <ul>
                    {$leaves}
                </ul>
            </li>
HTML;
    }

    private function getKinshipLeaves($kinships = array()) {
        $root = $kinships[0];

        $html = <<<HTML
            <li class="{$root->CSSClasses()}">
                <a href="#" title="{$root->getFullName()}" data-url="{$root->InfoLink()}" class="info-item">{$root->getPersonName()}</a>
                <ul>
                    {$this->appendLeaf($kinships[1])}
                    {$this->appendLeaf($kinships[2])}
                </ul>
            </li>
HTML;

        return $this->appendParents($root, $html);
    }

    private function appendParents($person, $html3) {
        if (!$person || $person->isClan() || !$person->Father()->exists()) {
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

        $index++;
        $html = <<<HTML
            <li class="{$person->CSSClasses()}">
                <a href="#" title="{$person->getFullName()}" data-url="{$person->InfoLink()}" class="info-item">{$person->getPersonName()}</a>
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
        $source = $this->isAdmin() ? 'Gender' : 'Male';

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
                        )
                        ->setSourceSort('CHAR_LENGTH(IndexedName) ASC')
                        ->setLimit(20), //
                AutoPersonField::create(
                                'Person2', //
                                _t('Genealogist.SECOND_PERSON', 'Second Person'), //
                                '', //
                                null, //
                                null, //
                                $source, //
                                array('IndexedName', 'Name', 'NickName') //
                        )
                        ->setSourceSort('CHAR_LENGTH(IndexedName) ASC')
                        ->setLimit(20)
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

    /// Utils ///
    public function getDBVersion() {
        return DB::get_conn()->getVersion();
    }

    public function getBranches() {
        return GenealogistHelper::get_all_branchs();
    }

    public function getPerson($id) {
        return GenealogistHelper::get_person($id);
    }

    public function getFigures() {
        return DataObject::get('Gender')
                        ->filterAny(array(
                            'ClassName:StartsWith' => 'Branch',
                            'ClassName:StartsWith' => 'Clan',
        ));
    }

    public function getRootBranches() {
//        return GenealogistHelper::get_root_branchs();
        return GenealogistHelper::get_all_branchs();
    }

    /**
     * Checks if the user is an authorized member
     * @return boolean true if the user is an authorized member
     */
    public function isAdmin() {
        return GenealogistHelper::is_genealogists();
    }

}
