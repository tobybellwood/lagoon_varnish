<?php

namespace Drupal\lagoon_varnish;


use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Config\ConfigFactoryOverrideInterface;
use Drupal\Core\Config\StorageInterface;


class LagoonVarnishConfigurationService implements ConfigFactoryOverrideInterface {
  /**
   * {@inheritdoc}
   */
  public function loadOverrides($names) {
    $overrides = array();
    return $overrides;
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheSuffix() {
    return 'LagoonVarnishConfigurationOverrider';
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheableMetadata($name) {
    return new CacheableMetadata();
  }

  /**
   * {@inheritdoc}
   */
  public function createConfigObject($name, $collection = StorageInterface::DEFAULT_COLLECTION) {
    return NULL;
  }


}