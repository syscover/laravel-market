<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\Category;
use Syscover\Market\Services\CategoryService;

class CategoryController extends CoreController
{
    protected $model = Category::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $response['status'] = "success";
        $response['data']   = CategoryService::create($request->all());

        return response()->json($response);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param   \Illuminate\Http\Request  $request
     * @param   int     $id
     * @param   string  $lang
     * @return  \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id, $lang)
    {
        $response['status'] = "success";
        $response['data']   = CategoryService::update($request->all(), $id, $lang);

        return response()->json($response);
    }
}
