<?php namespace Syscover\Market\Controllers;

use Illuminate\Http\Request;
use Syscover\Core\Controllers\CoreController;
use Syscover\Market\Models\TaxRule;
use Syscover\Market\Models\Product;
use Syscover\Market\Models\ProductLang;
use Syscover\Market\Models\ProductClassTax;
use Syscover\Market\Models\Category;

/**
 * Class ProductController
 * @package Syscover\Market\Controllers
 */

class ProductController extends CoreController
{
    protected $model = Product::class;

    public function apiCheckSlug()
    {
        return response()->json([
            'status'    => 'success',
            'slug'      => ProductLang::checkSlug('slug', $this->request->input('slug'), $this->request->input('id'))
        ]);
    }
}