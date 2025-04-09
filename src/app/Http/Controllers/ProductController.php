<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Season;
use App\Models\ProductSeason;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function getProducts()
    {
        $products = Product::all();
        $perPage = 6;
        $page = Paginator::resolveCurrentPage('page');
        $pageData = $products->slice(($page - 1) * $perPage, $perPage);
        $options = [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page'
        ];

        $products = new LengthAwarePaginator($pageData, $products->count(), $perPage, $page, $options);

        return view('list', compact('products'));
    }

    public function upload(ProductRequest $request)
    {
        $dir = 'images';
        $file_name = $request->file('product_image')->getClientOriginalName();
        $request->file('product_image')->storeAs('public/' . $dir, $file_name);

        $product_data = new Product();
        $product_data->name = $_POST["product_name"];
        $product_data->price = $_POST["product_price"];
        $product_data->image = 'storage/' . $dir . '/' . $file_name;
        $product_data->description = $_POST["product_description"];
        $product_data->save();


        $product_seasons = $request->input('product_season', []);
        $new_product_id = Product::count();

        foreach ($product_seasons as $product_season) {

            $product_season_data = new ProductSeason();
            $product_season_data->product_id = $new_product_id;
            $product_season_data->season_id = $product_season;
            $product_season_data->save();
        }

        $products = Product::all();
        $perPage = 6;
        $page = Paginator::resolveCurrentPage('page');
        $pageData = $products->slice(($page - 1) * $perPage, $perPage);
        $options = [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page'
        ];

        $products = new LengthAwarePaginator($pageData, $products->count(), $perPage, $page, $options);

        return redirect('/products');
    }

    public function getSearch(Request $request)
    {
        $query = Product::query();
        $sort = $request->input('sort');

        if ($sort == "高い順に表示") {

            $sort = "high_price";

        } elseif ($sort == "低い順に表示") {

            $sort = "low_price";

        } else {

            $sort = "";
        }

        if ($request->filled('keyword')) {

            $keyword = $request->input('keyword');
            $query->where('name', 'like', '%' . $keyword . '%');

        }

        $query_products = $query->get();

        if ($sort == "high_price") {

            $products = $query_products->sortByDesc('price');
            $sort = "高い順に表示";

        } elseif ($sort == "low_price") {

            $products = $query_products->sortBy('price');
            $sort = "低い順に表示";


        } else {

            $products = $query_products;
            $sort = "";

        }

        $perPage = 6;
        $page = Paginator::resolveCurrentPage('page');
        $pageData = $products->slice(($page - 1) * $perPage, $perPage);
        $options = [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page'
        ];

        $products = new LengthAwarePaginator($pageData, $products->count(), $perPage, $page, $options);

        $seasons = Season::all();


        return view('list')->with(compact('sort', 'products', 'seasons'));
    }

    public function postSearch(Request $request)
    {
        $query = Product::query();
        $sort = $request->input('sort');

        if ($request->filled('keyword')) {

            $keyword = $request->input('keyword');
            $query->where('name', 'like', '%' . $keyword . '%');

        }

        $query_products = $query->get();

        if ($sort == "high_price") {

            $products = $query_products->sortByDesc('price');
            $sort = "高い順に表示";

        } elseif ($sort == "low_price") {

            $products = $query_products->sortBy('price');
            $sort = "低い順に表示";


        } else {

            $products = $query_products;
            $sort = "";

        }

        $perPage = 6;
        $page = Paginator::resolveCurrentPage('page');
        $pageData = $products->slice(($page - 1) * $perPage, $perPage);
        $options = [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => 'page'
        ];

        $products = new LengthAwarePaginator($pageData, $products->count(), $perPage, $page, $options);

        $products->appends(['sort' => $sort]);

        $seasons = Season::all();

        return view('list')->with(compact('sort', 'products', 'seasons'));
    }

    public function postDelete($product_id)
    {
        $product = Product::find($product_id);
        $product->delete();
        $message = "製品の削除が完了しました。";
        $products = Product::paginate(6);

        return redirect('/products')->with(compact('products', 'message'));
    }

    public function getDetail($product_id)
    {
        $product = Product::with('seasons')->find($product_id);
        $seasons = Season::all();

        return view('detail', compact('product', 'seasons'));
    }

    public function postUpdate(ProductRequest $request)
    {
        $product_id = $request->input('product_id'); // ① フォームからIDを受け取る
        $product = Product::find($product_id); // ② 既存の商品を取得

        if ($request->hasFile('product_image')) {
            $dir = 'images';
            $file_name = $request->file('product_image')->getClientOriginalName();
            $request->file('product_image')->storeAs('public/' . $dir, $file_name);
            $product->image = 'storage/' . $dir . '/' . $file_name;
        }

        $product->name = $request->input("product_name");
        $product->price = $request->input("product_price");
        $product->description = $request->input("product_description");
        $product->save();

        // 中間テーブル（product_season）の更新
        $product->seasons()->sync($request->input('product_season', []));

        return redirect('/products');
    }

}