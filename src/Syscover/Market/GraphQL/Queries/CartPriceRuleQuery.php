<?php namespace Syscover\Market\GraphQL\Queries;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Query;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\CartPriceRule;

class CartPriceRuleQuery extends Query
{
    protected $attributes = [
        'name'          => 'CartPriceRuleQuery',
        'description'   => 'Query to get a cart price rule'
    ];

    public function type()
    {
        return GraphQL::type('MarketCartPriceRule');
    }

    public function args()
    {
        return [
            'sql' => [
                'name'          => 'sql',
                'type'          => Type::listOf(GraphQL::type('CoreSQLQueryInput')),
                'description'   => 'Field to add SQL operations'
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $query = SQLService::getQueryFiltered(CartPriceRule::builder(), $args['sql']);

        return $query->first();
    }
}