<?php

use Behat\Config\Config;
use Behat\Config\Extension;
use Behat\Config\Profile;
use Behat\Config\TesterOptions;
use Caciobanu\Behat\DeprecationExtension\ServiceContainer\DeprecationExtension;

return (new Config())
    ->withProfile((new Profile('default'))
        ->withTesterOptions((new TesterOptions())
            ->withErrorReporting(E_ALL))
        ->withExtension(new Extension(DeprecationExtension::class)))
    ->withProfile((new Profile('weak'))
        ->withTesterOptions((new TesterOptions())
            ->withErrorReporting(E_ALL))
        ->withExtension(new Extension(DeprecationExtension::class, [
            'mode' => 'weak',
        ])))
    ->withProfile((new Profile('integer'))
        ->withTesterOptions((new TesterOptions())
            ->withErrorReporting(E_ALL))
        ->withExtension(new Extension(DeprecationExtension::class, [
            'mode' => 1,
        ])))
    ->withProfile((new Profile('ignore_file'))
        ->withTesterOptions((new TesterOptions())
            ->withErrorReporting(E_ALL))
        ->withExtension(new Extension(DeprecationExtension::class, [
            'ignore' => [
                [
                    'file' => '#DeprecatedClass#',
                ],
            ],
        ])))
    ->withProfile((new Profile('ignore_message'))
        ->withTesterOptions((new TesterOptions())
            ->withErrorReporting(E_ALL))
        ->withExtension(new Extension(DeprecationExtension::class, [
            'ignore' => [
                [
                    'message' => '#deprecatedMethodUnsilenced#',
                ],
            ],
        ])))
    ->withProfile((new Profile('ignore_file_and_message'))
        ->withTesterOptions((new TesterOptions())
            ->withErrorReporting(E_ALL))
        ->withExtension(new Extension(DeprecationExtension::class, [
            'ignore' => [
                [
                    'file' => '#DeprecatedClass#',
                    'message' => '#deprecatedMethodUnsilenced#',
                ],
            ],
        ])))
    ->withProfile((new Profile('default_no_error_reporting'))
        ->withTesterOptions((new TesterOptions())
            ->withErrorReporting(0))
        ->withExtension(new Extension(DeprecationExtension::class)))
    ->withProfile((new Profile('weak_no_error_reporting'))
        ->withTesterOptions((new TesterOptions())
            ->withErrorReporting(0))
        ->withExtension(new Extension(DeprecationExtension::class, [
            'mode' => 'weak',
        ])))
    ->withProfile((new Profile('integer_no_error_reporting'))
        ->withTesterOptions((new TesterOptions())
            ->withErrorReporting(0))
        ->withExtension(new Extension(DeprecationExtension::class, [
            'mode' => 1,
        ])));
