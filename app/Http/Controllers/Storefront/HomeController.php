<?php

namespace App\Http\Controllers\Storefront;

use View;
use Carbon\Carbon;
use App\Page;
use App\Shop;
use App\Banner;
use App\Slider;
use App\Product;
use App\Country;
use App\Category;
use App\Inventory;
use App\Manufacturer;
use App\CategoryGroup;
use App\CategorySubGroup;
use App\Helpers\ListHelper;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        //Start Front-end
        $sliders = Slider::with('featureImage:path,imageable_id,imageable_type','mobileImage:path,imageable_id,imageable_type')
        ->orderBy('order', 'asc')->get()->toArray();
        $banners = Banner::with('featureImage:path,imageable_id,imageable_type')
        ->orderBy('order', 'asc')->get()->groupBy('group_id')->toArray();

        //Trending categories
        if ($trending_categories = get_from_option_table('trending_categories', [])){
            $trending_categories = Category::whereIn('id', $trending_categories)
            ->with(['listings' => function($q){
                return $q->available()->get()->take(config('system.popular.take.trending', 20));
            }])->get();
        }

        //Featured Category Load With Images
        $featured_category = Category::featured()->get();

        //Brands
        $featured_brands = ListHelper::get_featured_brands();

        //Deal of the day;
        $deal_of_the_day = get_deal_of_the_day();

        //$featured = ListHelper::featured_categories()
        $daily_popular = ListHelper::popular_items(config('system.popular.period.daily', 1), config('system.popular.take.daily', 5));
        $weekly_popular = ListHelper::popular_items(config('system.popular.period.weekly', 7), config('system.popular.take.weekly', 5));
        $monthly_popular = ListHelper::popular_items(config('system.popular.period.monthly', 30), config('system.popular.take.monthly', 5));

        //Recently Added Items
        $recent = ListHelper::latest_available_items(10);

        //additional Items
        $additional_items = ListHelper::random_items(10);

        //Bundle Offer:
        // $bundle_offer = ListHelper::random_items(18);

        //best deal under some amount:
        $deals_under = ListHelper::best_find_under(get_from_option_table('best_finds_under',99));

        $flashdeals = is_incevio_package_loaded('flashdeal') ? get_flash_deals() : Null;

        //best Selling now:
        // $best_selling = ListHelper::random_items(18);

        // For legacy theme support. Will be removed in future
        if(active_theme() == 'legacy'){
            $trending = ListHelper::popular_items(config('system.popular.period.trending', 2), config('system.popular.take.trending', 15));
            View::share('trending', $trending);
        }

        return view('theme::index', compact(
            'banners', 'sliders', 'daily_popular','weekly_popular', 'monthly_popular',
            'recent', 'additional_items', 'trending_categories', 'flashdeals',
            'deal_of_the_day', 'deals_under', 'featured_category', 'featured_brands'
        ));
    }

    /**
     * Browse category based products
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function browseCategory(Request $request, $slug, $sortby = Null)
    {
        $category = Category::where('slug', $slug)->with(['subGroup' => function($q){
            $q->select(['id','slug','name','category_group_id'])->active();
        }, 'subGroup.group' => function($q){
            $q->select(['id','slug','name'])->active();
        }])
        ->active()->firstOrFail();

        // Take only available items
        $all_products = $category->listings()->available()->filter($request->all());

        // Parameter for filter options
         $brands = ListHelper::get_unique_brand_names_from_linstings($all_products);
         $priceRange = ListHelper::get_price_ranges_from_linstings($all_products);

        // Filter results
        $products = $all_products->withCount(['feedbacks', 'orders' => function($query){
            $query->where('order_items.created_at', '>=', Carbon::now()->subHours(config('system.popular.hot_item.period', 24)));
        }])
        ->with(['feedbacks:rating,feedbackable_id,feedbackable_type', 'images:path,imageable_id,imageable_type'])
        ->paginate(config('system.view_listing_per_page', 16))->appends($request->except('page'));

        return view('theme::category', compact('category', 'products', 'brands', 'priceRange'));
    }

    /**
     * Browse listings by category sub group
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function browseCategorySubGrp(Request $request, $slug, $sortby = Null)
    {
        $categorySubGroup = CategorySubGroup::where('slug', $slug)->with(['categories' => function($q){
            $q->select(['id','slug','category_sub_group_id','name'])->whereHas('listings')->active();
        }])->active()->firstOrFail();

        $all_products = prepareFilteredListings($request, $categorySubGroup);

        // Get brands ans price ranges
        $brands = ListHelper::get_unique_brand_names_from_linstings($all_products);
        $priceRange = ListHelper::get_price_ranges_from_linstings($all_products);

        // Paginate the results
        $products = $all_products->paginate(config('system.view_listing_per_page', 16))->appends($request->except('page'));

        return view('theme::category_sub_group', compact('categorySubGroup', 'products', 'brands', 'priceRange'));
    }

    /**
     * Browse listings by category group
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function browseCategoryGroup(Request $request, $slug, $sortby = Null)
    {
        $categoryGroup = CategoryGroup::where('slug', $slug)->with(['subGroups' => function($query){
            $query->has('categories')->active();
        }, 'subGroups.categories' => function($q){
            $q->select(['categories.id','categories.slug','categories.category_sub_group_id','categories.name'])
            ->where('categories.active', 1)->whereHas('listings')->withCount('listings');
        }])->active()->firstOrFail();

        $all_products = prepareFilteredListings($request, $categoryGroup);

        // Get brands ans price ranges
        $brands = ListHelper::get_unique_brand_names_from_linstings($all_products);
        $priceRange = ListHelper::get_price_ranges_from_linstings($all_products);

        // Paginate the results
        $products = $all_products->paginate(config('system.view_listing_per_page', 16))->appends($request->except('page'));

        return view('theme::category_group', compact('categoryGroup', 'products', 'brands', 'priceRange'));
    }

    /**
     * Open product page
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function product($slug)
    {
        $item = Inventory::where('slug', $slug)->available()->withCount('feedbacks')->first();

        if (! $item) {
            return view('theme::exceptions.item_not_available');
        }

        $item->load(['product' => function($q) use ($item){
                $q->select('id', 'brand', 'model_number', 'mpn', 'gtin', 'gtin_type', 'origin_country', 'slug', 'description', 'manufacturer_id', 'sale_count', 'created_at')
                ->withCount(['inventories' => function($query) use($item){
                    $query->where('shop_id', '!=', $item->shop_id)->available();
                }]);
            }, 'attributeValues' => function($q){
                $q->select('id', 'attribute_values.attribute_id', 'value', 'color', 'order')->with('attribute:id,name,attribute_type_id,order');
            }, 'shop' => function($q){
                $q->withCount('feedbacks','inventories');
            },
            'feedbacks.customer:id,nice_name,name',
            'images:id,path,imageable_id,imageable_type',
            'tags:id,name',
        ]);

        $this->update_recently_viewed_items($item); //update_recently_viewed_items

        $variants = ListHelper::variants_of_product($item, $item->shop_id);

        $attr_pivots = \DB::table('attribute_inventory')->select('attribute_id','inventory_id','attribute_value_id')
        ->whereIn('inventory_id', $variants->pluck('id'))->get();

        $item_attrs = $attr_pivots->where('inventory_id', $item->id)->pluck('attribute_value_id')->toArray();

        $attributes = \App\Attribute::select('id','name','attribute_type_id','order')
        ->whereIn('id', $attr_pivots->pluck('attribute_id'))
        ->with(['attributeValues' => function($query) use ($attr_pivots) {
            $query->whereIn('id', $attr_pivots->pluck('attribute_value_id'))->orderBy('order');
        }])->orderBy('order')->get();

        // TEST
        $related = ListHelper::related_products($item);
        $linked_items = ListHelper::linked_items($item);

        if(! $linked_items->count()) {
            $linked_items = $related->random($related->count() >= 3 ? 3 : $related->count());
        }

        // Country list for ship_to dropdown
        $business_areas = Country::select('id', 'name', 'iso_code')->orderBy('name', 'asc')->get();

        return view('theme::product', compact('item', 'variants', 'attributes', 'item_attrs', 'related', 'linked_items', 'business_areas'));
    }

    /**
     * Open product quick review modal
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function quickViewItem($slug)
    {
        $item = Inventory::where('slug', $slug)->available()
        ->with([
            'images:path,imageable_id,imageable_type',
            'product' => function($q){
                $q->select('id', 'slug')
                ->withCount(['inventories' => function($query){
                    $query->available();
                }]);
            }
        ])
        ->withCount('feedbacks')->firstOrFail();

        $this->update_recently_viewed_items($item); //update_recently_viewed_items

        $variants = ListHelper::variants_of_product($item, $item->shop_id);

        $attr_pivots = \DB::table('attribute_inventory')->select('attribute_id','inventory_id','attribute_value_id')
        ->whereIn('inventory_id', $variants->pluck('id'))->get();

        $attributes = \App\Attribute::select('id','name','attribute_type_id','order')
        ->whereIn('id', $attr_pivots->pluck('attribute_id'))
        ->with(['attributeValues' => function($query) use ($attr_pivots) {
            $query->whereIn('id', $attr_pivots->pluck('attribute_value_id'))->orderBy('order');
        }])->orderBy('order')->get();

        return view('theme::modals.quickview', compact('item','attributes'))->render();
    }

    /**
     * Open shop page
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function offers($slug)
    {
        $product = Product::where('slug', $slug)->with(['inventories' => function($q){
                $q->available();
            }, 'inventories.attributeValues.attribute',
            'inventories.feedbacks:rating,feedbackable_id,feedbackable_type',
            'inventories.shop.feedbacks:rating,feedbackable_id,feedbackable_type',
            'inventories.shop.image:path,imageable_id,imageable_type',
        ])->firstOrFail();

        return view('theme::offers', compact('product'));
    }

    /**
     * Open brand list page
     *
     * @return \Illuminate\Http\Response
     */
    public function all_brands()
    {
        $brands = Manufacturer::select('id','slug','name')->active()->with('logoImage')->paginate(24);

        return view('theme::brand_lists', compact('brands'));
    }

    /**
     * Open shop list page
     *
     * @return \Illuminate\Http\Response
     */
    public function all_shops()
    {
        $shops = Shop::select('id','slug','name','id_verified','phone_verified','address_verified')
        ->active()->with('logoImage')->paginate(24);

        return view('theme::shop_lists', compact('shops'));
    }

    /**
     * Open shop page
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function shop($slug)
    {
        $shop = Shop::where('slug', $slug)->active()
        ->with(['feedbacks' => function($q){
            $q->with('customer:id,nice_name,name')->latest()->take(10);
        }])
        ->withCount(['inventories' => function($q){
            $q->available();
        }])->firstOrFail();

        // Check shop maintenance_mode
        if(getShopConfig($shop->id, 'maintenance_mode')) {
            return response()->view('theme::errors.503', [], 503);
        }

        $products = Inventory::where('shop_id', $shop->id)
        ->groupBy('product_id')
        ->filter(request()->all())
        ->with(['feedbacks:rating,feedbackable_id,feedbackable_type', 'images:path,imageable_id,imageable_type'])
        ->withCount(['orders' => function($q){
            $q->where('order_items.created_at', '>=', Carbon::now()->subHours(config('system.popular.hot_item.period', 24)));
        }])
        ->available()->paginate(20);

        return view('theme::shop', compact('shop', 'products'));
    }

    /**
     * Open brand page
     *
     * @param  slug  $slug
     * @return \Illuminate\Http\Response
     */
    public function brand($slug)
    {
        $brand = Manufacturer::where('slug', $slug)->firstOrFail();

        $ids = Product::where('manufacturer_id', $brand->id)->pluck('id');

        $products = Inventory::whereIn('product_id', $ids)->filter(request()->all())
        ->whereHas('shop', function($q) {
            $q->select(['id', 'current_billing_plan', 'active'])->active();
        })
        ->with(['feedbacks:rating,feedbackable_id,feedbackable_type', 'images:path,imageable_id,imageable_type'])
        ->withCount(['orders' => function($q){
            $q->where('order_items.created_at', '>=', Carbon::now()->subHours(config('system.popular.hot_item.period', 24)));
        }])
        ->active()->groupBy('product_id')->groupBy('shop_id')->paginate(20);

        return view('theme::brand', compact('brand', 'products'));
    }

    /**
     * Display the category list page.
     * @return \Illuminate\Http\Response
     */
    public function categories()
    {
        return view('theme::categories');
    }

    /**
     * Display the specified resource.
     *
     * @param  str  $slug
     * @return \Illuminate\Http\Response
     */
    public function openPage($slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return view('theme::page', compact('page'));
    }

    /**
     * Push product ID to session for the recently viewed items section
     *
     * @param  [type] $item [description]
     */
    private function update_recently_viewed_items($item)
    {
        $items = Session::get('products.recently_viewed_items', []);

        if(! in_array($item->getKey(), $items)) {
            Session::push('products.recently_viewed_items', $item->getKey());
        }

        return;
    }
}
