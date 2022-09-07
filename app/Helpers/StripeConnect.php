<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;

class StripeConnect
{
    public function __construct($account_id)
    {
        $this->account_id = $account_id;
    }

    public static function authUrl()
    {
        $args = [
            'client_id' => env('STRIPE_CONNECT_CLIENT_ID'),
            'redirect_uri' => route('accounts.stripe_connect'),
            'response_type' => 'code',
            'scope' => 'read_write',
        ];

        return 'https://dashboard.stripe.com/oauth/authorize?'.http_build_query($args);
    }

    public static function token()
    {
        $req = [
            'code' => $_GET['code'],
            'grant_type' => 'authorization_code',
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://connect.stripe.com/oauth/token?'.http_build_query($req),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => ['client_secret' => env('STRIPE_SECRET_KEY')],
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        curl_close($curl);

        if (! empty($resp->error)) {
            Log::error(implode(' ', [__FILE__, 'line:', __LINE__, "Stripe - {$resp->error}"]));

            return false;
        }

        return [
            'stripe_user_id' => $resp->stripe_user_id,
            'access_token' => $resp->access_token,
            'refresh_token' => $resp->refresh_token,
        ];
    }

    public static function tokenRefresh($refresh_token)
    {
        $req = [
            'grant_type' => 'authorization_code',
            'refresh_token' => $refresh_token,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://connect.stripe.com/oauth/token?'.http_build_query($req),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => ['client_secret' => env('STRIPE_SECRET_KEY')],
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        curl_close($curl);

        if (! empty($resp->error)) {
            Log::error(implode(' ', [__FILE__, 'line:', __LINE__, "Stripe - {$resp->error}"]));

            return false;
        }

        return [
            'access_token' => $resp->access_token,
            'refresh_token' => $resp->refresh_token,
        ];
    }

    public static function tokenRevoke($stripe_user_id)
    {
        $req = [
            'client_id' => env('STRIPE_CONNECT_CLIENT_ID'),
            'stripe_user_id' => $stripe_user_id,
        ];

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_URL => 'https://connect.stripe.com/oauth/deauthorize?'.http_build_query($req),
            CURLOPT_POST => 1,
            CURLOPT_POSTFIELDS => ['client_secret' => env('STRIPE_SECRET_KEY')],
            CURLOPT_HTTPHEADER => ['Accept: application/json'],
        ]);

        $resp = curl_exec($curl);
        $resp = json_decode($resp);

        curl_close($curl);

        if (! empty($resp->error)) {
            Log::error(implode(' ', [__FILE__, 'line:', __LINE__, "Stripe - {$resp->error}"]));

            return false;
        }

        return true;
    }

    public function customers($starting_after = null)
    {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            \Stripe\Stripe::setApiVersion(env('STRIPE_API_VERSION'));
        } catch (\Stripe\Error\ApiConnection $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\Base $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Exception $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        }

        return \Stripe\Customer::all(['limit' => 100, 'starting_after' => $starting_after], ['stripe_account' => $this->account_id]);
    }

    public static function event(Request $request)
    {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            \Stripe\Stripe::setApiVersion(env('STRIPE_API_VERSION'));
        } catch (\Stripe\Error\ApiConnection $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\Base $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Exception $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        }

        $payload = json_decode($request->getContent(), true);
        $event = null;

        if (empty($payload)) {
            return response()->json(['message' => __('No data submitted.')], 404);
        }

        try {
            $event = \Stripe\Event::constructFrom($payload);
        } catch(\UnexpectedValueException $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));

            return response()->json(['message' => __('accounts.generic_error')], 404);
        }

        return $event;
    }
}
