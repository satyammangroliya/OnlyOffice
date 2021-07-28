<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdeddd02a31326b02407601afe4c5b12b
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'srag\\RemovePluginDataConfirm\\OnlyOffice\\' => 40,
            'srag\\Plugins\\OnlyOffice\\' => 24,
            'srag\\LibraryLanguageInstaller\\OnlyOffice\\' => 41,
            'srag\\LibrariesNamespaceChanger\\' => 31,
            'srag\\DIC\\OnlyOffice\\' => 20,
            'srag\\CustomInputGUIs\\OnlyOffice\\' => 32,
            'srag\\ActiveRecordConfig\\OnlyOffice\\' => 35,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'srag\\RemovePluginDataConfirm\\OnlyOffice\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/removeplugindataconfirm/src',
        ),
        'srag\\Plugins\\OnlyOffice\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
        'srag\\LibraryLanguageInstaller\\OnlyOffice\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/librarylanguageinstaller/src',
        ),
        'srag\\LibrariesNamespaceChanger\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/librariesnamespacechanger/src',
        ),
        'srag\\DIC\\OnlyOffice\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/dic/src',
        ),
        'srag\\CustomInputGUIs\\OnlyOffice\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/custominputguis/src',
        ),
        'srag\\ActiveRecordConfig\\OnlyOffice\\' => 
        array (
            0 => __DIR__ . '/..' . '/srag/activerecordconfig/src',
        ),
    );

    public static $classMap = array (
        'ilObjOnlyOffice' => __DIR__ . '/../..' . '/classes/class.ilObjOnlyOffice.php',
        'ilObjOnlyOfficeAccess' => __DIR__ . '/../..' . '/classes/class.ilObjOnlyOfficeAccess.php',
        'ilObjOnlyOfficeGUI' => __DIR__ . '/../..' . '/classes/class.ilObjOnlyOfficeGUI.php',
        'ilObjOnlyOfficeListGUI' => __DIR__ . '/../..' . '/classes/class.ilObjOnlyOfficeListGUI.php',
        'ilOnlyOfficeConfigGUI' => __DIR__ . '/../..' . '/classes/class.ilOnlyOfficeConfigGUI.php',
        'ilOnlyOfficePlugin' => __DIR__ . '/../..' . '/classes/class.ilOnlyOfficePlugin.php',
        'srag\\ActiveRecordConfig\\OnlyOffice\\Config\\AbstractFactory' => __DIR__ . '/..' . '/srag/activerecordconfig/src/Config/AbstractFactory.php',
        'srag\\ActiveRecordConfig\\OnlyOffice\\Config\\AbstractRepository' => __DIR__ . '/..' . '/srag/activerecordconfig/src/Config/AbstractRepository.php',
        'srag\\ActiveRecordConfig\\OnlyOffice\\Config\\Config' => __DIR__ . '/..' . '/srag/activerecordconfig/src/Config/Config.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\AjaxCheckbox\\AjaxCheckbox' => __DIR__ . '/..' . '/srag/custominputguis/src/AjaxCheckbox/AjaxCheckbox.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\CheckboxInputGUI\\CheckboxInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/CheckboxInputGUI/CheckboxInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\ColorPickerInputGUI\\ColorPickerInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/ColorPickerInputGUI/ColorPickerInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\CustomInputGUIs' => __DIR__ . '/..' . '/srag/custominputguis/src/CustomInputGUIs.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\CustomInputGUIsTrait' => __DIR__ . '/..' . '/srag/custominputguis/src/CustomInputGUIsTrait.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\DateDurationInputGUI\\DateDurationInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/DateDurationInputGUI/DateDurationInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\FormBuilder\\AbstractFormBuilder' => __DIR__ . '/..' . '/srag/custominputguis/src/FormBuilder/AbstractFormBuilder.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\FormBuilder\\FormBuilder' => __DIR__ . '/..' . '/srag/custominputguis/src/FormBuilder/FormBuilder.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\HiddenInputGUI\\HiddenInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/HiddenInputGUI/HiddenInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\InputGUIWrapperUIInputComponent\\AbstractRenderer' => __DIR__ . '/..' . '/srag/custominputguis/src/InputGUIWrapperUIInputComponent/AbstractRenderer.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\InputGUIWrapperUIInputComponent\\InputGUIWrapperConstraint' => __DIR__ . '/..' . '/srag/custominputguis/src/InputGUIWrapperUIInputComponent/InputGUIWrapperConstraint.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\InputGUIWrapperUIInputComponent\\InputGUIWrapperConstraintTrait' => __DIR__ . '/..' . '/srag/custominputguis/src/InputGUIWrapperUIInputComponent/InputGUIWrapperConstraintTrait.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\InputGUIWrapperUIInputComponent\\InputGUIWrapperUIInputComponent' => __DIR__ . '/..' . '/srag/custominputguis/src/InputGUIWrapperUIInputComponent/InputGUIWrapperUIInputComponent.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\InputGUIWrapperUIInputComponent\\Renderer' => __DIR__ . '/..' . '/srag/custominputguis/src/InputGUIWrapperUIInputComponent/Renderer.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\Loader\\CustomInputGUIsLoaderDetector' => __DIR__ . '/..' . '/srag/custominputguis/src/Loader/CustomInputGUIsLoaderDetector.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\MultiLineNewInputGUI\\MultiLineNewInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/MultiLineNewInputGUI/MultiLineNewInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\MultiSelectSearchNewInputGUI\\AbstractAjaxAutoCompleteCtrl' => __DIR__ . '/..' . '/srag/custominputguis/src/MultiSelectSearchNewInputGUI/AbstractAjaxAutoCompleteCtrl.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\MultiSelectSearchNewInputGUI\\MultiSelectSearchNewInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/MultiSelectSearchNewInputGUI/MultiSelectSearchNewInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\MultiSelectSearchNewInputGUI\\ObjectChildrenAjaxAutoCompleteCtrl' => __DIR__ . '/..' . '/srag/custominputguis/src/MultiSelectSearchNewInputGUI/class.ObjectChildrenAjaxAutoCompleteCtrl.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\MultiSelectSearchNewInputGUI\\ObjectsAjaxAutoCompleteCtrl' => __DIR__ . '/..' . '/srag/custominputguis/src/MultiSelectSearchNewInputGUI/class.ObjectsAjaxAutoCompleteCtrl.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\MultiSelectSearchNewInputGUI\\OrgUnitAjaxAutoCompleteCtrl' => __DIR__ . '/..' . '/srag/custominputguis/src/MultiSelectSearchNewInputGUI/class.OrgUnitAjaxAutoCompleteCtrl.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\MultiSelectSearchNewInputGUI\\UsersAjaxAutoCompleteCtrl' => __DIR__ . '/..' . '/srag/custominputguis/src/MultiSelectSearchNewInputGUI/class.UsersAjaxAutoCompleteCtrl.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\NumberInputGUI\\NumberInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/NumberInputGUI/NumberInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\PropertyFormGUI\\Exception\\PropertyFormGUIException' => __DIR__ . '/..' . '/srag/custominputguis/src/PropertyFormGUI/Exception/PropertyFormGUIException.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\PropertyFormGUI\\Items\\Items' => __DIR__ . '/..' . '/srag/custominputguis/src/PropertyFormGUI/Items/Items.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\PropertyFormGUI\\PropertyFormGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/PropertyFormGUI/PropertyFormGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\ScreenshotsInputGUI\\ScreenshotsInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/ScreenshotsInputGUI/ScreenshotsInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\StaticHTMLPresentationInputGUI\\StaticHTMLPresentationInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/StaticHTMLPresentationInputGUI/StaticHTMLPresentationInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\TableGUI\\Exception\\TableGUIException' => __DIR__ . '/..' . '/srag/custominputguis/src/TableGUI/Exception/TableGUIException.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\TableGUI\\TableGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/TableGUI/TableGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\TabsInputGUI\\MultilangualTabsInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/TabsInputGUI/MultilangualTabsInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\TabsInputGUI\\TabsInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/TabsInputGUI/TabsInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\TabsInputGUI\\TabsInputGUITab' => __DIR__ . '/..' . '/srag/custominputguis/src/TabsInputGUI/TabsInputGUITab.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\Template\\Template' => __DIR__ . '/..' . '/srag/custominputguis/src/Template/Template.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\TextAreaInputGUI\\TextAreaInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/TextAreaInputGUI/TextAreaInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\TextInputGUI\\TextInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/TextInputGUI/TextInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\TextInputGUI\\TextInputGUIWithModernAutoComplete' => __DIR__ . '/..' . '/srag/custominputguis/src/TextInputGUI/TextInputGUIWithModernAutoComplete.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\UIInputComponentWrapperInputGUI\\UIInputComponentWrapperInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/UIInputComponentWrapperInputGUI/UIInputComponentWrapperInputGUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\UIInputComponentWrapperInputGUI\\UIInputComponentWrapperNameSource' => __DIR__ . '/..' . '/srag/custominputguis/src/UIInputComponentWrapperInputGUI/UIInputComponentWrapperNameSource.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\ViewControlModeUI\\ViewControlModeUI' => __DIR__ . '/..' . '/srag/custominputguis/src/ViewControlModeUI/ViewControlModeUI.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\Waiter\\Waiter' => __DIR__ . '/..' . '/srag/custominputguis/src/Waiter/Waiter.php',
        'srag\\CustomInputGUIs\\OnlyOffice\\WeekdayInputGUI\\WeekdayInputGUI' => __DIR__ . '/..' . '/srag/custominputguis/src/WeekdayInputGUI/WeekdayInputGUI.php',
        'srag\\DIC\\OnlyOffice\\Cron\\FixUITemplateInCronContext' => __DIR__ . '/..' . '/srag/dic/src/Cron/FixUITemplateInCronContext.php',
        'srag\\DIC\\OnlyOffice\\DICStatic' => __DIR__ . '/..' . '/srag/dic/src/DICStatic.php',
        'srag\\DIC\\OnlyOffice\\DICStaticInterface' => __DIR__ . '/..' . '/srag/dic/src/DICStaticInterface.php',
        'srag\\DIC\\OnlyOffice\\DICTrait' => __DIR__ . '/..' . '/srag/dic/src/DICTrait.php',
        'srag\\DIC\\OnlyOffice\\DIC\\AbstractDIC' => __DIR__ . '/..' . '/srag/dic/src/DIC/AbstractDIC.php',
        'srag\\DIC\\OnlyOffice\\DIC\\DICInterface' => __DIR__ . '/..' . '/srag/dic/src/DIC/DICInterface.php',
        'srag\\DIC\\OnlyOffice\\DIC\\Implementation\\ILIAS60DIC' => __DIR__ . '/..' . '/srag/dic/src/DIC/Implementation/ILIAS60DIC.php',
        'srag\\DIC\\OnlyOffice\\DIC\\Implementation\\ILIAS70DIC' => __DIR__ . '/..' . '/srag/dic/src/DIC/Implementation/ILIAS70DIC.php',
        'srag\\DIC\\OnlyOffice\\Database\\AbstractILIASDatabaseDetector' => __DIR__ . '/..' . '/srag/dic/src/Database/AbstractILIASDatabaseDetector.php',
        'srag\\DIC\\OnlyOffice\\Database\\DatabaseDetector' => __DIR__ . '/..' . '/srag/dic/src/Database/DatabaseDetector.php',
        'srag\\DIC\\OnlyOffice\\Database\\DatabaseInterface' => __DIR__ . '/..' . '/srag/dic/src/Database/DatabaseInterface.php',
        'srag\\DIC\\OnlyOffice\\Database\\PdoContextHelper' => __DIR__ . '/..' . '/srag/dic/src/Database/PdoContextHelper.php',
        'srag\\DIC\\OnlyOffice\\Database\\PdoStatementContextHelper' => __DIR__ . '/..' . '/srag/dic/src/Database/PdoStatementContextHelper.php',
        'srag\\DIC\\OnlyOffice\\Exception\\DICException' => __DIR__ . '/..' . '/srag/dic/src/Exception/DICException.php',
        'srag\\DIC\\OnlyOffice\\Loader\\AbstractLoaderDetector' => __DIR__ . '/..' . '/srag/dic/src/Loader/AbstractLoaderDetector.php',
        'srag\\DIC\\OnlyOffice\\Output\\Output' => __DIR__ . '/..' . '/srag/dic/src/Output/Output.php',
        'srag\\DIC\\OnlyOffice\\Output\\OutputInterface' => __DIR__ . '/..' . '/srag/dic/src/Output/OutputInterface.php',
        'srag\\DIC\\OnlyOffice\\PHPVersionChecker' => __DIR__ . '/..' . '/srag/dic/src/PHPVersionChecker.php',
        'srag\\DIC\\OnlyOffice\\Plugin\\Plugin' => __DIR__ . '/..' . '/srag/dic/src/Plugin/Plugin.php',
        'srag\\DIC\\OnlyOffice\\Plugin\\PluginInterface' => __DIR__ . '/..' . '/srag/dic/src/Plugin/PluginInterface.php',
        'srag\\DIC\\OnlyOffice\\Plugin\\Pluginable' => __DIR__ . '/..' . '/srag/dic/src/Plugin/Pluginable.php',
        'srag\\DIC\\OnlyOffice\\Version\\PluginVersionParameter' => __DIR__ . '/..' . '/srag/dic/src/Version/PluginVersionParameter.php',
        'srag\\DIC\\OnlyOffice\\Version\\Version' => __DIR__ . '/..' . '/srag/dic/src/Version/Version.php',
        'srag\\DIC\\OnlyOffice\\Version\\VersionInterface' => __DIR__ . '/..' . '/srag/dic/src/Version/VersionInterface.php',
        'srag\\LibrariesNamespaceChanger\\LibrariesNamespaceChanger' => __DIR__ . '/..' . '/srag/librariesnamespacechanger/src/LibrariesNamespaceChanger.php',
        'srag\\LibraryLanguageInstaller\\OnlyOffice\\LibraryLanguageInstaller' => __DIR__ . '/..' . '/srag/librarylanguageinstaller/src/LibraryLanguageInstaller.php',
        'srag\\Plugins\\OnlyOffice\\Config\\ConfigFormGUI' => __DIR__ . '/../..' . '/src/Config/ConfigFormGUI.php',
        'srag\\Plugins\\OnlyOffice\\Config\\Factory' => __DIR__ . '/../..' . '/src/Config/Factory.php',
        'srag\\Plugins\\OnlyOffice\\Config\\Repository' => __DIR__ . '/../..' . '/src/Config/Repository.php',
        'srag\\Plugins\\OnlyOffice\\CryptoService\\JwtService' => __DIR__ . '/../..' . '/src/CryptoService/JwtService.php',
        'srag\\Plugins\\OnlyOffice\\CryptoService\\WebAccessService' => __DIR__ . '/../..' . '/src/CryptoService/WebAccessService.php',
        'srag\\Plugins\\OnlyOffice\\ObjectSettings\\Factory' => __DIR__ . '/../..' . '/src/ObjectSettings/Factory.php',
        'srag\\Plugins\\OnlyOffice\\ObjectSettings\\ObjectSettings' => __DIR__ . '/../..' . '/src/ObjectSettings/ObjectSettings.php',
        'srag\\Plugins\\OnlyOffice\\ObjectSettings\\ObjectSettingsFormGUI' => __DIR__ . '/../..' . '/src/ObjectSettings/ObjectSettingsFormGUI.php',
        'srag\\Plugins\\OnlyOffice\\ObjectSettings\\Repository' => __DIR__ . '/../..' . '/src/ObjectSettings/Repository.php',
        'srag\\Plugins\\OnlyOffice\\Repository' => __DIR__ . '/../..' . '/src/Repository.php',
        'srag\\Plugins\\OnlyOffice\\StorageService\\DTO\\File' => __DIR__ . '/../..' . '/src/StorageService/DTO/File.php',
        'srag\\Plugins\\OnlyOffice\\StorageService\\DTO\\FileVersion' => __DIR__ . '/../..' . '/src/StorageService/DTO/FileVersion.php',
        'srag\\Plugins\\OnlyOffice\\StorageService\\FileSystem\\FileSystemService' => __DIR__ . '/../..' . '/src/StorageService/FileSystem/FileSystemService.php',
        'srag\\Plugins\\OnlyOffice\\StorageService\\Infrastructure\\Common\\UUID' => __DIR__ . '/../..' . '/src/StorageService/Infrastructure/Common/UUID.php',
        'srag\\Plugins\\OnlyOffice\\StorageService\\Infrastructure\\File\\FileAR' => __DIR__ . '/../..' . '/src/StorageService/Infrastructure/File/FileAR.php',
        'srag\\Plugins\\OnlyOffice\\StorageService\\Infrastructure\\File\\FileRepository' => __DIR__ . '/../..' . '/src/StorageService/Infrastructure/File/FileRepository.php',
        'srag\\Plugins\\OnlyOffice\\StorageService\\Infrastructure\\File\\FileVersionAR' => __DIR__ . '/../..' . '/src/StorageService/Infrastructure/File/FileVersionAR.php',
        'srag\\Plugins\\OnlyOffice\\StorageService\\Infrastructure\\File\\FileVersionRepository' => __DIR__ . '/../..' . '/src/StorageService/Infrastructure/File/FileVersionRepository.php',
        'srag\\Plugins\\OnlyOffice\\StorageService\\Infrastructure\\File\\ilDBFileRepository' => __DIR__ . '/../..' . '/src/StorageService/Infrastructure/File/ilDBFileRepository.php',
        'srag\\Plugins\\OnlyOffice\\StorageService\\Infrastructure\\File\\ilDBFileVersionRepository' => __DIR__ . '/../..' . '/src/StorageService/Infrastructure/File/ilDBFileVersionRepository.php',
        'srag\\Plugins\\OnlyOffice\\StorageService\\StorageService' => __DIR__ . '/../..' . '/src/StorageService/StorageService.php',
        'srag\\Plugins\\OnlyOffice\\Utils\\OnlyOfficeTrait' => __DIR__ . '/../..' . '/src/Utils/OnlyOfficeTrait.php',
        'srag\\RemovePluginDataConfirm\\OnlyOffice\\BasePluginUninstallTrait' => __DIR__ . '/..' . '/srag/removeplugindataconfirm/src/BasePluginUninstallTrait.php',
        'srag\\RemovePluginDataConfirm\\OnlyOffice\\PluginUninstallTrait' => __DIR__ . '/..' . '/srag/removeplugindataconfirm/src/PluginUninstallTrait.php',
        'srag\\RemovePluginDataConfirm\\OnlyOffice\\RemovePluginDataConfirmCtrl' => __DIR__ . '/..' . '/srag/removeplugindataconfirm/src/class.RemovePluginDataConfirmCtrl.php',
        'srag\\RemovePluginDataConfirm\\OnlyOffice\\RepositoryObjectPluginUninstallTrait' => __DIR__ . '/..' . '/srag/removeplugindataconfirm/src/RepositoryObjectPluginUninstallTrait.php',
        'xonoAbstractGUI' => __DIR__ . '/../..' . '/classes/xonoAbstractGUI.php',
        'xonoContentGUI' => __DIR__ . '/../..' . '/classes/Content/class.xonoContentGUI.php',
        'xonoEditorGUI' => __DIR__ . '/../..' . '/classes/Editor/class.xonoEditorGUI.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdeddd02a31326b02407601afe4c5b12b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdeddd02a31326b02407601afe4c5b12b::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdeddd02a31326b02407601afe4c5b12b::$classMap;

        }, null, ClassLoader::class);
    }
}
