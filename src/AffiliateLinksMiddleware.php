<?php

namespace Aelora\AffiliateLinks;

use Closure;

class AffiliateLinksMiddleware {

  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle( $request, Closure $next ) {
    $response = $next( $request );

    if ( !empty( config( 'affiliatelinks.environments' ) ) ) {

      if ( !\App::environment( config( 'affiliatelinks.environments' ) ) ) {
        return $response;
      }
    }
    $domains = config( 'affiliatelinks.domains' );
    if ( !empty( $domains ) ) {
      $contents = $response->getContent();
      foreach ( $domains as $domain => $tags ) {
        if ( preg_match( '/' . $domain . '/i', $contents ) ) {
          $contents = $this->parseContents( $contents );
          break;
        }
      }
      $response->setContent( $contents );
    }

    return $response;
  }

  private function parseContents( $contents ) {
    $domains = config( 'affiliatelinks.domains' );
    libxml_use_internal_errors(true);
    $xml = new \DOMDocument();
    $xml->loadHTML( $contents );

    foreach ( $xml->getElementsByTagName( 'a' ) as $link ) {
      $href = $link->getAttribute( 'href' );
      $domain = parse_url( $href, PHP_URL_HOST );
      $domain = preg_replace( '/^www\./i', '', $domain );

      if ( array_key_exists( $domain, $domains ) ) {
        $tags = $domains[ $domain ];
        foreach ( explode( '&', $tags ) as $tag ) {
          $parts = explode( '=', $tag );
          if ( config( 'affiliatelinks.replace_existing' ) ) {
            $href = $this->removeVar( $href, $parts[ 0 ] );
          }
          $href = $this->addVar( $href, $parts[ 0 ], $parts[ 1 ] );
        }

        $link->setAttribute( 'href', $href );
      }
    }

    return $xml->saveHtml();
  }

  /** @link https://wp-mix.com/php-add-remove-query-string-variables/ */
  private function addVar( $url, $key, $value ) {
    $url = preg_replace( '/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&' );
    $url = substr( $url, 0, -1 );

    if ( strpos( $url, '?' ) === false ) {
      return ($url . '?' . $key . '=' . $value);
    }
    else {
      return ($url . '&' . $key . '=' . $value);
    }
  }

  /** @link https://wp-mix.com/php-add-remove-query-string-variables/ */
  function removeVar( $url, $key ) {
    $url = preg_replace( '/(.*)(?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&' );
    $url = substr( $url, 0, -1 );
    return ($url);
  }

}
