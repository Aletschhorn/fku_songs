<?php
defined('TYPO3_MODE') or die();

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'FkuSongs',
	'List',
	'Songs'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'FkuSongs',
	'Reporting',
	'Song Reporting'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'FkuSongs',
	'Charts',
	'Song Charts'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'FkuSongs',
	'Statistics',
	'Song Statistics'
);

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	'FkuSongs',
	'Slugs',
	'Create slugs for songs'
);

?>