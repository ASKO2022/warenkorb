<?php

namespace Cart\Config;

use Slim\Views\Twig;
use Twig\Extension\DebugExtension;

class TwigConfig
{
    public static function create()
    {
        $twig = Twig::create(__DIR__ . '/../../templates', [
            'cache' => false, // Cache deaktivieren
            'debug' => true,  // Debug-Modus aktivieren
        ]);

        $twig->addExtension(new DebugExtension());

        return $twig;
    }
}
