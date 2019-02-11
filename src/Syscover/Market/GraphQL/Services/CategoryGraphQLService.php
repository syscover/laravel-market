<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Category;
use Syscover\Market\Services\CategoryService;

class CategoryGraphQLService extends CoreGraphQLService
{
    public function __construct(Category $model, CategoryService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
