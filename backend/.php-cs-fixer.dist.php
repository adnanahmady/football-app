<?php

$finder = (new PhpCsFixer\Finder())
    ->exclude('var')
    ->exclude('vendor')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        '@PSR1' => true,
        '@PSR2' => true,
        '@PSR12' => true,
        'concat_space' => ['spacing' => 'one'],
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'php_unit_test_annotation' => ['style' => 'annotation'],
        'void_return' => true,
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
