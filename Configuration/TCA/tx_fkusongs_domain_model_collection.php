<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_collection',
		'label' => 'number',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => TRUE,
		'hideTable' => TRUE,

		'delete' => 'deleted',
		'searchFields' => 'number,source,slide',
		'iconfile' => 'EXT:fku_songs/Resources/Public/Icons/tx_fkusongs_domain_model_collection.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'source, number, slide, rejected',
	),
	'types' => array(
		'1' => array('showitem' => '--palette--;LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:palette.general;general'),
	),
	'palettes' => array(
		'1' => array('showitem' => ''),
		'general' => array('showitem' => 'source, number, slide, rejected'),
	),
	'columns' => array(
		'source' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_collection.source',
			'config' => array(
				'type' => 'select',
				'renderType' => 'selectSingle',
				'foreign_table' => 'tx_fkusongs_domain_model_source',
				'minitems' => 0,
				'maxitems' => 1,
			),
		),
		'number' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_collection.number',
			'config' => array(
				'type' => 'input',
				'size' => 5,
				'eval' => 'int'
			),
		),
		'rejected' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_collection.rejected',
			'config' => array(
				'type' => 'check',
			),
		),
		'slide' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_collection.slide',
			'config' => array(
				'type' => 'input',
				'size' => 5,
				'eval' => 'int'
			)
		),
		
	),
);