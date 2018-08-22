<?php namespace Syscover\Market\GraphQL\Types;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class SectionType extends GraphQLType {

    protected $attributes = [
        'name'          => 'Section',
        'description'   => 'Section of web page to implement in market'
    ];

    public function fields()
    {
        return [
            'ix' => [
                'type' => Type::nonNull(Type::int()),
                'description' => 'The id of section'
            ],
            'id' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The id of section'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of section'
            ],
            'slug' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The slug of section'
            ]
        ];
    }
}