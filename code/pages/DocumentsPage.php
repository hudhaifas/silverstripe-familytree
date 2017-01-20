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
 * @version 1.0, Jan 19, 2017 - 10:57:33 AM
 */
class DocumentsPage
        extends Page {

    private static $icon = "genealogist/images/genealogy.png";

    public function canCreate($member = false) {
        if (!$member || !(is_a($member, 'Member')) || is_numeric($member)) {
            $member = Member::currentUserID();
        }

        return (DataObject::get($this->owner->class)->count() > 0) ? false : true;
    }

}

class DocumentsPage_Controller
        extends Page_Controller {

    private static $allowed_actions = array(
        'doc',
    );
    private static $url_handlers = array(
        'doc/$ID' => 'doc',
        '$action/$ID' => 'index',
    );

    public function init() {
        parent::init();

        Requirements::css("genealogist/css/docs.css");
        if ($this->isRTL()) {
            Requirements::css("genealogist/css/docs-rtl.css");
        }
    }

    /// Sub Pages ///
    public function index(SS_HTTPRequest $request) {
        $action = $this->getRequest()->param('action');
        $id = $this->getRequest()->param('ID');
        $person = null;

        if ($action == 'person' && $id) {
            $person = DataObject::get_by_id('Person', $id);
            $docs = $person->Documents();
        } else if ($action == 'tag' && $id) {
            $docs = DataObject::get_by_id('DocumentTag', $id)->Files();
        } else {
            $docs = DocumentFile::get();
        }

        $paginate = $this->getPaginated($docs);

        $data = array(
            'Docs' => $docs,
            'Results' => $paginate,
            'Individual' => $person,
        );

        return $data;
    }

    public function doc() {
        $id = $this->getRequest()->param('ID');

        $doc = DocumentFile::get()->byID($id);

        if ($doc) {
            $this->etalage(140, 205);

            return $this
                            ->customise(array(
                                'Doc' => $doc,
                                'Title' => $doc->Title
                            ))
                            ->renderWith(array('DocumentsPage_Doc', 'Page'));
        } else {
            return $this->httpError(404, 'That document could not be found!');
        }
    }

    /// Pagination ///
    public function getPaginated($list, $length = 9) {
        $paginate = new PaginatedList($list, $this->request);
        $paginate->setPageLength($length);

        return $paginate;
    }

    private function etalage($w, $h) {
        $dir = $this->isRTL() ? 'right' : 'left';

        Requirements::customScript(<<<JS
            jQuery(document).ready(function ($) {
                $('#etalage, .etalager').etalage({
                    thumb_image_width: $w,
                    thumb_image_height: $h,
                    source_image_width: 900,
                    source_image_height: 1200,
                    show_hint: true,
                    align: "$dir",
                });
            });
JS
        );
    }

}