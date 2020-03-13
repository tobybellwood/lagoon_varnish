CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Maintainers

INTRODUCTION
------------

This module provides a zero configuration Varnish Cache setup for Drupal
sites running on amazeeio's Lagoon hosting system.

Installing this module sets up a purger with the same details as described in
https://lagoon.readthedocs.io/en/latest/using_lagoon/drupal/services/varnish/


REQUIREMENTS
------------

This module lists all its requirements as direct dependencies.


INSTALLATION
------------

Run "composer require lagoon_varnish && drush pm-enable -y lagoon_varnish"

This will set up a Lagoon Varnish purger, which you can confirm by visiting
`admin/config/development/performance/purge`

This can be tested locally by following the steps described here
https://lagoon.readthedocs.io/en/latest/using_lagoon/drupal/services/varnish/#test-varnish-locally

CONFIGURATION
-------------

There should be no configuration required beyond enabling the module.
As long as it is running on a Lagoon environment it will deal with
clearing Varnish cache.


MAINTAINERS
-----------

Blaize Kaye (drupal.org/bomoko)
