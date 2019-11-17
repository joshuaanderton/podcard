<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Account extends Model
{
    use \App\Helpers\Billable, \App\Helpers\Helpable;

    protected $fillable = [
        'name',
        'app_id',
        'api_token',
        'stripe_customer_id',
        'stripe_customer',
        'contacts_count',
        'stripe_connect_user_id',
        'stripe_connect_access_token',
        'stripe_connect_refresh_token',
    ];

    public function podcasts()
    {
        return $this->hasMany('App\Podcast');
    }

    public function __construct()
    {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
            \Stripe\Stripe::setApiVersion(env('STRIPE_API_VERSION'));
        } catch (\Stripe\Error\ApiConnection $e) {
            Log::error(join(' ', [str_replace(base_path(), '', $e->getFile()) . ':' . $e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\Base $e) {
            Log::error(join(' ', [str_replace(base_path(), '', $e->getFile()) . ':' . $e->getLine(), $e->getMessage()]));
        } catch (\Exception $e) {
            Log::error(join(' ', [str_replace(base_path(), '', $e->getFile()) . ':' . $e->getLine(), $e->getMessage()]));
        }
    }

    public function users()
    {
        return $this->hasManyThrough(
            'App\User',
            'App\AccountUser',
            'account_id',
            'id',
            'id',
            'user_id'
        );
    }

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }

    static function accountUserSetup(array $data)
    {
        $account = \App\Account::create($data);
        $user = \App\User::create($data);

        \App\AccountUser::create([
            'account_id' => $account->id,
            'user_id' => $user->id
        ]);

        $account
            ->setupStripeCustomer()
            ->cacheStripeCustomer();

        //\Mail::to($user->email)->send(new \App\Mail\UserWelcome($user));

        return $user;
    }

    static function plans()
    {
        $is_prod = \App::environment('production');
        //$trial_coupon_code = $is_prod ? '' : '';

        $plans = [[
            'name'         => 'Starter',
            'internal_id'  => 0,
            'month_id'     => $is_prod ? '' : '',
            'month_price'  => 0,
            //'trial_coupon' => $trial_coupon_code
        ]];

        return json_decode(json_encode($plans));
    }

    public function importStripeCustomers()
    {
        $stripe = new \App\Helpers\StripeConnect($this->stripe_connect_user_id);

        $customers = $starting_after = null;

        while ($customers == null || $stripe_customers->has_more) :
            if ($customers == null) $customers = [];
            $stripe_customers = $stripe->customers($starting_after);

            if (count($stripe_customers->data) == 0) break;

            $starting_after = $stripe_customers->data[count($stripe_customers->data)-1]->id;

            foreach ($stripe_customers->data as $sc) :
                if (!$sc->email) continue;

                $customers[] = [
                    'account_id'         => $this->id,
                    'stripe_customer_id' => $sc->id,
                    'email'              => $sc->email,
                    'first_name'         => $sc->first_name,
                    'last_name'          => $sc->last_name,
                    'stripe_customer'    => $sc->__toJSON()
                ];
            endforeach;
        endwhile;

        \App\Contact::insert($customers);

        return $stripe_customers;
    }
}