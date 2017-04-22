<?php namespace Syscover\Market\Middleware;

use Closure;

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
                config(['pulsar.market.taxCountry' => $customer->country_id]);
            }

            if($customer->classTax != null)
            {
                // set group customer
                config(['pulsar.market.taxCustomerClass' => $customer->class_tax]);
            }
        }
        return $next($request);
    }
}
