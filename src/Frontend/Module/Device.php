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


namespace ContaoBlackforest\Frontend\Module;


/**
 * Class Device
 *
 * @package ContaoBlackforest\Frontend\Module
 */
class Device extends \Controller
{
	/**
	 * control frontend visibility
	 *
	 * @param $element
	 * @param $return
	 *
	 * @return bool
	 */
	public function visibleDevice($element, $return)
	{
		$method = explode('_', $element->getTable())[1] . 'Visible';

		if (method_exists($this, $method)) {
			return $this->$method($element);
		}

		if ($element->type === 'module') {
			if (method_exists($this, $method)) {
				return $this->$method($element);
			}
		}

		return $return;
	}


	protected function moduleVisible($element)
	{
		$method = 'is' . str_replace($element->deviceSelect[0], strtoupper($element->deviceSelect[0]), $element->deviceSelect);

		if (method_exists($this, $method)) {
			return $this->$method();
		}

		return true;
	}


	protected function contentVisible($element)
	{
		if ($element->type === 'module') {
			$module = \ModuleModel::findByPk($element->module);

			if ($module && $module->deviceSelect && $module->deviceSelect != $GLOBALS['TL_DCA'][$module->getTable()]['fields']['deviceSelect']['default']) {
				$method = 'is' . str_replace($module->deviceSelect[0], strtoupper($module->deviceSelect[0]), $module->deviceSelect);

				if (method_exists($this, $method)) {
					return $this->$method();
				}
			}
		}

		return true;
	}


	protected function isDesktop()
	{
		if (!$GLOBALS['container']['mobile-detect']->isMobile()) {
			return true;
		}

		return false;
	}


	protected function isMobile()
	{
		if ($GLOBALS['container']['mobile-detect']->isMobile()) {
			return true;
		}

		return false;
	}


	protected function isPhone()
	{
		if (!$GLOBALS['container']['mobile-detect']->isTablet() && $GLOBALS['container']['mobile-detect']->isMobile()) {
			return true;
		}

		return false;
	}


	protected function isTablet()
	{
		if ($GLOBALS['container']['mobile-detect']->isTablet()) {
			return true;
		}

		return false;
	}
}