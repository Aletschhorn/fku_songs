<?php
namespace FKU\FkuSongs\ViewHelpers;

class ExplodeViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper {

    /**
     * Explodes content of array in one-line expression for link argument
     *
     * @param mixed $object
     * @return string
     */
	public function render($object = NULL) {
		if ($object === NULL) {
			$object = $this->renderChildren();
		}

		if (is_array($object)) {
			$string = self::separate($object, '');
			return substr($string,0,-1);
		} else {
			return NULL;
		}
	}
	
	private function separate($arr, $str, $prefix = '') {
		foreach ($arr as $key => $value) {
			if (is_array($value)) {
				$str = self::separate($value,$str,$key);
			} else {
				if ($prefix) {
					$str .= $prefix . '.';
				}
				$str .=  $key . ':' . $value . ',';
			}
		}
		return $str;
	}
}

?>