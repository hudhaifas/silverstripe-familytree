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
        'info',
        'town',
    );
    private static $url_handlers = array(
        'person-info/$ID' => 'info',
        'town/$action' => 'town',
        '$ID/$town' => 'index',
    );

    public function init() {
        parent::init();

        Requirements::css("genealogist/css/jquery.jOrgChart.css");
        Requirements::css("genealogist/css/genealogy.css");
        Requirements::css("genealogist/css/genealogy-rtl.css");

        Requirements::javascript("genealogist/js/jquery.jOrgChart.js");
        Requirements::javascript("genealogist/js/jquery.dragscroll.js");
        Requirements::javascript("genealogist/js/jquery.fullscreen.js");
//        Requirements::javascript("genealogist/js/html2canvas.js");
        Requirements::javascript("genealogist/js/genealogy.js");
    }

    public function index(SS_HTTPRequest $request) {
        $id = $this->getRequest()->param('ID');
        $town = $this->getRequest()->param('town');

        if ($id) {
            $root = DataObject::get_by_id('Person', (int) $id);
        } else {
            $root = $this->getClans()->first();
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
        $person = DataObject::get_by_id('Person', (int) $id);

        return $person->renderWith("Side_Info");
    }

    /// Search Book ///
    public function SearchPerson() {
        $context = singleton('Book')->getDefaultSearchContext();
        $fields = $context->getSearchFields();
        $form = new Form($this, 'SearchPerson', $fields, new FieldList(new FormAction('doSearchPerson')));
        $form->setTemplate('Form_SearchPerson');
//        $form->setFormMethod('GET');
//        $form->disableSecurityToken();
//        $form->setFormAction($this->Link());

        return $form;
    }

    public function doSearchPerson($data, $form) {
        $term = $data['Form_SearchPerson_SearchTerm'];

        $books = LibrarianHelper::search_all_books($this->request, $term);
        $title = _t('Genealogist.SEARCH_RESULTS', 'Search Results') . ': ' . $data['Form_SearchPerson_SearchTerm'];

        if ($books) {
            $paginate = $this->getPaginated($books);

            return $this
                            ->customise(array(
                                'Books' => $books,
                                'Results' => $paginate,
                                'Title' => $title
                            ))
                            ->renderWith(array('Library_Books', 'Page'));
        } else {
            return $this->httpError(404, 'No books could be found!');
        }
    }

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
        return Clan::get()->filter(array('FatherID' => 0));
    }

}
