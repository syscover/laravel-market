<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\ProductLang;

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
                //'subtotal'              => $this->getSubtotalOverTotal(),
                'product_class_tax_id'  => $request->input('product_class_tax_id')
            ]);

            $id     = $product->id;
            $idLang = null;
        }
        else
        {
            // create product with other language
            $id     = $request->input('id');
            $idLang = $id;
        }

        // update product with data lang
        Product::where('id', $id)->update([
            'data_lang' => Product::addLangDataRecord($request->input('lang_id'), $idLang),
        ]);

        ProductLang::create([
            'id'            => $id,
            'lang_id'       => $request->input('lang_id'),
            'name'          => $request->input('name'),
            'slug'          => $request->input('slug'),
            'description'   => $request->input('description'),
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
        //$attachments = json_decode($request->input('attachments'));
        //AttachmentLibrary::storeAttachments($attachments, $this->package, 'market-product', $id, $this->request->input('lang'));

        // set custom fields
        //if(!empty($this->request->input('customFieldGroup')))
          //  CustomFieldResultLibrary::storeCustomFieldResults($this->request, $this->request->input('customFieldGroup'), 'market-product', $id, $this->request->input('lang'));


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
        Product::where('id', $id)->update([
            'field_group_id'        => $request->input('field_group_id'),
            'product_type_id'       => $request->input('product_type_id'),
            'parent_product_id'     => $request->input('parent_product_id'),
            'weight'                => $request->input('weight'),
            'active'                => $request->input('active'),
            'sort'                  => $request->input('sort'),
            'price_type_id'         => $request->input('price_type_id'),
            //'subtotal'              => $this->getSubtotalOverTotal(),
            'product_class_tax_id'  => $request->input('product_class_tax_id')
        ]);

        ProductLang::where('id', $id)
            ->where('lang_id', $lang)
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

        $response['status'] = "success";
        $response['data']   = $product;

        return response()->json($response);
    }

    public function showCustom($parameters, $product)
    {
        // add categories to object
        $product->categories = $product->categories()->where('lang_id', $parameters['lang'])->get();
        $product->categories_id = $product->categories->pluck('id');

        return $product;
    }

    public function apiCheckSlug(Request $request)
    {
        return response()->json([
            'status'    => 'success',
            'slug'      => ProductLang::checkSlug('slug', $request->input('slug'), $request->input('id'))
        ]);
    }
}