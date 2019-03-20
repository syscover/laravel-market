<?php namespace Syscover\Market\Controllers;

use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\Section;
use Syscover\Market\Services\SectionService;

class SectionController extends CoreController
{
    public function __construct(Section $model, SectionService $service)
    {
        $this->model = $model;
        $this->service = $service;
    }
}
