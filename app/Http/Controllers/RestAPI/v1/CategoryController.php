<?php

namespace App\Http\Controllers\RestAPI\v1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Utils\CategoryManager;
use App\Utils\Helpers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function get_categories(Request $request): JsonResponse
    {
        $categoriesID = [];
        if ($request->has('seller_id') && $request['seller_id'] != null) {
            // Finding category ids
            $categoriesID = Product::active()
                ->when($request->has('seller_id') && $request['seller_id'] != null && $request['seller_id'] != 0, function ($query) use ($request) {
                    return $query->where(['added_by' => 'seller'])
                        ->where('user_id', $request['seller_id']);
                })->when($request->has('seller_id') && $request['seller_id'] != null && $request['seller_id'] == 0, function ($query) use ($request) {
                    return $query->where(['added_by' => 'admin',
                    ]);
                })->pluck('category_id');
        }

        $categories = Category::when($request->has('seller_id') && $request['seller_id'] != null, function ($query) use ($categoriesID) {
                $query->whereIn('id', $categoriesID);
            })
            ->with(['product' => function ($query) {
                return $query->active()->withCount(['orderDetails']);
            }])
            ->withCount(['product' => function ($query) use ($request) {
                $query->when($request->has('seller_id') && !empty($request['seller_id']), function ($query) use ($request) {
                    $query->where(['added_by' => 'seller', 'user_id' => $request['seller_id'], 'status' => '1']);
                });
            }])->with(['childes' => function ($query) {
                $query->with(['childes' => function ($query) {
                    $query->withCount(['subSubCategoryProduct'])->where('position', 2);
                }])->withCount(['subCategoryProduct'])->where('position', 1);
            }, 'childes.childes'])
            ->where(['position' => 0])->get();

        $categories = CategoryManager::getPriorityWiseCategorySortQuery(query: $categories);

        return response()->json($categories->values());
    }


    // public function get_medical_intruments_subcategories(Request $request): JsonResponse
    // {
    //     $categoriesID = [];
    //     if ($request->has('seller_id') && $request['seller_id'] != null) {
    //         // Finding category ids
    //         $categoriesID = Product::active()
    //             ->when($request->has('seller_id') && $request['seller_id'] != null && $request['seller_id'] != 0, function ($query) use ($request) {
    //                 return $query->where(['added_by' => 'seller'])
    //                     ->where('user_id', $request['seller_id']);
    //             })->when($request->has('seller_id') && $request['seller_id'] != null && $request['seller_id'] == 0, function ($query) use ($request) {
    //                 return $query->where(['added_by' => 'admin']);
    //             })->pluck('category_id');
    //     }

    //     $categories = Category::when($request->has('seller_id') && $request['seller_id'] != null, function ($query) use ($categoriesID) {
    //             $query->whereIn('id', $categoriesID);
    //         })
    //         ->where('name', 'LIKE', '%medical instrument%') // Filter by category name
    //         ->with(['product' => function ($query) {
    //             return $query->active()->withCount(['orderDetails']);
    //         }])
    //         ->withCount(['product' => function ($query) use ($request) {
    //             $query->when($request->has('seller_id') && !empty($request['seller_id']), function ($query) use ($request) {
    //                 $query->where(['added_by' => 'seller', 'user_id' => $request['seller_id'], 'status' => '1']);
    //             });
    //         }])->with(['childes' => function ($query) {
    //             $query->with(['childes' => function ($query) {
    //                 $query->withCount(['subSubCategoryProduct'])->where('position', 2);
    //             }])->withCount(['subCategoryProduct'])->where('position', 1);
    //         }, 'childes.childes'])
    //         ->where(['position' => 0])->get();

    //     $categories = CategoryManager::getPriorityWiseCategorySortQuery(query: $categories);

    //     return response()->json($categories->values());
    // }


    public function get_medical_intruments_subcategories(Request $request): JsonResponse
    {
        $categoriesID = [];
        if ($request->has('seller_id') && $request['seller_id'] != null) {
            // Finding category IDs
            $categoriesID = Product::active()
                ->when($request->has('seller_id') && $request['seller_id'] != null && $request['seller_id'] != 0, function ($query) use ($request) {
                    return $query->where(['added_by' => 'seller'])
                        ->where('user_id', $request['seller_id']);
                })->when($request->has('seller_id') && $request['seller_id'] != null && $request['seller_id'] == 0, function ($query) use ($request) {
                    return $query->where(['added_by' => 'admin']);
                })->pluck('category_id');
        }
    
        $categories = Category::when($request->has('seller_id') && $request['seller_id'] != null, function ($query) use ($categoriesID) {
                $query->whereIn('id', $categoriesID);
            })
            ->where('name', 'LIKE', '%medical instrument%') // Filter by category name
            ->with(['product' => function ($query) {
                return $query->active()->withCount(['orderDetails']);
            }])
            ->withCount(['product' => function ($query) use ($request) {
                $query->when($request->has('seller_id') && !empty($request['seller_id']), function ($query) use ($request) {
                    $query->where(['added_by' => 'seller', 'user_id' => $request['seller_id'], 'status' => '1']);
                });
            }])
            ->with(['childes' => function ($query) {
                $query->with(['childes' => function ($query) {
                    $query->withCount(['subSubCategoryProduct'])->where('position', 2);
                }])->withCount(['subCategoryProduct'])->where('position', 1);
            }, 'childes.childes'])
            ->where(['position' => 0])->get();
    
        // Group products under subcategories
        $categories = $categories->map(function ($category) {
            $category->childes = $category->childes->map(function ($child) use ($category) {
                // Filter products by sub_category_id and assign to subcategory
                $child->products = $category->product->filter(function ($product) use ($child) {
                    return $product->sub_category_id == $child->id;
                })->values();
    
                // Recursively assign products for nested childes
                $child->childes = $child->childes->map(function ($grandChild) use ($child) {
                    $grandChild->products = $child->products->filter(function ($product) use ($grandChild) {
                        return $product->sub_category_id == $grandChild->id;
                    })->values();
                    return $grandChild;
                });
    
                return $child;
            });
    
            // Remove main products from the parent category (optional)
            unset($category->product);
    
            return $category;
        });
    
        $categories = CategoryManager::getPriorityWiseCategorySortQuery(query: $categories);
    
        return response()->json($categories->values());
    }
    



    public function get_products(Request $request, $id): JsonResponse
    {
        return response()->json(Helpers::product_data_formatting(CategoryManager::products($id, $request), true), 200);
    }

