<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\Category;
use Syscover\Market\Services\CategoryService;

class CategoryController extends CoreController
{
    protected $model = Category::class;

    public function store(Request $request)
    {
        $response['status'] = "success";
        $response['data']   = CategoryService::create($request->all());

        return response()->json($response);
    }

    public function update(Request $request)
    {
        $response['status'] = "success";
        $response['data']   = CategoryService::update($request->all());

        return response()->json($response);
    }
}
