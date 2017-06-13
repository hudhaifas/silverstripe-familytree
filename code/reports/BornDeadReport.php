<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Nov 11, 2016 - 9:24:25 AM
 */
class BornDeadReport
        extends SS_Report {

    public function title() {
        return 'Born & Dead Statistics';
    }

    public function sourceRecords($params = null) {
        $report = isset($params['r']) ? $params['r'] : 1;
        $date = isset($params['d']) ? $params['d'] : null;

        switch ($report) {
            case 1:
                return GenealogistHelper::get_born_today($date)->sort('BirthDate');
                break;

            case 2:
                return GenealogistHelper::get_born_this_year($date)->sort('BirthDate');
                break;

            case 3:
                return GenealogistHelper::get_dead_today($date)->sort('BirthDate');
                break;

            case 4:
            default:
                return GenealogistHelper::get_dead_this_year($date)->sort('BirthDate');
                break;
        }
    }

    public function columns() {
        $fields = array(
            'FullName' => array(
                'title' => 'Name',
                'formatting' => '<a href=\"admin/genealogist/Person/EditForm/field/Person/item/{$ID}/edit\" title=\"Edit page\" target=\"_blank\">{$value}</a>'
            ),
            'BirthDate' => 'Birth Date',
            'DeathDate' => 'Death Date',
            'Age' => 'Age',
        );

        return $fields;
    }

    function parameterFields() {
        $reports = array(
            1 => 'Born on this day',
            2 => 'Born on this year',
            3 => 'Dead on this day',
            4 => 'Dead on this year',
        );

//        $branchs = GenealogistHelper::get_main_clans_and_branches()->map();

        $params = new FieldList(
                DropdownField::create(
                        "r", _t('Genealogist.REPORT_TYPE', 'Report Type'), $reports
                ), // 
//                DropdownField::create(
//                        "c", _t('Genealogist.BRANCH', 'Branch'), $branchs
//                ), // 
                DateField::create('d', _t('Genealogist.DATE', 'Date'))
                        ->setConfig('showcalendar', true) //
                        ->setConfig('dateformat', 'dd-MM-yyyy')
        );

        return $params;
    }

}