<?php
namespace FKU\FkuSongs\ViewHelpers;

/***************************************************************
*  Copyright notice
*
*  (c) 2014 Daniel Widmer <daniel.widmer@fku.ch>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
* Returns ...
*
* @package fku_songs
*/

class IfInArrayViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

    public function initializeArguments() {
		parent::initializeArguments();
        $this->registerArgument('needle', 'string', 'Item to be looked for', true);
        $this->registerArgument('haystack', 'mixed', 'Array to be looked in', true);
    }
	
    protected static function evaluateCondition($arguments = NULL) {
		$needle = $arguments['needle'];
		$haystack = $arguments['haystack'];

		if ($needle === NULL) {
			return false;
		}
		if (is_string($haystack)) {
			$haystack = \TYPO3\CMS\Core\Utility\GeneralUtility::trimExplode(',', $haystack, true);
		}
		if (is_object($haystack) and $haystack instanceof \TYPO3\CMS\Extbase\Persistence\ObjectStorage) {
			$haystack = $haystack->toArray();
		}
        return is_array($haystack) && in_array($needle, $haystack);
	}
}
?>