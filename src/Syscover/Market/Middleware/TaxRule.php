<?php namespace Syscover\Market\Middleware;

use Closure;
use Syscover\Market\Models\TaxRule as TaxRuleModel;

class TaxRule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(auth('crm')->check())
        {
            $customer = auth('crm')->user();

            if(! empty($customer->country_id))
            {
                // set country tax rule
                config(['pulsar.market.taxCountryDefault' => $customer->country_id]);
            }

            if($customer->class_tax != null)
            {
                // set group customer
                config(['pulsar.market.taxCustomerClassDefault' => $customer->class_tax]);
            }
        }

        // Set tax rules in session
        if(session('pulsar.market.taxRules') === null)
        {
            $taxRules = TaxRuleModel::builder()
                ->where('country_id', config('pulsar.market.taxCountryDefault'))
                ->where('customer_class_tax_id', config('pulsar.market.taxCustomerClassDefault'))
                ->orderBy('priority', 'asc')
                ->get();

            session(['pulsar.market.taxRules' => $taxRules]);
        }

        return $next($request);
    }
}
