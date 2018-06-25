<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Hideyo\Ecommerce\Framework\Models\ProductCategory;

class ProductCategoryController extends Controller
{
	public function index() {
		return ProductCategory::all();
	}

	public function show(ProductCategory $category) {
		return $category;
	}

	public function products(ProductCategory $category) {
		return $category->products;
	}
}