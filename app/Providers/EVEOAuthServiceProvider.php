<?php

namespace App\Providers;

use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider;
use Killmails\OAuth2\Client\Provider\EveOnline;

/**
 * 用来提供EVE SSO认证程序
 * @package App\Providers
 */
class EVEOAuthServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(EveOnline::class, function () {
            $config = [
                'clientId' => config('eve.clientId'),
                'clientSecret' => config('eve.clientSecret'),
                'redirectUri' => config('app.url') . config('eve.callbackURL'),
            ];
            // 检查必要配置是否为空
            foreach ($config as $k => $v) {
                if ($v === null) {
                    throw new \RuntimeException("$k for EVE App must be set in config");
                }
            }
            return new EveOnline($config);
        });
    }

    public function provides() {
        return [EveOnline::class];
    }
}
