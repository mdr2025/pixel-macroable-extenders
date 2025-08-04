<?php

namespace PixelMacroableExtenders\MacroableExtenders;

use Exception;

abstract class PixelMacroableExtender
{ 
    abstract public function extendMacroable() : void;

    public static function getClassName() : string
    {
        return class_basename(static::class);
    }

    public static function throwNotLoadedExtenderException() : void
    {
        $message = static::class . " is not Loaded ... Please add it to pixel-macroable-extenders-config config file !";
        throw new Exception( $message );
    }
}