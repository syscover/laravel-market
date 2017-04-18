<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\Category;

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
        $category= Category::create([
            'lang_id'               => $request->input('lang_id'),
            'parent_id'             => $request->input('parent_id'),
            'name'                  => $request->input('name'),
            'slug'                  => $request->input('slug'),
            'active'                => $request->has('active'),
            'description'           => $request->input('description'),
            'data_lang'             => Category::addLangDataRecord($request->input('lang_id'), $request->input('id'))
        ]);

        $response['status'] = "success";
        $response['data']   = $category;

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
        Category::where('id', $id)->where('lang_id', $lang)->update([
            'parent_id'             => $request->input('parent_id'),
            'name'                  => $request->input('name'),
            'slug'                  => $request->input('slug'),
            'active'                => $request->has('active'),
            'description'           => $request->input('description'),
        ]);

        $category = Category::where($request->input('id'));

        $response['status'] = "success";
        $response['data']   = $category;

        return response()->json($response);
    }
}
