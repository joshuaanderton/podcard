<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

trait Billable
{
    public static function plans()
    {
        return json_decode(json_encode([]));
    }

    public function cacheStripeCustomer()
    {
        if (! $this->stripe_customer_id) {
            return $this;
        }

        try {
            $customer = \Stripe\Customer::retrieve($this->stripe_customer_id);
            $this->update(['stripe_customer' => $customer->__toJSON()]);
        } catch (\Stripe\Error\Card $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\RateLimit $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\InvalidRequest $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\Authentication $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\ApiConnection $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\Base $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Exception $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        }

        return $this;
    }

    public function setupStripeCustomer($with_plan = true)
    {
        $plans = \App\Account::plans();
        $plan_id = Cookie::get('billing_choose_plan');
        $plan_id = $with_plan && ! empty($plan_id) ? $plan_id : $plans[1]->month_id;
        $created_by = $this->users()->first();

        try {
            $stripe_customer = \Stripe\Customer::create([
                'email' => $created_by->email,
                'name' => $created_by->fullName(),
                'metadata' => ['account_id' => $this->id],
            ]);

            // TODO: make sure this plan is in plans meta
            if (! empty($plan_id)) {
                \Stripe\Subscription::create([
                    'customer' => $stripe_customer->id,
                    'items' => [['plan' => $plan_id]],
                    'trial_period_days' => ($plans[0]->month_id !== $plan_id) ? 14 : null, // No trial for free plan
                ]);
            }

            $this->update(['stripe_customer_id' => $stripe_customer->id]);
        } catch (\Stripe\Error\Card $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\RateLimit $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\InvalidRequest $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\Authentication $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\ApiConnection $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\Base $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Exception $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        }

        return $this;
    }

    public function billingUpdate($data)
    {
        $update_data = [];

        if (! $this->stripe_customer_id) {
            $this->setupStripeCustomer(false);
        }

        $stripe_customer = $this->stripeCustomer();

        if (isset($data['update_address'])) {
            $update_data['address'] = [
                'city' => $data['city'],
                'country' => $data['country'],
                'line1' => $data['line1'],
                'line2' => $data['line2'],
                'postal_code' => $data['postal_code'],
                'state' => $data['state'],
            ];

            if (isset($data['name'])) {
                $update_data['name'] = $data['name'];
            } else {
                $update_data['name'] = null;
            }
        }

        try {
            if (isset($data['update_address']) && isset($data['vat'])) {
                if ($stripe_customer->tax_ids->data && $stripe_customer->tax_ids->data[0]) {
                    \Stripe\Customer::deleteTaxId($stripe_customer->id, $stripe_customer->tax_ids->data[0]->id);
                }
                \Stripe\Customer::createTaxId($stripe_customer->id, ['type' => $data['vat_type'], 'value' => $data['vat']]);
            }

            if (! empty($data['stripe_token'])) {
                $card = \Stripe\Customer::createSource($stripe_customer->id, ['source' => $data['stripe_token']]);
                $update_data['default_source'] = $card->id;
            }

            if (! empty($data['coupon']) && ! $this->coupon()) {
                $coupon = \Stripe\Coupon::retrieve($data['coupon']);
                if ($coupon) {
                    $update_data['coupon'] = $coupon->id;
                    if (empty($data['stripe_token']) && ! $this->card()) {
                        return response()->json(['message' => __('accounts.errors.no_card'), 'no_card' => true, 'redirect_url' => false], 404);
                    }
                }
            }

            if (! empty($update_data)) {
                $customer = \Stripe\Customer::update($stripe_customer->id, $update_data);
                $this->update(['stripe_customer' => $customer->__toJSON()]);
            }

            if (! empty($data['plan_id'])) {
                if (empty($card->id) && ! $this->card() && $this->plans()[0]->month_id !== $data['plan_id']) {
                    return response()->json(['message' => __('accounts.errors.no_card'), 'no_card' => true, 'redirect_url' => false], 404);
                }

                $subscription = $this->subscription();

                if ($subscription) {
                    \Stripe\Subscription::update($subscription->id, [
                        'cancel_at_period_end' => false,
                        'trial_end' => 'now',
                        'items' => [[
                            'id' => $subscription->items->data[0]->id,
                            'plan' => $data['plan_id'],
                        ]],
                    ]);
                } else {
                    \Stripe\Subscription::create([
                        'customer' => $stripe_customer->id,
                        'items' => [['plan' => $data['plan_id']]],
                    ]);
                }

                $this->cacheStripeCustomer();
            }

            return ['redirect_url' => ! empty($data['redirect_url']) ? $data['redirect_url'] : route('account.billing')];
        } catch (\Stripe\Error\Card $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));

