<?php
/**
 * Contao Module Device
 * Copyright (C) 2015 Sven Baumann
 *
 * PHP version 5
 *
 * @package   contaoblackforest/contao-module-device
 * @file      config.php
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   LGPL-3.0+
 * @copyright ContaoBlackforest 2015
 */

$GLOBALS['TL_HOOKS']['loadDataContainer'][] = array('ContaoBlackforest\Backend\DCA\Module\Device', 'addChildRecordCallback');
$GLOBALS['TL_HOOKS']['parseWidget'][]       = array('ContaoBlackforest\Backend\DCA\Module\Device', 'addVisibleDeviceToWizard');
$GLOBALS['TL_HOOKS']['isVisibleElement'][]  = array('ContaoBlackforest\Frontend\Module\Device', 'visibleDevice');
