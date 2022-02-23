<?php
namespace FKU\FkuSongs\ViewHelpers;

/**
 * Checks if the given song is marked as rejected in the given source (collection)
 */
 
class IfSongRejectedViewHelper extends \TYPO3Fluid\Fluid\Core\ViewHelper\AbstractConditionViewHelper {

	static $number;

	public function initializeArguments() {
		$this->registerArgument('song', 'FKU\FkuSongs\Domain\Model\Song', 'Song that has to be checked');
		$this->registerArgument('source', 'integer', 'Source UID in which the song has to be checked');
		$this->registerArgument('number', 'string', 'Variable name to return song number in given source');
	}
	
	protected static function evaluateCondition($arguments = NULL) {
		$song = $arguments['song'];
		$source = $arguments['source'];
		self::$number = NULL;
		
		$collections = $song->getCollection();
		if (count($collections) == 0) {
			return true;
		}
		
		foreach ($collections as $collection) {
			if ($collection->getSource()->getUid() == $source) {
				if ($collection->getNumber() > 0) {
					self::$number = $collection->getNumber();
					if ($collection->isRejected()) {
						return true;
					} else {
						return false;
					}
				}
			}
		}
	}
	
}

?>

