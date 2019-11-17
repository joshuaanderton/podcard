<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;

class AccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        // try {
        //     \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        //     \Stripe\Stripe::setApiVersion(env('STRIPE_API_VERSION'));
        // } catch (\Stripe\Error\ApiConnection $e) {
        //     Log::error(join(' ', [str_replace(base_path(), '', $e->getFile()) . ':' . $e->getLine(), $e->getMessage()]));
        // } catch (\Stripe\Error\Base $e) {
        //     Log::error(join(' ', [str_replace(base_path(), '', $e->getFile()) . ':' . $e->getLine(), $e->getMessage()]));
        // } catch (\Exception $e) {
        //     Log::error(join(' ', [str_replace(base_path(), '', $e->getFile()) . ':' . $e->getLine(), $e->getMessage()]));
        // }
    }

    public function edit()
    {
        $account = Auth::user()->currentAccount();

        return view('accounts.edit', [
            'user' => Auth::user(),
            'account' => $account,
            'podcasts' => $account->podcasts()->get()
        ]);
    }

    public function stripeConnect()
    {
        $tokens = \App\Helpers\StripeConnect::token();

        if (!empty($tokens['stripe_user_id'])) :
            Auth::user()->currentAccount()->update([
                'stripe_connect_user_id'       => $tokens['stripe_user_id'],
                'stripe_connect_access_token'  => $tokens['access_token'],
                'stripe_connect_refresh_token' => $tokens['refresh_token'],
            ]);

            Auth::user()->currentAccount()->importStripeCustomers();
        endif;

        return redirect()->route('accounts.edit');
    }

    public function stripeConnectDelete()
    {
        $account = Auth::user()->currentAccount();
        \App\Helpers\StripeConnect::tokenRevoke($account->stripe_connect_user_id);
        $account->update(['stripe_connect_user_id' => null]);
        $account->contacts()->delete();

        return redirect()->route('accounts.edit');
    }

    public function users(Request $request)
    {
        if (Auth::user()->currentAccount()->tier()->internal_id < 3) :
            return redirect(route('account.edit'));
        endif;

        $search = !empty($request->search) ? $request->search : null;

        $account = Auth::user()->currentAccount();
        $users = $account->users()->latest('created_at');

        return view('accounts.users', [
            'account' => $account,
            'invites' => \App\AccountUser::whereNotNull('invite_token')->where('invite_email', 'LIKE', "%{$search}%")->get(),
            'users' => $users->where('email', 'LIKE', "%{$search}%")->get()
        ]);
    }

    public function admin(Request $request)
    {
        if (!Auth::user()->isAdmin()) return redirect(route('account.edit'));

        $search = !empty($request->search) ? $request->search : null;
        $users  = \App\User::latest('created_at');
        $users  = $users
                    ->where('id', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('first_name', 'LIKE', "%{$search}%")
                    ->orWhere('last_name', 'LIKE', "%{$search}%")
                    ->paginate(6);

        return view('accounts.admin', [
            'users' => $users,
        ]);
    }

    public function adminUserDelete(\App\User $user)
    {
        if (!Auth::user()->isAdmin()) return response()->json(['message' => __('accounts.generic_error')], 404);

        $user->deleteData();

        return response()->json(['message' => 'User deleted for all eternity!', 'redirect_url' => true], 200);
    }

    public function adminUserLogin(\App\User $user)
    {
        if (Auth::user()->isAdmin()) :
            Auth::logout();
            Auth::login($user);
        endif;

        return redirect()->route('app');
    }

    public function billing()
    {
        $account = Auth::user()->currentAccount();
        $subscription = $account->subscription();
        return view('accounts.billing', [
            'account'        => $account,
            'address'        => $account->address(),
            'invoices'       => $account->invoices(),
            'subscription'   => $subscription,
            'card'           => $account->card(),
            'plan'           => $account->plan(),
            'plans'          => \App\Account::plans(),
            'button_classes' => 'btn btn-outline-secondary btn-sm'
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->all();

        foreach([
            'double_optin',
            'disable_email_login',
            'subscribed_to_notifications',
            'subscribed_to_emails',
        ] as $key) :
            if (!isset($data[$key])) $data[$key] = '0';
        endforeach;

        try {
            $user = Auth::user();

            $user->update($data);
            $user->currentAccount()->update($data);
            $user->intercomUpdate();

            return ['message' => __('accounts.updated_confirmation')];
        } catch (\Exception $e) {
            Log::error(join(' ', [str_replace(base_path(), '', $e->getFile()) . ':' . $e->getLine(), $e->getMessage()]));
        }

        return response()->json(['message' => __('accounts.generic_error')], 404);
    }

    public function updatePassword(Request $request)
    {
        $data = $request->all();

        if (empty($data['password'])) :
            return response()->json(['message' => __('Hmmm...Did you enter a password?')], 404);
        endif;

        if ($data['password'] !== $data['password_confirm']) :
            return response()->json(['message' => __('Oops! Passwords don\'t match.')], 404);
        endif;

        try {
            Auth::user()->update(['password' => Hash::make($data['password'])]);
            return ['message' => __('accounts.updated_confirmation')];
        } catch (\Exception $e) {
            Log::error(join(' ', [str_replace(base_path(), '', $e->getFile()) . ':' . $e->getLine(), $e->getMessage()]));
        }

        return response()->json(['message' => __('accounts.generic_error')], 404);
    }

    public function billingUpdate(Request $request)
    {
        $account = Auth::user()->currentAccount();
        return $account->billingUpdate($request->all());
    }

    public function hasCard()
    {
        $account = Auth::user()->currentAccount();
        return ['has_card' => !empty($account->card()), 'redirect_url' => false];
    }

    public function cancelSubscription(Request $request)
    {
        Auth::user()->currentAccount()->deleteData();
        return redirect()->route('app');
    }
    public function accountSwitch(\App\Account $account)
    {
        if (Auth::user()->accounts()->find($account->id)->exists()) :
            Cookie::queue('current_account', $account->id, 2628000);
        endif;
        return redirect()->route('app');
    }

    public function userInvite(Request $request)
    {
        if (empty($request->email)) return response()->json(['message' => 'Oops! Need an email address.'], 404);

        $account = Auth::user()->currentAccount();
        $invite_token = str_random(60);
        $email = $request->email;

        \App\AccountUser::create([
            'account_id'   => $account->id,
            'user_id'      => 0, //Good idea?
            'invite_token' => $invite_token,
            'invite_email' => $request->email
        ]);

        $user = Auth::user()->first();
        $invite_url = route('account.users.activate', ['token' => $invite_token]);

        $invite_notification = new \App\Send([
            'from_email' => $user->email ?: 'invite@upscri.be',
            'from_name'  => $user->fullName(),
            'subject'    => "Join my " . env('APP_NAME') . " account!",
            'recipients' => [[
                'to_email'    => $request->email,
                'to_name'     => '',
                'custom_subs' => ['*|UNSUBSCRIBE|*' => '']
            ]],
            'body' => "Hey friend!<br/><br/>I'm inviting you to join my " . env('APP_NAME') . " account so we can build forms and sent emails together. Woot woot!<br/><br/><a href='{$invite_url}'>Click here to join!</a>",
        ]);

        $invite_notification->email();

        return response()->json(['redirect_url' => true], 200);
    }

    public function userActivate(string $token)
    {
        if (!empty($token) && $invite = \App\AccountUser::where('invite_token', $token)->first()) :
            if (Auth::user()->accounts()->find($invite->account_id)->exists()) :
                \App\AccountUser::where('invite_token', $token)->delete();
            else :
                $invite->update([
                    'invite_token' => null,
                    'invite_email' => null,
                    'user_id'      => Auth::user()->id
                ]);
                Cookie::queue('current_account', $invite->account_id, 2628000);
            endif;
        endif;

        return redirect()->route('app');
    }

    public function inviteDestroy(string $token)
    {
        \App\AccountUser::where('invite_token', $token)->delete();
        return response()->json(['redirect_url' => true], 200);
    }

    public function userDestroy(\App\User $user)
    {
        \App\AccountUser::where(['account_id' => Auth::user()->currentAccount()->id, 'user_id' => $user->id])->delete();
        return response()->json(['redirect_url' => true], 200);
    }
}