<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\ProductLang;
use Syscover\Admin\Services\AttachmentService;
use Syscover\Market\Services\ProductService;

/**
 * Class ProductController
 * @package Syscover\Market\Controllers
 */

class ProductController extends CoreController
{
    protected $model        = Product::class;
    protected $modelLang    = ProductLang::class;

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        $response['status']     = 200;
        $response['statusText'] = "OK";
        $response['data']   = ProductService::create(request()->all());

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
        $response['status']     = 200;
        $response['statusText'] = "OK";
        $response['data']   = ProductService::update($request->all(), $id, $lang);

        return response()->json($response);
    }

    // when delete product, we destroy all attachments
    public function destroyCustom($parameters)
    {
        AttachmentService::deleteAttachments($parameters['id'], $this->model, $parameters['lang']);
    }

    private function setCategories($object, $request)
    {
        $object->categories()->sync($request->input('categories_id'));
    }
}