<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Php74\Rector\Property\RestoreDefaultNullToNullableTypePropertyRector;
use Rector\Php80\Rector\Class_\ClassPropertyAssignToConstructorPromotionRector;

return RectorConfig::configure()
    ->withPaths([
         __DIR__ . '/../../src',
    ])
    ->withPhpSets()
    ->withAttributesSets(all: true)
    ->withComposerBased(twig: true, doctrine: true, phpunit: true, symfony: true)
    ->withSkip([
        ClassPropertyAssignToConstructorPromotionRector::class,
        RestoreDefaultNullToNullableTypePropertyRector::class,
    ])
    ->withPHPStanConfigs([__DIR__.'/phpstan.neon'])
    ;
