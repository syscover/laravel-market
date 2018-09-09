<?php namespace Syscover\Market\Middleware;

use Closure;
use Syscover\Market\Services\TaxRuleService;

class TaxRule
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @param  string                   $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = 'crm')
    {
        // Set default tax rules in session
        if(session('pulsar-market.tax_rules') === null)
        {
            TaxRuleService::setTaxRules(TaxRuleService::getCustomerTaxRules());
        }

        return $next($request);
    }
}