<?php
defined('TYPO3_MODE') or die();

foreach (['song', 'collection', 'reporting', 'source', 'keyword', 'link'] as $table) {
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_fkusongs_domain_model_' . $table);
	\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_fkusongs_domain_model_' . $table, 
	'EXT:fku_songs/Resources/Private/Language/locallang_csh_tx_fkusongs_domain_model_' . $table . '.xlf');
}

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('fku_songs', 'Configuration/TypoScript', 'FKU Songs');

?>