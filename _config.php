<?php

/**
 * Fetches the name of the current module folder name.
 *
 * @return string
 */
if (!defined('FAMILYTREE_DIR')) {
    define('FAMILYTREE_DIR', ltrim(Director::makeRelative(realpath(__DIR__)), DIRECTORY_SEPARATOR));
}

//Display in cms menu
FamilyTreeAdmin::add_extension('SubsiteMenuExtension');