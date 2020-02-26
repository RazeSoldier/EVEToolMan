<?php

namespace App\Http\Controllers;

use App\RefreshCode;
use Illuminate\Http\Request;
use Killmails\OAuth2\Client\Provider\EveOnline;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;

class AddWatcher extends Controller
{
    /**
     * 重定向到EVE SSO
     * @param Request $request
     * @param EveOnline $authService
     */
    public function index(Request $request, EveOnline $authService)
    {
        $authorizationUrl = $authService->getAuthorizationUrl(['scope' => 'esi-characters.read_notifications.v1']);
        $request->session()->pull('oauth2state', $authService->getState());
        return redirect()->away($authorizationUrl);
    }

    /**
     * 除了从EVE SSO返回的授权码，并向ESI请求访问令牌和刷新令牌
     * @param Request $request
     * @param EveOnline $authService
     */
    public function handleCode(Request $request, EveOnline $authService)
    {
        if (!$request->exists(['state', 'code']) ||
            $request->session()->has('oauth2state') && $request->session()->get('oauth2state') !== $request->get('state'))
        {
            $request->session()->remove('oauth2state');
            return response('Invalid state', 403);
        }
        try {
            $accessToken = $authService->getAccessToken('authorization_code', ['code' => $request->get('code')]);
        } catch (IdentityProviderException $e) {
            return response($e->getMessage());
        }

        $uid = $authService->getResourceOwner($accessToken)->toArray()['CharacterID'];
        if (RefreshCode::where([['character_id', $uid], ['scope', 'esi-characters.read_notifications.v1']])->exists()) {
            return '你已经是主权监视者了';
        }
        $model = new RefreshCode();
        $model->character_id = $uid;
        $model->refresh_code = $accessToken->getRefreshToken();
        $model->scope = 'esi-characters.read_notifications.v1';
        $model->save();
        return '成功成为主权监视者';
    }
}
