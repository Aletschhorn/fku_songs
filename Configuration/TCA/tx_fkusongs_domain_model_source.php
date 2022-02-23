<?php
return array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_source',
		'label' => 'title',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,
		'versioningWS' => TRUE,
		'sortby' => 'sorting',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'title,',
		'iconfile' => 'EXT:fku_songs/Resources/Public/Icons/tx_fkusongs_domain_model_source.gif'
	),
	'interface' => array(
		'showRecordFieldList' => 'hidden, title, acronym, withrecommended',
	),
	'types' => array(
		'1' => array('showitem' => 'hidden, --palette--;;1, title, acronym, withrecommended, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'),
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
		'starttime' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
			'config' => array(
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'size' => 13,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),
		'endtime' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
			'config' => array(
				'type' => 'input',
				'renderType' => 'inputDateTime',
				'size' => 13,
				'eval' => 'datetime',
				'checkbox' => 0,
				'default' => 0,
				'range' => array(
					'lower' => mktime(0, 0, 0, date('m'), date('d'), date('Y'))
				),
			),
		),

		'title' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_source.title',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		
		'acronym' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_source.acronym',
			'config' => array(
				'type' => 'input',
				'size' => 30,
				'eval' => 'trim'
			),
		),
		
		'withrecommended' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:fku_songs/Resources/Private/Language/locallang_db.xlf:tx_fkusongs_domain_model_source.withrecommended',
			'config' => array(
				'type' => 'check',
			),
		),
		
	),
);