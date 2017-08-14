<?php namespace Syscover\Market\GraphQL\Mutations;

use GraphQL;
use GraphQL\Type\Definition\Type;
use Folklore\GraphQL\Support\Mutation;
use Syscover\Admin\Services\AttachmentService;
use Syscover\Core\Services\SQLService;
use Syscover\Market\Models\ProductLang;
use Syscover\Market\Models\Tag;
use Syscover\Market\Models\Product;

class ProductMutation extends Mutation
{
    public function type()
    {
        return GraphQL::type('MarketProduct');
    }

    public function args()
    {
        return [
            'object' => [
                'name' => 'object',
                'type' => Type::nonNull(GraphQL::type('MarketProductInput'))
            ],
        ];
    }
}

class AddProductMutation extends ProductMutation
{
    protected $attributes = [
        'name' => 'addProduct',
        'description' => 'Add new product'
    ];

    public function resolve($root, $args)
    {
        // check if there is id
        if(empty($args['object']['id']))
        {
            // create new product
            $object = Product::create($args['object']);
            $args['object']['id'] = $object->id;
        }

        // get custom fields
        if(isset($args['object']['field_group_id'])) $args['object']['data']['customFields'] = $args['object']['customFields'];

        // create product lang
        ProductLang::create($args['object']);

        // update data_lang after create ProductLang because yu
        Product::where('market_product.id', $args['object']['lang_id'])->update([
            'data_lang' => json_encode(Product::addLangDataRecord($args['object']['lang_id'], $args['object']['id']))
        ]);

        // get object with builder, to get every relations
        $object = Product::builder()
            ->where('market_product.id', $args['object']['id'])
            ->where('market_product_lang.lang_id', $args['object']['lang_id'])
            ->first();

        // set categories
        $object->categories()->sync($args['object']['categories_id']);

        // set attachments
        if(is_array($args['object']['attachments']))
        {
            // first save libraries to get id
            $attachments = AttachmentService::storeAttachmentsLibrary($args['object']['attachments']);

            // then save attachments
            AttachmentService::storeAttachments($attachments, 'storage/app/public/market/products', 'storage/market/products', Product::class, $object->id,  $object->lang_id);
        }

        return $object;
    }
}

class UpdateProductMutation extends ProductMutation
{
    protected $attributes = [
        'name' => 'updateProduct',
        'description' => 'Update product'
    ];

    public function resolve($root, $args)
    {
        // update product
        Product::where('market_product.id', $args['object']['id'])->update([
            'code'                  => $args['object']['code'],
            'field_group_id'        => $args['object']['field_group_id'],
            'type_id'               => $args['object']['type_id'],
            'parent_id'             => $args['object']['parent_id'],
            'weight'                => $args['object']['weight'],
            'active'                => $args['object']['active'],
            'sort'                  => $args['object']['sort'],
            'price_type_id'         => $args['object']['price_type_id'],
            'subtotal'              => $args['object']['subtotal'],
            'product_class_tax_id'  => $args['object']['product_class_tax_id']
        ]);

        // get custom fields
        $data = [];
        if(isset($args['object']['field_group_id'])) $data['customFields'] = $args['object']['customFields'];

        // update product lang
        ProductLang::where('market_product_lang.id', $args['object']['id'])
            ->where('market_product_lang.lang_id', $args['object']['lang_id'])
            ->update([
                'name'          => $args['object']['name'],
                'slug'          => $args['object']['slug'],
                'description'   => $args['object']['description'],
                'data'          => json_encode($data)
            ]);

        $object = Product::builder()
            ->where('market_product.id', $args['object']['id'])
            ->where('market_product_lang.lang_id', $args['object']['lang_id'])
            ->first();

        // set categories
        $object->categories()->sync($args['object']['categories_id']);

        // set attachments
        if(is_array($args['object']['attachments']))
        {
            // first save libraries to get id
            $attachments = AttachmentService::storeAttachmentsLibrary($args['object']['attachments']);

            // then save attachments
            AttachmentService::updateAttachments($attachments, 'storage/app/public/market/products', 'storage/market/products', Product::class, $object->id,  $object->lang_id);
        }

        return $object;
    }
}

class DeleteProductMutation extends ProductMutation
{
    protected $attributes = [
        'name' => 'deleteProduct',
        'description' => 'Delete product'
    ];

    public function args()
    {
        return [
            'id' => [
                'name' => 'id',
                'type' => Type::nonNull(Type::string())
            ],
            'lang' => [
                'name' => 'lang',
                'type' => Type::nonNull(Type::string())
            ]
        ];
    }

    public function resolve($root, $args)
    {
        // destroy object
        $object = SQLService::destroyRecord($args['id'], Product::class, $args['lang']);

        // destroy attachments
        AttachmentService::deleteAttachments($args['id'], Product::class, $args['lang']);

        return $object;
    }
}
