<?php

namespace Drupal\search_api_solr\Utility;

use Drupal\search_api\ServerInterface;

defined('SOLR_INDEX_WAIT') || define('SOLR_INDEX_WAIT', getenv('SOLR_INDEX_WAIT') ?: 0);

/**
 * Helper to ensure that solr index is up to date.
 */
trait SolrCommitTrait {

  /**
   * Explicitly sends a commit command to a Solr server.
   *
   * @param \Drupal\search_api\ServerInterface $server
   *   The Search API server entity.
   *
   * @throws \Drupal\search_api\SearchApiException
   */
  protected function ensureCommit(ServerInterface $server) {
    $backend = $server->getBackend();
    /** @var \Drupal\search_api_solr\SolrConnectorInterface $connector */
    $connector = $backend->getSolrConnector();
    $update = $connector->getUpdateQuery();
    $update->addCommit(TRUE, TRUE, TRUE);
    $connector->update($update);
    if (SOLR_INDEX_WAIT) {
      sleep(SOLR_INDEX_WAIT);
    }
  }

}
