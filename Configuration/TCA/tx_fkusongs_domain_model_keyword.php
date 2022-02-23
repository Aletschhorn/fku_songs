<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_keyword',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => FALSE,
		'default_sortby' => 'ORDER BY title',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
		),
		'searchFields' => 'title',
		'iconfile' => 'EXT:fku_songs/Resources/Public/Icons/tx_fkusongs_domain_model_keyword.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden, title, songs',
	),
	'types' => array(
		'1' => array('showitem' => 'hidden, --palette--;;1, title, songs'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
	),
	'columns' => array(
		'hidden' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.hidden',
			'config' => array(
				'type' => 'check',
			),
		),
		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_keyword.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		'songs' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_keyword.songs',
			'config' => array(
				'type' => 'none',
			),
		),

	),
);