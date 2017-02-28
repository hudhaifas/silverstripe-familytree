<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Hudhaifa Shatnawi <hudhaifa.shatnawi@gmail.com>
 * @version 1.0, Jan 8, 2017 - 7:20:28 AM
 */
class PersonStats
        extends DataObject {

    private static $db = array(
        'Sons' => 'Int',
        'Daughters' => 'Int',
        'Males' => 'Int',
        'Females' => 'Int',
        'Total' => 'Int',
        'LiveSons' => 'Int',
        'LiveDaughters' => 'Int',
        'LiveMales' => 'Int',
        'LiveFemales' => 'Int',
        'LiveTotal' => 'Int',
        'MinYear' => 'Int',
        'MaxYear' => 'Int',
    );
    private static $has_one = array(
        'Person' => 'Person',
    );

}
