<?php namespace Syscover\Market\GraphQL\Services;

use Syscover\Core\GraphQL\Services\CoreGraphQLService;
use Syscover\Market\Models\Section;
use Syscover\Market\Services\SectionService;

class SectionGraphQLService extends CoreGraphQLService
{
    protected $model = Section::class;
    protected $serviceClassName = SectionService::class;
}
