<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Admin\Models\Field;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\ProductLang;
use Syscover\Admin\Services\AttachmentService;

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
    public function store(Request $request)
    {
        if(! $request->has('id'))
        {
            // create new product
            $product = Product::create([
                'field_group_id'        => $request->input('field_group_id'),
                'product_type_id'       => $request->input('product_type_id'),
                'parent_product_id'     => $request->input('parent_product_id'),
                'weight'                => $request->input('weight'),
                'active'                => $request->input('active'),
                'sort'                  => $request->input('sort'),
                'price_type_id'         => $request->input('price_type_id'),
                'subtotal'              => $request->input('subtotal'),
                'product_class_tax_id'  => $request->input('product_class_tax_id')
            ]);

            $id     = $product->id;
            $idAux  = null;
        }
        else
        {
            // create product with other language
            $id     = $request->input('id');
            $idAux  = $id;
        }

        $productLang = ProductLang::create([
            'id'            => $id,
            'lang_id'       => $request->input('lang_id'),
            'name'          => $request->input('name'),
            'slug'          => $request->input('slug'),
            'description'   => $request->input('description'),
        ]);

        // update data_lang after create ProductLang because yu
        Product::where('product.id', $id)->update([
            'data_lang' => json_encode(Product::addLangDataRecord($request->input('lang_id'), $idAux))
        ]);

        $product = Product::builder()
            ->where('product.id', $id)
            ->where('product_lang.lang_id', $request->input('lang_id'))
            ->first();

        // set categories
        if(is_array($request->input('categories_id')))
        {
            $product->categories()->sync($request->input('categories_id'));
        }

        // set attachments
        if(is_array($request->input('attachments')))
        {
            // first save libraries to get id
            $attachments = AttachmentService::storeAttachmentsLibrary($request->input('attachments'));

            // then save attachments
            AttachmentService::storeAttachments($attachments, 'storage/app/public/market/products', 'storage/market/products', $this->model, $product->id,  $product->lang_id);
        }

        // set custom fields
        if($request->has('field_group_id'))
        {
            $fields = Field::where('field_group_id', $request->input('field_group_id'))->get();
            $data = [];
            foreach ($fields as $field)
            {
                $data['properties'][$field->name] = $request->input($field->name);
            }

            $productLang->data = $data;
            $productLang->save();

            ProductLang::where('id', $id)->where('lang_id', $request->input('lang_id'))->update([
                'data' => json_encode($data)
            ]);
        }

        $object = $product;

        $response['status'] = "success";
        $response['data']   = $object;

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
        // update product
        Product::where('product.id', $id)->update([
            'field_group_id'        => $request->input('field_group_id'),
            'product_type_id'       => $request->input('product_type_id'),
            'parent_product_id'     => $request->input('parent_product_id'),
            'weight'                => $request->input('weight'),
            'active'                => $request->input('active'),
            'sort'                  => $request->input('sort'),
            'price_type_id'         => $request->input('price_type_id'),
            'subtotal'              => $request->input('subtotal'),
            'product_class_tax_id'  => $request->input('product_class_tax_id')
        ]);

        ProductLang::where('product_lang.id', $id)
            ->where('product_lang.lang_id', $lang)
            ->update([
                'name'          => $request->input('name'),
                'slug'          => $request->input('slug'),
                'description'   => $request->input('description')
            ]);

        $product = Product::builder()
            ->where('product.id', $id)
            ->where('product_lang.lang_id', $lang)
            ->first();

        // categories
        if(is_array($request->input('categories_id')))
        {
            $product->categories()
                ->sync($request->input('categories_id'));
        }
        else
        {
            $product->categories()
                ->detach();
        }

        // set attachments
        if(is_array($request->input('attachments')))
        {
            // first save libraries to get id
            $attachments = AttachmentService::storeAttachmentsLibrary($request->input('attachments'));

            // then save attachments
            AttachmentService::updateAttachments($attachments, 'storage/app/public/market/products', 'storage/market/products', $this->model, $product->id,  $product->lang_id);
        }

        // set custom fields
        if($request->has('field_group_id'))
        {
            $fields = Field::where('field_group_id', $request->input('field_group_id'))->get();
            $data = [];
            foreach ($fields as $field)
            {
                $data['properties'][$field->name] = $request->input($field->name);
            }

            ProductLang::where('id', $id)->where('lang_id', $request->input('lang_id'))->update([
                'data' => json_encode($data)
            ]);
        }

        $response['status'] = "success";
        $response['data']   = $product;

        return response()->json($response);
    }

    public function destroyCustom($parameters)
    {
        AttachmentService::deleteAttachments($parameters['id'], $this->model, $parameters['lang']);
    }

    public function apiCheckSlug(Request $request)
    {
        return response()->json([
            'status'    => 'success',
            'slug'      => ProductLang::checkSlug('slug', $request->input('slug'), $request->input('id'))
        ]);
    }
}