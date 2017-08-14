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
            $object = Product::create([
                'code'                  => $request->input('code'),
                'field_group_id'        => $request->input('field_group_id'),
                'type_id'               => $request->input('type_id'),
                'parent_id'     => $request->input('parent_id'),
                'weight'                => $request->input('weight'),
                'active'                => $request->input('active'),
                'sort'                  => $request->input('sort'),
                'price_type_id'         => $request->input('price_type_id'),
                'subtotal'              => $request->input('subtotal'),
                'product_class_tax_id'  => $request->input('product_class_tax_id')
            ]);

            $id     = $object->id;
        }
        else
        {
            // create product with other language
            $id     = $request->input('id');
        }

        // get custom fields
        $data = [];
        if($request->has('field_group_id'))
        {
            $fields = Field::where('field_group_id', $request->input('field_group_id'))->get();
            foreach ($fields as $field)
            {
                $data['properties'][$field->name] = $request->input($field->name);
            }
        }

        // create product lang
        ProductLang::create([
            'id'            => $id,
            'lang_id'       => $request->input('lang_id'),
            'name'          => $request->input('name'),
            'slug'          => $request->input('slug'),
            'description'   => $request->input('description'),
            'data'          => $data
        ]);

        // update data_lang after create ProductLang because yu
        Product::where('market_product.id', $id)->update([
            'data_lang' => json_encode(Product::addLangDataRecord($request->input('lang_id'), $id))
        ]);

        $object = Product::builder()
            ->where('market_product.id', $id)
            ->where('market_product_lang.lang_id', $request->input('lang_id'))
            ->first();

        // set categories
        $this->setCategories($object, $request);

        // set attachments
        if(is_array($request->input('attachments')))
        {
            // first save libraries to get id
            $attachments = AttachmentService::storeAttachmentsLibrary($request->input('attachments'));

            // then save attachments
            AttachmentService::storeAttachments($attachments, 'storage/app/public/market/products', 'storage/market/products', $this->model, $object->id,  $object->lang_id);
        }

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
        Product::where('market_product.id', $id)->update([
            'code'                  => $request->input('code'),
            'field_group_id'        => $request->input('field_group_id'),
            'type_id'               => $request->input('type_id'),
            'parent_id'             => $request->input('parent_id'),
            'weight'                => $request->input('weight'),
            'active'                => $request->input('active'),
            'sort'                  => $request->input('sort'),
            'price_type_id'         => $request->input('price_type_id'),
            'subtotal'              => $request->input('subtotal'),
            'product_class_tax_id'  => $request->input('product_class_tax_id')
        ]);

        // get custom fields
        $data = [];
        if($request->has('field_group_id'))
        {
            $fields = Field::where('field_group_id', $request->input('field_group_id'))->get();
            foreach ($fields as $field)
            {
                $data['properties'][$field->name] = $request->input($field->name);
            }
        }

        // update product lang
        ProductLang::where('market_product_lang.id', $id)
            ->where('market_product_lang.lang_id', $lang)
            ->update([
                'name'          => $request->input('name'),
                'slug'          => $request->input('slug'),
                'description'   => $request->input('description'),
                'data'          => json_encode($data)
            ]);

        $object = Product::builder()
            ->where('market_product.id', $id)
            ->where('market_product_lang.lang_id', $lang)
            ->first();

        // categories
        $this->setCategories($object, $request);

        // set attachments
        if(is_array($request->input('attachments')))
        {
            // first save libraries to get id
            $attachments = AttachmentService::storeAttachmentsLibrary($request->input('attachments'));

            // then save attachments
            AttachmentService::updateAttachments($attachments, 'storage/app/public/market/products', 'storage/market/products', $this->model, $object->id,  $object->lang_id);
        }

        elseif(isset($object->data['properties']))
        {
            $data = $object->data;
            unset($data['properties']);

            // delete properties from all languages
            ProductLang::where('product_lang.id', $id)
                ->update([
                    'data' => json_encode($data)
                ]);

            $object = Product::builder()
                ->where('market_product.id', $id)
                ->where('market_product_lang.lang_id', $lang)
                ->first();
        }

        $response['status'] = "success";
        $response['data']   = $object;

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