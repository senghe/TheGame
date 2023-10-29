<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\Import\NoUnusedImportsFixer;
use \PhpCsFixer\Fixer\Phpdoc\PhpdocInlineTagNormalizerFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return function (ECSConfig $ecsConfig): void {
    $ecsConfig->paths([
        __DIR__ . '/config',
        __DIR__ . '/public',
        __DIR__ . '/spec',
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

    // this way you add a single rule
    $ecsConfig->rules([
        NoUnusedImportsFixer::class,
        PhpdocInlineTagNormalizerFixer::class,
    ]);

    // this way you can add sets - group of rules
    $ecsConfig->sets([
         SetList::SPACES,
         SetList::ARRAY,
         SetList::NAMESPACES,
         SetList::COMMENTS,
         SetList::PSR_12,
    ]);
};
