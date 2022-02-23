<?php
namespace FKU\FkuSongs\ViewHelpers;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;

class Utf8EncodeViewHelper extends AbstractViewHelper {

	use CompileWithContentArgumentAndRenderStatic;
	
	public function initializeArguments() {
		$this->registerArgument('string', 'string', 'The string to be encoded to utf-8 charset');
	}
	
	public static function renderStatic (array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext) {
		if (! $string = $arguments['string']) {
	        $string = $renderChildrenClosure();
		}	
		return utf8_encode($string);
	}
}

?>