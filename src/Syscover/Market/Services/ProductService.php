<?php namespace Syscover\Market\Services;

use Syscover\Admin\Services\AttachmentService;
use Syscover\Market\Models\Category;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\ProductLang;

class ProductService
{
    /**
     * Function to create a product
     * @param   array   $object
     * @return  \Syscover\Market\Models\Product
     * @throws  \Exception
     */
    public static function create($object)
    {
        // check if there is id
        if(empty($object['id']))
        {
            // create new product
            $product = Product::create($object);
            $object['id'] = $product->id;
        }

        // get custom fields
        if(isset($object['field_group_id'])) $object['data']['customFields'] = $object['customFields'];

        // create product lang
        $product = ProductLang::create($object);

        // update data_lang after create ProductLang
        Product::where('market_product.id', $object['lang_id'])
            ->update([
                'data_lang' => json_encode(Product::addLangDataRecord($object['lang_id'], $object['id']))
            ]);

        // get object with builder, to get every relations
        $product = Product::builder()
            ->where('market_product.id', $product->id)
            ->where('market_product_lang.lang_id', $product->lang_id)
            ->first();

        // set categories
        $product->categories()->sync($object['categories_id']);

        // set attachments
        if(is_array($object['attachments']))
        {
            // first save libraries to get id
            $attachments = AttachmentService::storeAttachmentsLibrary($object['attachments']);

            // then save attachments
            AttachmentService::storeAttachments($attachments, 'storage/app/public/market/products', 'storage/market/products', Product::class, $product->id, $product->lang_id);
        }

        return $product;
    }

    /**
     * @param   array     $object     contain properties of product
     * @param   int       $id         id of product
     * @param   string    $lang       lang of product
     * @return  \Syscover\Market\Models\Product
     */
    public static function update($object, $id, $lang)
    {
        $object = collect($object);

        // update product
        Product::where('market_product.id', $id)
            ->update([
                'code'                  =>  $object->get('code'),
                'field_group_id'        =>  $object->get('field_group_id'),
                'type_id'               =>  $object->get('type_id'),
                'parent_id'             =>  $object->get('parent_id'),
                'weight'                =>  $object->get('weight'),
                'active'                =>  $object->get('active'),
                'sort'                  =>  $object->get('sort'),
                'price_type_id'         =>  $object->get('price_type_id'),
                'subtotal'              =>  $object->get('subtotal'),
                'product_class_tax_id'  =>  $object->get('product_class_tax_id')
            ]);

        // get custom fields
        $data = [];
        if($object->has('field_group_id')) $data['customFields'] = $object->get('customFields');

        // update product lang
        ProductLang::where('market_product_lang.id', $id)
            ->where('market_product_lang.lang_id', $lang)
            ->update([
                'name'          => $object->get('name'),
                'slug'          => $object->get('slug'),
                'description'   => $object->get('description'),
                'data'          => json_encode($data)
            ]);

        $product = Product::builder()
            ->where('market_product.id', $id)
            ->where('market_product_lang.lang_id', $lang)
            ->first();

        // set categories
        $product->categories()->sync($object->get('categories_id'));

        // set attachments
        if(is_array($object->get('attachments')))
        {
            // first save libraries to get id
            $attachments = AttachmentService::storeAttachmentsLibrary($object->get('attachments'));

            // then save attachments
            AttachmentService::updateAttachments($attachments, 'storage/app/public/market/products', 'storage/market/products', Product::class, $product->id,  $product->lang_id);
        }

        return $product;
    }
}