<?php
/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Core
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Table tl_article
 */
$tl_module = &$GLOBALS['TL_DCA']['tl_module'];

// Palettes
foreach ($tl_module['palettes'] as &$pallet) {
	if (!is_array($pallet) && stristr($pallet, 'protected_legend')) {
		$string = '{device_legend:hide},deviceSelect;{protected_legend';

		$pallet = str_replace('{protected_legend', $string, $pallet);
	}
}

//Fields
$fields = array
(
	'deviceSelect' => array
	(
		'label'     => &$GLOBALS['TL_LANG']['tl_module']['deviceSelect'],
		'default'   => '--',
		'exclude'   => true,
		'inputType' => 'select',
		'options'   => array('--', 'desktop', 'mobile', 'phone', 'tablet'),
		'reference' => &$GLOBALS['TL_LANG']['tl_module'],
		'sql'       => "varchar(32) NOT NULL default ''"
	)
);

$tl_module['fields'] = array_merge($tl_module['fields'], $fields);
