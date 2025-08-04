<?php

namespace PixelMacroableExtenders\ServiceProviders;

use Illuminate\Support\Facades\File;
use Illuminate\Support\ServiceProvider;
use PixelMacroableExtenders\Config\PixelMacroableExtendersConfigManager;
use PixelMacroableExtenders\MacroableExtenders\PixelMacroableExtender;

class PixelMacroableExtendersServiceProvider extends ServiceProvider
{ 
 
    public function register()
    {
        //merging config files
        $this->mergeConfigFiles();
    }

    public function boot()
    {
        $this->prepareConfigFilesPublishing();

        $this->extendMacroables();
         
    }

    protected function mergeConfigFiles() : void
    {
        $projectPath = PixelMacroableExtendersConfigManager::getConfigFileProjectPath();
        $packagePath = PixelMacroableExtendersConfigManager::getConfigFilePackagePath();
        $mergingKeyName = PixelMacroableExtendersConfigManager::getConfigMergingKeyName();

        if(!File::exists($projectPath))
        {
            $this->mergeConfigFrom( $packagePath , $mergingKeyName );
        }
    }

    protected function prepareConfigFilesPublishing() : void
    {
        $publisingKey = PixelMacroableExtendersConfigManager::getConfigFilePublishingKeyName();
        $pacakgePath = PixelMacroableExtendersConfigManager::getConfigFilePackagePath();
        $projectPath = PixelMacroableExtendersConfigManager::getConfigFileProjectPath();
        
        $this->publishes(
            [
                $pacakgePath => $projectPath
            ],
            $publisingKey
         );
    }
          
    protected function extendMacroables() : void
    { 
        foreach(PixelMacroableExtendersConfigManager::getPixelNeededMacroableExtenders() as $extenderClass)
        {
            if(is_subclass_of($extenderClass , PixelMacroableExtender::class))
            {
                $extender = new $extenderClass();
                $extender->extendMacroable();
            }
        }
    }
}
