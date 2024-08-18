<?php if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

class CmpGridComplex extends CBitrixComponent
{
    public array $arVariables = [];
    protected array $arUrlTemplates  = [];
    protected array $arDefaultVariableAliases  = [];
    protected array $arComponentVariables = ['CODE', 'ID'];
    protected array $arDefaultUrlTemplates404 = [
        "detail" => "#ID#/",
    ];

    public function onPrepareComponentParams($arParams) 
    {
		$result = [
			"CACHE_TYPE" => $arParams["CACHE_TYPE"],
			"CACHE_TIME" => isset($arParams["CACHE_TIME"])? $arParams["CACHE_TIME"]: 36000000,
        ];

        $result = $result+$arParams;

		return $result;
	}

    public function getTemplateNameDefault()
	{
		if ($name = $this->getTemplateName()) {
			return $name;
        } 

        return '.default';
	}

	public function executeComponent() 
    {
        try {

            if ($this->arParams['SEF_MODE'] == 'Y') {
                if (!is_array($this->arParams['SEF_URL_TEMPLATES'])) {
                    $this->arParams['SEF_URL_TEMPLATES'] = [];
                }
        
                debug($this->arParams['SEF_MODE']);

            
                $this->arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates(
                    $this->arResultarDefaultUrlTemplates404, 
                    $arParams['SEF_URL_TEMPLATES']
                );

                /**
                 * $this->arVariables передается по ссылке
                 * и будет содержать массив с ключем = имени переменной из шаблона пути
                */
                $view = CComponentEngine::parseComponentPath(
                    $this->arParams['SEF_FOLDER'],
                    $this->arUrlTemplates,
                    $this->arVariables,
                );
        
                $view = (!empty($view))? $view: 'list';

                $this->arResult = [
                    'SEF_FOLDER' => $this->arParams['SEF_FOLDER'],
                    'URL_TEMPLATES' => [
                        'DETAIL' => $this->arParams['SEF_FOLDER'] . $sefTemplates['detail']
                    ]
                ];

                debug($arVariables);

                debug($view);

            } else {
                $arVariableAliases = CComponentEngine::MakeComponentVariableAliases(
                    $arDefaultVariableAliases,
                    $this->arParams['VARIABLE_ALIASES']
                );
                
                CComponentEngine::InitComponentVariables(
                    false,
                    $arComponentVariables,
                    $arVariableAliases,
                    $arVariables
                );

                $view = (intval($arVariables['COMPANY_ID']) > 0)? 'detail' : 'list';

            }

            // Include template
            $this->includeComponentTemplate($view);
            
        } catch (\Throwable $e) {
            print $e->getMessage();
        }
	}
}