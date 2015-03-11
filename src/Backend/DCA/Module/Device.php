<?php
/**
 * Contao Module Device
 * Copyright (C) 2015 Sven Baumann
 *
 * PHP version 5
 *
 * @package   contaoblackforest/contao-module-device
 * @file      Device.php
 * @author    Sven Baumann <baumann.sv@gmail.com>
 * @author    Dominik Tomasi <dominik.tomasi@gmail.com>
 * @license   LGPL-3.0+
 * @copyright ContaoBlackforest 2015
 */


namespace ContaoBlackforest\Backend\DCA\Module;


/**
 * Class Device
 *
 * @package ContaoBlackforest\Backend\DCA\Module
 */
class Device extends \Backend
{

	/**
	 * set to the module type if device selected
	 *
	 * @param array
	 *
	 * @return string
	 */
	public function addDeviceVisibility($row)
	{
		$strName = \Input::get('table');

		$callback = &$GLOBALS['TL_DCA'][$strName]['list']['sorting']['child_record_callback'];

		$reflectionClass = new \ReflectionClass($this);

		foreach ($callback as $k => $v) {
			if ($v == $reflectionClass->name || $v == __FUNCTION__) {
				unset($callback[$k]);
			}
		}
		$callback = array_values($callback);

		$return = static::importStatic($callback[0])
						->$callback[1](
							$row
						);

		if ($row['deviceSelect']) {
			$devices = unserialize($row['deviceSelect']);

			if (count($devices) > 0) {
				$string = '';
				foreach ($devices as $device) {
					$string .= ' <span style="color:#b3b3b3;">[' . $GLOBALS['TL_LANG'][$strName][$device] . ']';
				}

				$return   = explode('</span>', $return);
				$return[] = $string;
				$return   = implode('</span>', $return);
			}
		}


		array_insert($callback, 0, array($reflectionClass->name, __FUNCTION__));

		return $return;
	}


	/**
	 * set to the module type if device selected in content list
	 *
	 * @param array
	 *
	 * @return string
	 */
	public function addDeviceVisibilityByContentList($row)
	{
		$strName = \Input::get('table');

		$callback = &$GLOBALS['TL_DCA'][$strName]['list']['sorting']['child_record_callback'];

		$reflectionClass = new \ReflectionClass($this);

		foreach ($callback as $k => $v) {
			if ($v == $reflectionClass->name || $v == __FUNCTION__) {
				unset($callback[$k]);
			}
		}
		$callback = array_values($callback);

		$return = static::importStatic($callback[0])
						->$callback[1](
							$row
						);

		if ($row['type'] === 'module') {
			$module = \ModuleModel::findByPk($row['module']);

			if ($module) {
				if ($module->deviceSelect) {
					$devices = unserialize($module->deviceSelect);

					if ($devices) {
						$string = '';
						foreach ($devices as $device) {
							$string .= ' (' . $GLOBALS['TL_LANG'][$strName]['deviceVisibility'] . ' ' . $GLOBALS['TL_LANG'][$strName][$device] . ')';
						}

						$return    = explode('</div>', $return);
						$return[0] = str_replace($GLOBALS['TL_LANG']['CTE'][$row['type']][0], $GLOBALS['TL_LANG']['CTE'][$row['type']][0] . $string, $return[0]);
						$return    = implode('</div>', $return);
					}
				}
			}
		}

		array_insert($callback, 0, array($reflectionClass->name, __FUNCTION__));

		return $return;
	}


	public function addChildRecordCallback($table)
	{
		if ($table === 'tl_module') {
			$callback = &$GLOBALS['TL_DCA'][$table]['list']['sorting']['child_record_callback'];

			array_insert($callback, 0, array('ContaoBlackforest\Backend\DCA\Module\Device', 'addDeviceVisibility'));
		}

		if ($table === 'tl_content') {
			$callback = &$GLOBALS['TL_DCA'][$table]['list']['sorting']['child_record_callback'];

			array_insert($callback, 0, array('ContaoBlackforest\Backend\DCA\Module\Device', 'addDeviceVisibilityByContentList'));
		}
	}


	public function addVisibleDeviceToWizard($buffer, $wizard)
	{
		if ($wizard->id === 'modules' || $wizard->id === 'module') {
			switch ($wizard->id) {
				case 'modules':
					$buffer = $this->multiWizard($buffer, $wizard);

					break;
				case 'module':
					$buffer = $this->singleWizard($buffer);

					break;
				default:
					break;
			}
		}

		return $buffer;
	}


	protected function multiWizard($buffer, $wizard)
	{
		foreach ($wizard->value as $value) {
			$module = \ModuleModel::findByPk($value['mod']);

			if ($module && $module->deviceSelect) {
				$this->loadLanguageFile('tl_module');

				$option = 'value="' . $module->id . '"';
				$endTag = ']</option>';

				$buffer = explode($option, $buffer);

				$i = 0;
				foreach ($buffer as &$row) {
					if ($i > 0) {
						$row = explode($endTag, $row);

						if ($module->deviceSelect) {
							$devices = unserialize($module->deviceSelect);

							if ($devices) {
								foreach ($devices as $device) {
									$row[0] .= ' || ' . $GLOBALS['TL_LANG'][$module->getTable()][$device];
								}
							}
						}

						$row = implode($endTag, $row);
					}
					$i++;
				}
				$buffer = implode($option, $buffer);
			}
		}

		return $buffer;
	}


	protected function singleWizard($buffer)
	{
		$modules = \ModuleModel::findAll();

		if ($modules) {
			while ($modules->next()) {
				if ($modules->deviceSelect && $modules->deviceSelect) {
					$this->loadLanguageFile('tl_module');

					$option = 'value="' . $modules->id . '"';
					$endTag = ')</option>';

					$buffer = explode($option, $buffer);

					$i = 0;
					foreach ($buffer as &$row) {
						if ($i > 0) {
							$row = explode($endTag, $row);

							if ($modules->deviceSelect) {
								$devices = unserialize($modules->deviceSelect);

								if ($devices) {
									foreach ($devices as $device) {
										$row[0] .= ' || ' . $GLOBALS['TL_LANG'][$modules->current()
																						->getTable()][$device];
									}
								}
							}

							$row = implode($endTag, $row);
						}
						$i++;
					}
					$buffer = implode($option, $buffer);
				}
			}
		}

		return $buffer;
	}
}