            return response()->json(['message' => __('Oops! There was a problem with your card.')], 404); // TODO: Need better errors here!
        } catch (\Stripe\Error\RateLimit $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\InvalidRequest $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));

            return response()->json(['message' => $e->getMessage()], 404);
        } catch (\Stripe\Error\Authentication $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\ApiConnection $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\Base $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Exception $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        }

        return response()->json(['message' => __('accounts.generic_error')], 404);
    }

    public function subscription()
    {
        $stripe_customer = $this->stripeCustomer();

        return ! empty($stripe_customer->subscriptions->data) ? $stripe_customer->subscriptions->data[0] : null;
    }

    public function coupon()
    {
        $stripe_customer = $this->stripeCustomer();

        if (
            ! empty($stripe_customer->subscriptions->data) &&
            $stripe_customer->subscriptions->data[0]->discount &&
            $stripe_customer->subscriptions->data[0]->discount->coupon
        ) {
            return $stripe_customer->subscriptions->data[0]->discount->coupon;
        }

        if (
            ! empty($stripe_customer->discount) &&
            $stripe_customer->discount->coupon
        ) {
            return $stripe_customer->discount->coupon;
        }

        return null;
    }

    public function card()
    {
        $stripe_customer = $this->stripeCustomer();

        if (empty($stripe_customer->default_source)) {
            return null;
        }

        foreach ($stripe_customer->sources->data as $source) {
            if ($source->id == $stripe_customer->default_source) {
                return $source;
            }
        }

        return null;
    }

    public function plan()
    {
        if ($this->paypal_subscription_id) {
            $paypal_amount = 500;
            $user = $this->users()->first();

            if (in_array($user->id, [4568, 4489, 4430, 5714, 4479])) {
                $paypal_amount = 2000;
            }
            if (in_array($user->id, [1805])) {
                $paypal_amount = 1400;
            }
            if (in_array($user->id, [731, 3703, 1585])) {
                $paypal_amount = 1000;
            }

            return json_decode(json_encode([
                'amount' => $paypal_amount,
                'interval' => 'month',
                'metadata' => ['internal_id' => 2],
            ]));
        }

        $subscription = $this->subscription();

        return $subscription && $subscription->items->data ? $subscription->items->data[0]->plan : null;
    }

    public function tier($user_id = false)
    {
        $plan = $this->plan();
        $internal_id = ! empty($this->plan()->metadata->internal_id) ? $this->plan()->metadata->internal_id : 0;

        // Paypal users grandfather business plan
        if ($this->paypal_subscription_id) {
            $internal_id = 2;
        }

        return $this->plans($user_id)[$internal_id];
    }

    public function trialing()
    {
        $subscription = $this->subscription();

        return $subscription ? $subscription->status == 'trialing' : null;
    }

    public function active()
    {
        $subscription = $this->subscription();

        return $subscription ? $subscription->status == 'active' : null;
    }

    public function address()
    {
        $stripe_customer = $this->stripeCustomer();
        $address = ! empty($stripe_customer->address) ? $stripe_customer->address : null;
        $name = ! empty($stripe_customer->name) ? $stripe_customer->name : null;
        $tax_ids = ! empty($stripe_customer->tax_ids->data) ? $stripe_customer->tax_ids->data : null;

        return json_decode(json_encode([
            'name' => $name ?: $this->company_name,
            'city' => $address ? $address->city : '',
            'country' => $address ? $address->country : '',
            'line1' => $address ? $address->line1 : '',
            'line2' => $address ? $address->line2 : '',
            'postal_code' => $address ? $address->postal_code : '',
            'state' => $address ? $address->state : '',
            'vat_type' => $tax_ids ? $tax_ids[0]->type : '',
            'vat' => $tax_ids ? $tax_ids[0]->value : '',
        ]));
    }

    public function invoices()
    {
        if (! $this->stripe_customer_id) {
            return [];
        }

        try {
            return \Stripe\Invoice::all(['customer' => $this->stripe_customer_id, 'limit' => 100])->data;
        } catch (\Stripe\Error\Card $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\RateLimit $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\InvalidRequest $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\Authentication $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\ApiConnection $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Stripe\Error\Base $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        } catch (\Exception $e) {
            Log::error(implode(' ', [str_replace(base_path(), '', $e->getFile()).':'.$e->getLine(), $e->getMessage()]));
        }

        return [];
    }

    public function stripeCustomer()
    {
        if (! $this->stripe_customer) {
            $this->cacheStripeCustomer();
        }

        return json_decode($this->stripe_customer ?: '{}');
    }
}
