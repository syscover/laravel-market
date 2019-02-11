<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Section;
use Syscover\Market\Services\SectionService;

class SectionGraphQLService extends CoreGraphQLService
{
    public function __construct(Section $model, SectionService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