//     public function get_products(Request $request, $category_id): JsonResponse
// {
//     // Fetch products based on the category ID
//     $products = Product::where('category_id', $category_id)->get();

//     // Return response as JSON
//     return response()->json([
//         'success' => true,
//         'data' => $products
//     ]);
// }

    public function find_what_you_need()
    {
        $find_what_you_need_categories = Category::where('parent_id', 0)
            ->with(['childes' => function ($query) {
                $query->withCount(['subCategoryProduct' => function ($query) {
                    return $query->active();
                }]);
            }])
            ->withCount(['product' => function ($query) {
                return $query->active();
            }])
            ->get()->toArray();

        $get_categories = [];
        foreach($find_what_you_need_categories as $category){
            $slice = array_slice($category['childes'], 0, 4);
            $category['childes'] = $slice;
            $get_categories[] = $category;
        }

        $final_category = [];
        foreach ($get_categories as $category) {
            if (count($category['childes']) > 0) {
                $final_category[] = $category;
            }
        }

        return response()->json(['find_what_you_need'=>$final_category], 200);
    }

    public function get_products_by_category(Request $request)
    {
        $categoryName = $request->query('category');
        if (!$categoryName) {
            return response()->json(['error' => 'Category parameter is missing'], 400);
        }
        $category = Category::where('slug', $categoryName)->first();
        if (!$category) {
            return response()->json(['error' => 'Category not found'], 404);
        }
        $products = $category->product;
        return response()->json($products);
    }


    public function get_products_by_sub_category(Request $request)
    {
        $categoryName = $request->query('sub-category');
        if (!$categoryName) {
            return response()->json(['error' => 'Category parameter is missing'], 400);
        }

        $sub_category = Category::where('slug', $categoryName)->first();
        if (!$sub_category) {
            return response()->json(['error' => 'Sub-Category not found'], 404);
        }
        $products = $sub_category->subCategoryProduct;
        return response()->json($products);
    }


    public function get_all_categories(){
        $categories = Category::where('parent_id', 0)->get();
        return response()->json($categories, 200);
    }

}
