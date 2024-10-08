<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\TypeTable;
use Bitrix\Iblock\IblockTable;

if (!Loader::includeModule('iblock')) {
	return;
}

$iblockExists = (!empty($arCurrentValues['IBLOCK_ID']) && (int)$arCurrentValues['IBLOCK_ID'] > 0);

$iblockTypes = TypeTable::getList(
	[
		'select' => ['*', 'NAME' => 'LANG_MESSAGE.NAME'],
		'filter' => ['=LANG_MESSAGE.LANGUAGE_ID' => LANGUAGE_ID],
	]
);

while ($row = $iblockTypes->fetch())
{
	$arIBlockTypes [$row['ID']] = "[{$row['ID']}] {$row['NAME']}";
}

$arInfoBlocks = [];

$arFilterInfoBlocks = ['ACTIVE' => 'Y'];

$arOrderInfoBlocks = ['SORT' => 'ASC'];

if (!empty($arCurrentValues['IBLOCK_TYPE'])) {
	$arFilterInfoBlocks['IBLOCK_TYPE_ID'] = $arCurrentValues['IBLOCK_TYPE'];
}

$rsIBlock = IblockTable::getList([
		'select' => ['ID', 'NAME'],
		'filter' => $arFilterInfoBlocks,
		'order' => $arOrderInfoBlocks,
	]);

while ($row = $rsIBlock->fetch()) {
	$arInfoBlocks[$row['ID']] = '[' . $row['ID'] . '] ' . $row['NAME'];
}

// Property codes
$arPropertys = [];
if ($iblockExists) {
	$rsProp = CIBlockProperty::GetList(
		[
			'SORT' => 'ASC',
			'NAME' => 'ASC',
		],
		[
			'ACTIVE' => 'Y',
			'IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
		]
	);
	while ($arr = $rsProp->Fetch()) {
		if (in_array($arr['PROPERTY_TYPE'], ['L', 'N', 'S', 'E'])) {
			$arPropertys[$arr['CODE']] = '[' . $arr['CODE'] . '] ' . $arr['NAME'];
		}
	}
}

$arComponentParameters = [
	'GROUPS' => [
	'LIST_SETTINGS' => [
		'NAME' => Loc::getMessage('T_GRID_LIST_SETTINGS'),
	],
	'DETAIL_SETTINGS' => [
		'NAME' => Loc::getMessage('T_GRID_DETAIL_SETTINGS'),
	],
		],
	'PARAMETERS' => [
		'VARIABLE_ALIASES' => [
			'ID' => ['NAME' => Loc::getMessage('T_GRID_COMPANY_ID_DESC')],
			'CODE' => ['NAME' => Loc::getMessage('T_GRID_COMPANY_CODE_DESC')],
		],
		'SEF_MODE' => [
			'detail' => [
				'NAME' => Loc::getMessage('T_GRID_DETAIL_URL_TEMPLATE'),
				'DEFAULT' => '#ID#/',
				'VARIABLES' => ['ID', 'CODE'],
			],
		],
		'IBLOCK_TYPE' => [
			'PARENT' => 'BASE',
			'NAME' => Loc::getMessage('T_GRID_IBLOCK_TYPE'),
			'TYPE' => 'LIST',
			'VALUES' => $arIBlockTypes,
			'REFRESH' => 'Y',
			'DEFAULT' => '',
			'MULTIPLE' => 'N',
		],
		'IBLOCK_ID' => [
			'PARENT' => 'BASE',
			'NAME' => Loc::getMessage('T_GRID_IBLOCK'),
			'TYPE' => 'LIST',
			'VALUES' => $arInfoBlocks,
			'REFRESH' => 'Y',
			'DEFAULT' => '',
		],
		'LIST_FIELD_CODE' => CIBlockParameters::GetFieldCode(Loc::getMessage('T_GRID_IBLOCK_FIELD'), 'LIST_SETTINGS'),
		'LIST_PROPERTY_CODE' => [
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => Loc::getMessage('T_GRID_IBLOCK_PROPERTY'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'VALUES' => $arPropertys,
			'ADDITIONAL_VALUES' => 'Y',
		],
		'DETAIL_FIELD_CODE' => CIBlockParameters::GetFieldCode(Loc::getMessage('T_GRID_IBLOCK_FIELD'), 'DETAIL_SETTINGS'),
		'DETAIL_PROPERTY_CODE' => [
			'PARENT' => 'DETAIL_SETTINGS',
			'NAME' => Loc::getMessage('T_GRID_IBLOCK_PROPERTY'),
			'TYPE' => 'LIST',
			'MULTIPLE' => 'Y',
			'VALUES' => $arPropertys,
			'ADDITIONAL_VALUES' => 'Y',
		],
		'CACHE_TIME' => ['DEFAULT' => 86400],
	],
];