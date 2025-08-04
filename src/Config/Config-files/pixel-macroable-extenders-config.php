<?php

use PixelMacroableExtenders\MacroableExtenders\PixelBlueprintExtender;
use PixelMacroableExtenders\MacroableExtenders\PixelBuilderExtender;
use PixelMacroableExtenders\MacroableExtenders\PixelCarbonExtender;
use PixelMacroableExtenders\MacroableExtenders\PixelHasManyExtender;
use PixelMacroableExtenders\MacroableExtenders\PixelReponseExtender;
use PixelMacroableExtenders\MacroableExtenders\PixelStrExtender;

return [

    "needed-macroable-extenders" => [
        PixelBlueprintExtender::getClassName()  => PixelBlueprintExtender::class,
        PixelBuilderExtender::getClassName()    => PixelBuilderExtender::class,
        PixelCarbonExtender::getClassName()     => PixelCarbonExtender::class,
        PixelHasManyExtender::getClassName()    => PixelHasManyExtender::class,
        PixelStrExtender::getClassName()        => PixelStrExtender::class,
        PixelReponseExtender::getClassName()    => PixelReponseExtender::class
    ]
];