<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CategoryTranslations;
use App\Models\Language;
use App\Models\Products;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public $current_page = 'category';
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $getCommonData = $this->getCommonData();
        $category = Category::where('step', 0)->orderBy('created_at', 'desc')->get();
        return view('category.index', array_merge(['categories'=> $category, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $getCommonData = $this->getCommonData();
        return view('category.create', array_merge($getCommonData, ['current_page'=>$this->current_page]));
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
        $model->parent_id = null;
        $model->step = 0;
        $model->save();
        $languages = Language::get();
        foreach ($languages as $language) {
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
        $getCommonData = $this->getCommonData();
        $model = Category::where('step', 0)->find($id);
        return view('category.show', array_merge(['model'=>$model, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $getCommonData = $this->getCommonData();
        $category = Category::where('step', 0)->find($id);
        return view('category.edit', array_merge(['category'=> $category, 'current_page'=>$this->current_page], $getCommonData));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $model = Category::where('step', 0)->find($id);
        if($request->name != $model->name){
            $languages = Language::get();
            foreach ($languages as $language) {
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
        $languages = Language::get();
        foreach ($languages as $language) {
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
        $language = $request->header('language')??'en';
        $categories = Category::with([
            'getTranslatedModel' => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            'subcategory',
            'subcategory.getTranslatedModel' => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            'subcategory.subsubcategory',
            'subcategory.subsubcategory.getTranslatedModel' => function ($query) use ($language) {
                $query->where('lang', $language);
            },
        ])->where('step', 0)->get()->map(function($category){
            $translate_category_name = optional($category->getTranslatedModel)->name??'';
            $translate_category_en_name = $category->name??'';
            $sub_category = $category->subcategory->map(function($subcategory){
                $translate_sub_category_name = optional($subcategory->getTranslatedModel)->name??'';
                $sub_sub_category = $subcategory->subsubcategory->map(function($subsubcategory){
                    return [
                        'id'=>$subsubcategory->id,
                        'name'=>optional($subsubcategory->getTranslatedModel)->name??'',
                    ];
                });
                return [
                    'id'=> $subcategory->id,
                    'name'=> $translate_sub_category_name??'',
                    'sub_sub_category'=>$sub_sub_category
                ];
            });
            return [
              'id'=> $category->id,
              'name'=> $translate_category_name??'',
              'en_name'=> $translate_category_en_name??'',
              'sub_category'=>$sub_category
            ];
        });
        return response()->json($categories, 200);
    }

    public function getProductsByCategory(Request $request)
    {
        $language = $request->header('language')??'en';
        $category = Category::with([
            'category',
            'sub_category',
            'sub_category.category',
            'category.getTranslatedModel' => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            'sub_category.getTranslatedModel' => function ($query) use ($language) {
                $query->where('lang', $language);
            },
            'sub_category.category.getTranslatedModel' => function ($query) use ($language) {
                $query->where('lang', $language);
            }
        ])->find($request->category_id);
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
                    'name' => optional($category->getTranslatedModel)->name??'',
                ];
                $subCategory = [];
                $subSubCategory = [];
            } elseif ($category->step == 1) {
                $category_ = [
                    'id' => $category->category->id,
                    'name' => optional(optional($category->category)->getTranslatedModel)->name,
                ];

                $subCategory = [
                    'id' => $category->id,
                    'name' => optional($category->getTranslatedModel)->name,
                ];
                $subSubCategory = [];
            }elseif($category->step == 2) {
                $category_ = [
                    'id' => optional(optional($category->sub_category)->category)->id,
                    'name' => optional(optional(optional($category->sub_category)->category)->getTranslatedModel)->name,
                ];

                $subCategory = [
                    'id' => $category->sub_category->id,
                    'name' => optional(optional($category->sub_category)->getTranslatedModel)->name,
                ];

                $subSubCategory = [
                    'id' => $category->id,
                    'name' => optional($category->getTranslatedModel)->name,
                ];
            }

            $products = Products::with([
                'discount',
                'getTranslatedModel' => function ($query) use ($language) {
                    $query->where('lang', $language);
                },
                'getTranslatedDescriptionModel' => function ($query) use ($language) {
                    $query->where('lang', $language);
                },
            ])->select('id', 'name', 'category_id', 'images',  'sum', 'description')->where('category_id', $category->id)->get();
        } else {
            $subCategory = [];
            $products = collect();
            $category_= [];
        }
        $products_data = $products->map(function($product){
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
            return [
                'id' => $product->id,
                'name' => optional($product->getTranslatedModel)->name,
                'category_id' => $product->category_id,
                'images' => $images_array,
                'description' => optional($product->getTranslatedDescriptionModel)->description??'',
                'price' => $product->sum,
                'discount' => optional($product->discount)->percent ?? NULL,
                'price_discount' => $product->discount? (int)$product->price - ((int)$product->price / 100 * (int)$product->discount->percent) : NULL,
            ];
        });

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
