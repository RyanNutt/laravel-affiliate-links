<?php

namespace Aelora\AffiliateLinks;

use Illuminate\Support\ServiceProvider;

class AffiliateLinksProvider extends ServiceProvider {

  /**
   * Register services.
   *
   * @return void
   */
  public function register() {
    //
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot() {
    $this->publishes( [
        __DIR__ . '/affiliatelinks.php' => config_path( 'affiliatelinks.php' )
    ], 'config' );
  }

}
