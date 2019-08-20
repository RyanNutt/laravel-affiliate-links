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

    if ( $request instanceof \Illuminate\Http\Response && $request->isMethod( 'GET' ) && $response->getStatusCode() == 200 ) {

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
    }

    return $response;
  }

  private function parseContents( $contents ) {
    $domains = config( 'affiliatelinks.domains' );

    $contents = preg_replace_callback( '/<a(.*?)href="(.*?)"(.*?)>/', function($m) use ($domains) {
      $original = $m[ 2 ];
      $m[ 2 ] = html_entity_decode( $m[ 2 ] );
      $url = preg_replace( '/^www\./i', '', parse_url( $m[ 2 ], PHP_URL_HOST ) );
      if ( array_key_exists( $url, $domains ) ) {
        $all_tags = $domains[ $url ];
        foreach ( explode( '&', $all_tags ) as $tag ) {
          $tag_info = explode( '=', $tag );
          $m[ 2 ] = $this->addVar( $m[ 2 ], $tag_info[ 0 ], $tag_info[ 1 ] );
        }
        return str_replace( $original, $m[ 2 ], $m[ 0 ] );
      }
      return $m[ 0 ];
    }, $contents );

    return $contents;
  }

  /** @link https://wp-mix.com/php-add-remove-query-string-variables/ */
  private function addVar( $url, $key, $value ) {
    $url = preg_replace( '/(.*)(\?|&)' . $key . '=[^&]+?(&)(.*)/i', '$1$2$4', $url . '&' );
    $url = substr( $url, 0, -1 );

    if ( strpos( $url, '?' ) === false ) {
      return ($url . '?' . $key . '=' . $value);
    }
    else {
      return ($url . '&' . $key . '=' . $value);
    }
  }

}
