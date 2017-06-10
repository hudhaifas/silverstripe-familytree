<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of GenealogistTownHelper
 *
 * @author hudhaifa
 */
class GenealogistTownHelper {

    public static function get_town_born($town) {
        return self::get_town_people($town, 'BirthPlaceID');
    }

    public static function get_town_born_public_figures($town) {
        return self::get_town_people_public_figures($town, 'BirthPlaceID');
    }

    public static function get_town_died($town) {
        return self::get_town_people($town, 'DeathPlaceID');
    }

    public static function get_town_died_public_figures($town) {
        return self::get_town_people_public_figures($town, 'DeathPlaceID');
    }

    public static function get_town_buried($town) {
        return self::get_town_people($town, 'BurialPlaceID');
    }

    public static function get_town_buried_public_figures($town) {
        return self::get_town_people_public_figures($town, 'BurialPlaceID');
    }

    public static function get_town_resident($town) {
        return self::get_town_people($town, 'ResidencePlaceID');
    }

    public static function get_town_resident_public_figures($town) {
        return self::get_town_people_public_figures($town, 'ResidencePlaceID');
    }

    public static function get_town_people($town, $event) {
        if (!$town) {
            return null;
        }

        return DataObject::get('Person')->filter(array(
                    $event => $town->ID
                ))->Sort('YearOrder ASC');
    }

    public static function get_town_people_public_figures($town, $event) {
        if (!$town) {
            return null;
        }

        return DataObject::get('Person')->filter(array(
                    $event => $town->ID,
                    'IsPublicFigure' => 1,
                ))->Sort('YearOrder ASC');
    }

}
