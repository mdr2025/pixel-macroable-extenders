<?php

namespace PixelMacroableExtenders\Config;

use PixelMacroableExtenders\MacroableExtenders\PixelBlueprintExtender;
use PixelMacroableExtenders\MacroableExtenders\PixelBuilderExtender;
use PixelMacroableExtenders\MacroableExtenders\PixelCarbonExtender;
use PixelMacroableExtenders\MacroableExtenders\PixelHasManyExtender;
use PixelMacroableExtenders\MacroableExtenders\PixelReponseExtender;
use PixelMacroableExtenders\MacroableExtenders\PixelStrExtender;

class PixelMacroableExtendersConfigManager
{    
    public static function getConfigFileFullName()  :string
    {
        return static::getConfigFileName() . ".php";
    }

    public static function getConfigFileName()  :string
    {
        return "pixel-macroable-extenders-config";
    }

    public static function getConfigFilePublishingKeyName() : string
    {
        return static::getConfigFileName();
    }

    public static function getConfigMergingKeyName() : string
    {
        return static::getConfigFileName();
    }
    
    public static function getConfigs() : array
    {
        return config(static::getConfigMergingKeyName() , []);
    }
    
    public static function getConfigFileProjectPath() : string
    {
        $fileFullName = static::getConfigFileFullName();

        return config_path( $fileFullName );
    }

    public static function getConfigFilePackagePath() : string
    {
        $filePath = __DIR__ . "/Config-files/" . static::getConfigFileFullName();
        
        return realpath( $filePath );
    }

    public static function getPixelNeededMacroableExtenders() : array
    {
        $configs = static::getConfigs();
        
        return $configs["needed-macroable-extenders"] ??  []  ;
    }

    public static function isMacroableExtenderLoaded(string $macroableExtenderKeyName) : bool
    {
        return isset( static::getPixelNeededMacroableExtenders()[$macroableExtenderKeyName] );
    }

    public static function isPixelBlueprintExtenderLoaded()  :bool
    {
        $key = PixelBlueprintExtender::getClassName();
        return static::isMacroableExtenderLoaded($key);
    }

    public static function isPixelBuilderExtenderLoaded()  :bool
    {
        $key = PixelBuilderExtender::getClassName();
        return static::isMacroableExtenderLoaded($key);
    }

    
    public static function isPixelCarbonExtenderLoaded()  :bool
    {
        $key = PixelCarbonExtender::getClassName();
        return static::isMacroableExtenderLoaded($key);
    }
    
    public static function isPixelHasManyExtenderLoaded()  :bool
    {
        $key = PixelHasManyExtender::getClassName();
        return static::isMacroableExtenderLoaded($key);
    }

    
    public static function isPixelStrExtenderLoaded()  :bool
    {
        $key = PixelStrExtender::getClassName();
        return static::isMacroableExtenderLoaded($key);
    }

    public static function isPixelReponseExtenderLoaded()  :bool
    {
        $key = PixelReponseExtender::getClassName();
        return static::isMacroableExtenderLoaded($key);
    }
}