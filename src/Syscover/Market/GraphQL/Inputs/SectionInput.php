<?php namespace Syscover\Market\GraphQL\Inputs;

use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Type as GraphQLType;

class SectionInput extends GraphQLType
{
    protected $attributes = [
        'name'          => 'SectionInput',
        'description'   => 'Section of webpage to implement in market'
    ];

    protected $inputObject = true;

    public function fields()
    {
        return [
            'ix' => [
                'type' => Type::int(),
                'description' => 'The index of section'
            ],
            'id' => [
                'type' => Type::string(),
                'description' => 'The id of section'
            ],
            'lang_id' => [
                'type' => Type::string(),
                'description' => 'lang of section'
            ],
            'name' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The name of section'
            ],
            'slug' => [
                'type' => Type::nonNull(Type::string()),
                'description' => 'The slug of section'
            ],
            'data_lang' => [
                'type' => Type::listOf(Type::string()),
                'description' => 'JSON string that contain information about object translations'
            ]
        ];
    }
}