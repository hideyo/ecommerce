<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use Hideyo\Ecommerce\Framework\Models\Product;

class ProductController extends Controller
{
	public function index() {
		return Product::all();
	}

	public function show(Product $product) {
		return $product;
	}
}