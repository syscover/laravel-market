<?php namespace Syscover\Market\Services;

use Syscover\Admin\Services\AttachmentService;
use Syscover\Market\Models\Category;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\ProductLang;

class ProductService
{
    public static function create($object)
    {
        ProductService::checkCreate($object);

        // check if there is id
        if(empty($object['id']))
        {
            // create new product
            $product = Product::create(ProductService::builder($object, ['sku', 'field_group_id', 'type_id', 'parent_id', 'weight', 'active', 'sort', 'price_type_id', 'subtotal', 'product_class_tax_id', 'data_lang']));
            $object['id'] = $product->id;
        }

        // get custom fields
        if(isset($object['field_group_id'])) $object['data']['customFields'] = $object['customFields'];

        // create product lang
        $product = ProductLang::create(ProductService::builder($object, ['id', 'lang_id', 'name', 'slug', 'description', 'data']));

        // product already is create, it's not necessary update product with data_lang value
        Product::addDataLang($object['lang_id'], $object['id']);

        // get object with builder, to get every relations
        $product = Product::builder()
            ->where('market_product.id', $product->id)
            ->where('market_product_lang.lang_id', $product->lang_id)
            ->first();

        // set categories
        if(! empty($object['categories_id']))
        {
            $product->categories()->sync($object['categories_id']);
        }

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

    public static function update($object)
    {
        ProductService::checkUpdate($object);
        Product::where('id', $object['id'])->update(ProductService::builder($object, ['sku', 'field_group_id', 'type_id', 'parent_id', 'weight', 'active', 'sort', 'price_type_id', 'subtotal', 'product_class_tax_id', 'data_lang']));

        // set custom fields
        if(! empty($object['field_group_id']))
        {
            $data = [];
            $data['customFields'] = $object['customFields'];
            $object['data'] = json_encode($data);
        }

        // update product lang
        ProductLang::where('market_product_lang.id', $object['id'])
            ->where('market_product_lang.lang_id', $object['lang_id'])
            ->update(ProductService::builder($object, ['name', 'slug', 'description', 'data']));

        // get product instance
        $product = Product::builder()
            ->where('market_product.id', $object['id'])
            ->where('market_product_lang.lang_id', $object['lang_id'])
            ->first();

        // set categories
        if(! empty($object['categories_id']))
        {
            $product->categories()->sync($object['categories_id']);
        }

        // set attachments
        if(is_array($object['attachments']))
        {
            // first save libraries to get id
            $attachments = AttachmentService::storeAttachmentsLibrary($object['attachments']);

            // then save attachments
            AttachmentService::updateAttachments($attachments, 'storage/app/public/market/products', 'storage/market/products', Product::class, $product->id,  $product->lang_id);
        }

        return $product;
    }

    private static function builder($object, $filterKeys = null)
    {
        $object = collect($object);
        if($filterKeys) return $object->only($filterKeys)->toArray();

        return  $object->only('id', 'lang_id', 'name', 'slug', 'description', 'sku', 'field_group_id', 'type_id', 'parent_id', 'weight', 'active', 'sort', 'price_type_id', 'subtotal', 'product_class_tax_id', 'data_lang', 'data')->toArray();
    }

    private static function checkCreate($object)
    {
        if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to create a category');
        if(empty($object['name']))      throw new \Exception('You have to define a name field to create a category');
        if(empty($object['slug']))      throw new \Exception('You have to define a slug field to create a category');
    }

    private static function checkUpdate($object)
    {
        if(empty($object['id']))        throw new \Exception('You have to define a id field to update a product');
        if(empty($object['lang_id']))   throw new \Exception('You have to define a lang_id field to update a product');
    }
}