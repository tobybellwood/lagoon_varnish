<?php

namespace Drupal\lagoon_varnish\Plugin\Purge\Purger;

use Drupal\purge\Plugin\Purge\Purger\PurgerInterface;
use Drupal\purge\Plugin\Purge\Invalidation\InvalidationInterface;
use Drupal\varnish_purger\Plugin\Purge\Purger\VarnishPurgerBase;

/**
 * HTTP Purger.
 *
 * @PurgePurger(
 *   id = "lagoon_varnish_purger",
 *   label = @Translation("Lagoon Varnish Purger"),
 *   cooldown_time = 0.0,
 *   description = @Translation("Varnish purger that makes HTTP requests for each given invalidation instruction."),
 *   multi_instance = FALSE,
 *   types = {},
 * )
 */
class LagoonVarnishPurger extends VarnishPurgerBase implements PurgerInterface {

  // Makes const available for use elsewhere.
  const LAGOON_VARNISH_PURGER_ID = 'lagoon_varnish_purger';
  const LAGOON_VARNISH_HOSTNAME = 'varnish';
  const LAGOON_VARNISH_PORT = '8080';
  const LAGOON_VARNISH_SCHEME = 'http';
  const LAGOON_VARNISH_PATH = '/';
  const LAGOON_VARNISH_REQUEST_METHOD = 'BAN';
  const LAGOON_VARNISH_REQUEST_HEADERS = [
    'Cache-Tags' => '[invalidations:separated_pipe]',
  ];

  /**
   * {@inheritdoc}
   */
  public function invalidate(array $invalidations) {

    // Iterate every single object and fire a request per object.
    foreach ($invalidations as $invalidation) {
      $token_data = ['invalidation' => $invalidation];
      $uri = $this->getUri($token_data);
      $opt = $this->getOptions($token_data);
      try {
        $this->client->request($this->settings->request_method, $uri, $opt);
        $invalidation->setState(InvalidationInterface::SUCCEEDED);
      }
      catch (\Exception $e) {
        $invalidation->setState(InvalidationInterface::FAILED);

        // Log as much useful information as we can.
        $headers = $opt['headers'];
        unset($opt['headers']);
        $debug = json_encode(str_replace("\n", ' ', [
          'msg' => $e->getMessage(),
          'uri' => $uri,
          'method' => $this->settings->request_method,
          'guzzle_opt' => $opt,
          'headers' => $headers,
        ]));
        $this->logger()->emergency("item failed due @e, details (JSON): @debug",
          ['@e' => get_class($e), '@debug' => $debug]);
      }
    }
  }

  /**
   * Retrieve the URI to connect to.
   *
   * @param $token_data
   *   An array of keyed objects, to pass on to the token service.
   *
   * @return string
   *   URL string representation.
   */
  protected function getUri($token_data) {
    return sprintf(
      '%s://%s:%s%s',
      self::LAGOON_VARNISH_SCHEME,
      self::LAGOON_VARNISH_HOSTNAME,
      self::LAGOON_VARNISH_PORT,
      $this->token->replace(self::LAGOON_VARNISH_PATH, $token_data)
    );
  }

  /**
   * Retrieve all configured headers that need to be set.
   *
   * @param $token_data
   *   An array of keyed objects, to pass on to the token service.
   *
   * @return string[]
   *   Associative array with header values and field names in the key.
   */
  protected function getHeaders($token_data) {
    $headers = [];
    $headers['user-agent'] = 'varnish_purger module for Drupal 8.';
    foreach (self::LAGOON_VARNISH_REQUEST_HEADERS as $field => $value) {
      $headers[strtolower($field)] = $this->token->replace(
        $value,
        $token_data
      );
    }
    return $headers;
  }

}
