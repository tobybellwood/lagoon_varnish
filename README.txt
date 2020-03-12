CONTENTS OF THIS FILE
---------------------

 * Introduction
 * Requirements
 * Installation
 * Configuration
 * Maintainers

INTRODUCTION
------------

This module provides a zero configuration Varnish Cache setup for Drupal sites running on amazeeio's Lagoon hosting system.


REQUIREMENTS
------------

This module lists all its requirements as direct dependencies.


INSTALLATION
------------

run "composer require lagoon_varnish && drush pm-enable -y lagoon_varnish"


CONFIGURATION
-------------

There should be no configuration required beyond enabling the module. As long as it is running on a Lagoon environment it will deal with clearning Varnish cache.

MAINTAINERS
-----------

Blaize Kaye (drupal.org/bomoko)