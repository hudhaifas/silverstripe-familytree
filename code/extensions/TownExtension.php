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
 * @version 1.0, May 25, 2017 - 12:52:10 PM
 */
class TownExtension
        extends DataExtension {

    private static $many_many = array(
        'Towns' => 'Town',
    );

    public function extraTabs(&$lists) {
        $towns = $this->owner->Towns();
        if ($towns->Count()) {
            $lists[] = array(
                'Title' => _t('Genealogist.TOWNS', 'Towns'),
                'Content' => $this->owner
                        ->customise(array(
                            'Results' => $towns
                        ))
                        ->renderWith('List_Grid')
            );
        }
    }

    public function updateCMSFields(FieldList $fields) {
        $field = $fields->fieldByName('Root.Towns.Towns');
        if ($field != null) {
//        $config = GridFieldConfig::create();
            $config = $field->getConfig();

            $config->removeComponentsByType('GridFieldAddExistingAutocompleter');
            $config->addComponent(new GridFieldAddExistingAutocompleter('buttons-before-right', array('IndexedName', 'Name')));

            $field->setConfig($config);
        }
    }

}
