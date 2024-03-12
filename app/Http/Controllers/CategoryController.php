<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CharacterizedProducts;
use App\Models\Products;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $category = Category::where('step', 0)->orderBy('created_at', 'desc')->get();
        return view('category.index', ['categories'=> $category]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $model = new Category();
        $model->name = $request->name;
        $model->parent_id = 0;
        $model->step = 0;
        $model->save();
        return redirect()->route('category.index')->with('status', __('Successfully created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $model = Category::where('step', 0)->find($id);
        return view('category.show', ['model'=>$model]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::where('step', 0)->find($id);
        return view('category.edit', ['category'=> $category]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Category::where('step', 0)->find($id);
        $model->name = $request->name;
        $model->step = 0;
        $model->save();
        return redirect()->route('category.index')->with('status', __('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Category::where('step', 0)->find($id);
        $model->delete();
        return redirect()->route('category.index')->with('status', __('Successfully deleted'));
    }

    //Json api

    public function getCategories(){
        $categories = Category::where('step', 0)->get();
        foreach ($categories as $category){
            $subcategories = $category->subcategory;
            $sub_sub_category = [];
            $sub_category = [];
            foreach ($subcategories as $subcategory){
                $sub_category[]=[
                    'id'=> $subcategory->id,
                    'name'=> $subcategory->name,
                    'sub_sub_category'=>$subcategory->subsubcategory??[]
                ];
            }
            $data_category[] = [
              'id'=> $category->id,
              'name'=> $category->name,
              'sub_category'=>$sub_category
            ];
        }
        return response()->json($data_category, 200);
    }

    public function getProductsByCategory(Request $request)
    {
        $category = Category::find($request->category_id);
        $data = [];
        $products_data = [];
        $category_ = [];
        $subCategory = [];
        $subSubCategory = [];
        $productId = [];

        if ($category) {
            if ($category->step == 0) {
                $category_ = [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
                $subCategory = [];
                $subSubCategory = [];
            } elseif ($category->step == 1) {
                $category_ = [
                    'id' => $category->category->id,
                    'name' => $category->category->name,
                ];

                $subCategory = [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
                $subSubCategory = [];
            }elseif($category->step == 2) {
                $category_ = [
                    'id' => $category->sub_category->category->id,
                    'name' => $category->sub_category->category->name,
                ];

                $subCategory = [
                    'id' => $category->sub_category->id,
                    'name' => $category->sub_category->name,
                ];

                $subSubCategory = [
                    'id' => $category->id,
                    'name' => $category->name,
                ];
            }

            $products = Products::select('id', 'name', 'category_id', 'images',  'sum', 'description')->with('discount')->where('category_id', $category->id)->get();
        } else {
            $subCategory = [];
            $products = [];
            $category_= [];
        }
        foreach ($products as $product) {
            $images_array = [];
            if (!is_array($product->images)) {
                $images = json_decode($product->images);
            }
            foreach ($images as $image) {
                if (!$image) {
                    $product_image = 'no';
                } else {
                    $product_image = $image;
                }

                $avatar_main = storage_path('app/public/products/' . $product_image);
                if (file_exists($avatar_main)) {
                    $images_array[] = asset('storage/products/' . $image);
                }
            }

            $productId[] = $product->id;
            $products_data[] = [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'images' => $images_array,
                'description' => $product->description,
                'price' => $product->sum,
                'discount' => (isset($product->discount)) > 0 ? $product->discount->percent : NULL,
                'price_discount' => (isset($product->discount)) > 0 ? $product->price - ($product->price / 100 * $product->discount->percent) : NULL,
            ];
        }

        $data[] = [
            'category' => $category_,
            'sub_category' => $subCategory,
            'sub_sub_category' => $subSubCategory,
            'products' => $products_data,
        ];

        $message = 'Success';
        return $this->success($message, 200, $data);
    }

    public function getImages($model, $text)
    {
        if ($model->images) {
            $images_ = json_decode($model->images);
            $images = [];
            foreach ($images_ as $image_) {
                if ($text == 'warehouse') {
                    $images[] = asset('storage/warehouses/'.$image_);
                } elseif ($text == 'product') {
                    $images[] = asset('storage/products/'.$image_);
                }
            }
        } else {
            $images = [];
        }

        return $images;
    }
}
