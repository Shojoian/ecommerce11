<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Services\CurrencyService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request, CurrencyService $currency)
    {
        $query = Product::where('is_active', true)->with('category');

        /*
        |--------------------------------------------------------------------------
        | SEARCH
        |--------------------------------------------------------------------------
        */
        if ($search = $request->get('q')) {
            $query->where('name', 'like', "%{$search}%");
        }

        /*
        |--------------------------------------------------------------------------
        | CATEGORY FILTER
        |--------------------------------------------------------------------------
        */
        if ($category = $request->get('category')) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        /*
        |--------------------------------------------------------------------------
        | PRICE RANGE FILTER
        |--------------------------------------------------------------------------
        */
        if ($min = $request->get('min_price')) {
            $query->where('price', '>=', $min);
        }

        if ($max = $request->get('max_price')) {
            $query->where('price', '<=', $max);
        }

        /*
        |--------------------------------------------------------------------------
        | SORTING OPTIONS
        |--------------------------------------------------------------------------
        */
        switch ($request->get('sort')) {
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;

            case 'price_low_high':
                $query->orderBy('price', 'asc');
                break;

            case 'price_high_low':
                $query->orderBy('price', 'desc');
                break;

            case 'popular':
                // Add a "views" column if you want
                $query->orderBy('views', 'desc');
                break;

            default:
                // Leave unsorted
                break;
        }

        /*
        |--------------------------------------------------------------------------
        | PAGINATE RESULTS
        |--------------------------------------------------------------------------
        */
        $products = $query->paginate(12);
        $categories = Category::all();

        /*
        |--------------------------------------------------------------------------
        | CURRENCY CONVERSION
        |--------------------------------------------------------------------------
        */
        $selectedCurrency = $request->get('currency', config('app.currency'));
        $supportedCurrencies = $currency->getSupportedCurrencies();

        // Convert prices for display
        $products->getCollection()->transform(function ($product) use ($currency, $selectedCurrency) {
            $product->display_price = $currency->convert($product->price, $selectedCurrency);
            $product->display_currency = $selectedCurrency;
            return $product;
        });

        return view('storefront.products.index', compact(
            'products',
            'categories',
            'selectedCurrency',
            'supportedCurrencies'
        ));
    }

    public function show(string $slug, CurrencyService $currency, Request $request)
    {
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $selectedCurrency = $request->get('currency', config('app.currency'));

        $product->display_price = $currency->convert($product->price, $selectedCurrency);
        $product->display_currency = $selectedCurrency;

        return view('storefront.products.show', compact('product', 'selectedCurrency'));
    }
}
