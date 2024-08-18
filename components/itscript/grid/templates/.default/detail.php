<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

$APPLICATION->IncludeComponent(
	'itscript:grid.detail',
	'',
	[
		'IBLOCK_TYPE' => $arParams['IBLOCK_TYPE'],
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'CACHE_TYPE' => $arParams['CACHE_TYPE'],
		'CACHE_TIME' => $arParams['CACHE_TIME'],
		'URL_TEMPLATES' => $arResult['URL_TEMPLATES'],
		'SEF_FOLDER' => $arResult['SEF_FOLDER'],
		'DETAIL_FIELD_CODE' => $arParams['DETAIL_FIELD_CODE'],
		'DETAIL_PROPERTY_CODE' => $arParams['DETAIL_PROPERTY_CODE'],
	],
	$component,
	[
		'HIDE_ICONS' => 'N',
	]
);

