<?php
namespace FKU\FkuSongs\ViewHelpers;

/**
 * Checks if a variable is an array or not
 *
 */

class IfIsArrayViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

    public function initializeArguments() {
        $this->registerArgument('subject', 'mixed', 'Variable to be checked if it is an array', true);
    }

    protected static function evaluateCondition($arguments = NULL) {
        return is_array($arguments['subject']);
	}
}

?>