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
    ])
    ->setFinder($finder)
;
