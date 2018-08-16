<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\Section;
use Syscover\Market\Services\SectionService;

class SectionMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketSection');
    }
}

class AddSectionMutation extends SectionMutation
{
    protected $attributes = [
        'name'          => 'addSection',
        'description'   => 'Add new section'
    ];

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketSectionInput'))
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return SectionService::create($args['object']);
    }
}

class UpdateSectionMutation extends SectionMutation
{
    protected $attributes = [
        'name' => 'updateSection',
        'description' => 'Update section'
    ];

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketSectionInput'))
            ],
        ];
    }

    public function resolve($root, $args)
    {
        return SectionService::update($args['object']);
    }
}

class DeleteSectionMutation extends SectionMutation
{
    protected $attributes = [
        'name' => 'deleteSection',
        'description' => 'Delete section'
    ];

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        $object = SQLService::destroyRecord($args['id'], Section::class);

        return $object;
    }
}
