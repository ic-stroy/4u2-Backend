<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryTranslations;
use App\Models\CharacterizedProducts;
use App\Models\Language;
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
        $last_category = Category::withTrashed()->select('id')->orderBy('id', 'desc')->first();
        if($last_category){
            $model->id = (int)$last_category->id + 1;
        }
        $model->name = $request->name;
        $model->parent_id = 0;
        $model->step = 0;
        $model->save();

        foreach (Language::all() as $language) {
            $category_translations = CategoryTranslations::firstOrNew(['lang' => $language->code, 'category_id' => $model->id]);
            $category_translations->lang = $language->code;
            $category_translations->name = $model->name;
            $category_translations->category_id = $model->id;
            $category_translations->save();
        }
        return redirect()->route('category.index')->with('status', translate('Successfully created'));
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
        if($request->name != $model->name){
            foreach (Language::all() as $language) {
                $category_translations = CategoryTranslations::firstOrNew(['lang' => $language->code, 'category_id' => $model->id]);
                $category_translations->lang = $language->code;
                $category_translations->name = $request->name;
                $category_translations->category_id = $model->id;
                $category_translations->save();
            }
        }
        $model->name = $request->name;
        $model->step = 0;
        $model->save();
        return redirect()->route('category.index')->with('status', translate('Successfully updated'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $model = Category::where('step', 0)->find($id);
        if(!$model->subcategory->isEmpty()){
            if(!$model->subcategory->isEmpty()){
                return redirect()->back()->with('error', translate('You cannot delete this category because it has subcategories'));
            }
        }
        if($model->product){
            return redirect()->back()->with('error', translate('You cannot delete this category because it has products'));
        }
        foreach (Language::all() as $language) {
            $categories_translations = CategoryTranslations::where(['lang' => $language->code, 'category_id' => $model->id])->get();
            foreach ($categories_translations as $category_translation){
                $category_translation->delete();
            }
        }
        $model->delete();
        return redirect()->route('category.index')->with('status', translate('Successfully deleted'));
    }

    //Json api

    public function getCategories(Request $request){
        $language = $request->header('language');
        $categories = Category::where('step', 0)->get();
        foreach ($categories as $category){
            $translate_category_name = table_translate($category, 'category', $language);
            $translate_category_en_name = table_translate($category, 'category', 'en');
            $subcategories = $category->subcategory;
            $sub_category = [];
            foreach ($subcategories as $subcategory){
                $sub_sub_category = [];
                $translate_sub_category_name = table_translate($subcategory, 'category', $language);
                foreach($subcategory->subsubcategory as $subsubcategory){
                    $sub_sub_category[] = [
                        'id'=>$subsubcategory->id,
                        'name'=>table_translate($subsubcategory, 'category', $language),
                    ];
                }

                $sub_category[]=[
                    'id'=> $subcategory->id,
                    'name'=> $translate_sub_category_name??'',
                    'sub_sub_category'=>$sub_sub_category
                ];
            }
            $data_category[] = [
              'id'=> $category->id,
              'name'=> $translate_category_name??'',
              'en_name'=> $translate_category_en_name??'',
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
                'discount' => $product->discount ? $product->discount->percent : NULL,
                'price_discount' => $product->discount? $product->price - ($product->price / 100 * $product->discount->percent) : NULL,
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
