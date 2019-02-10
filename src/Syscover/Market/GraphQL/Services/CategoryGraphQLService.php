<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Category;
use Syscover\Market\Services\CategoryService;

class CategoryGraphQLService extends CoreGraphQLService
{
    protected $model = Category::class;
    protected $serviceClassName = CategoryService::class;
}
