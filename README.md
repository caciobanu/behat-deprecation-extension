Behat Deprecation Extension
================================

A [Behat extension](http://behat.org) to display the whole list of user deprecated features (E_USER_DEPRECATED messages). It can be useful in a Symfony2 application when the tests are run with [Symfony2 BrowserKit driver for Mink framework](https://github.com/minkphp/MinkBrowserKitDriver) but not limited to.

[![Package version](http://img.shields.io/packagist/v/caciobanu/behat-deprecation-extension.svg?style=flat-square)](https://packagist.org/packages/caciobanu/behat-deprecation-extension)
[![Build Status](https://img.shields.io/travis/caciobanu/behat-deprecation-extension.svg?branch=master&style=flat-square)](https://travis-ci.org/caciobanu/behat-deprecation-extension?branch=master)

## Installation

You can use [Composer](https://getcomposer.org/) to install the extension to your project:

```bash
composer require --dev caciobanu/behat-deprecation-extension
```

Then, in your behat config file `behat.yml`, register the extension:

```yaml
# behat.yml
default:
    extensions:
        Caciobanu\Behat\DeprecationExtension: ~
```

Or like below with a mode set:

```yaml
# behat.yml
default:
    extensions:
        Caciobanu\Behat\DeprecationExtension:
            mode: weak
```

The following reporting modes are supported:
- use null to display the deprecation report without making the test suite fail (default);
- use "weak" to hide the deprecation report but keep a global count;
- use a number to define the upper bound of allowed deprecations, making the tests fail whenever more notices are triggered.

## Basic usage

Run Behat and enjoy :)

#### The summary includes:
- Unsilenced
    - Reports deprecation notices that were triggered without the recommended @-silencing operator.
- Legacy
    - Deprecation notices denote tests that explicitly test some legacy features, marked with the @legacy tag.
- Remaining
    - Deprecation notices are all other (non-legacy) notices.

## Ignore some deprecation

You can filter the file that did make the call to `trigger_error` like this:

```yaml
default:
    extensions:
        Caciobanu\Behat\DeprecationExtension:
            ignoreDeprecations:
                - '#symfony#'
                - '#my-app#'
```

It will ignore every files that matches the listed regexps

## Credits

This library is developed by [Catalin Ciobanu](https://github.com/caciobanu).

## License

[![license](https://img.shields.io/badge/license-MIT-red.svg?style=flat-square)](LICENSE)
