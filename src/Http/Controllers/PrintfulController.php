<?php

namespace SamiXSous\Printful\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Storage;
use SamiXSous\Printful\Facades\Printful;
use SamiXSous\Printful\Models\PrintfulKey;

use Webkul\Product\Repositories\ProductRepository;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}

class PrintfulController extends Controller
{
    /**
     * AttributeRepository instance
     *
     * @var \Webkul\Attribute\Repositories\AttributeRepository
     */
    protected $attributeRepository;


    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * ProductRepository object
     *
     * @var \Webkul\Product\Repositories\ProductRepository
     */
    protected $productRepository;

    /**
     * Create a new controller instance.
     *
     * @param  \Webkul\Product\Repositories\ProductRepository  $productRepository
     * @return void
     */

    public function __construct(
        ProductRepository $productRepository
//        OrderItemRepository $orderItemRepository,
//        CustomerRepository $customerRepository,
//        ProductInventoryRepository $productInventoryRepository
    )
    {
        $this->_config = request('_config');

        $this->middleware('admin');

        $this->productRepository = $productRepository;

//        $this->orderRepository = $orderRepository;
//
//        $this->orderItemRepository = $orderItemRepository;
//
//        $this->customerRepository = $customerRepository;
//
//        $this->productInventoryRepository = $productInventoryRepository;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector|\Illuminate\View\View
     */
    public function index()
    {
        $printfulKey = PrintfulKey::get()->first()['api_key'];
        $channels = DB::table('channels')->get();
        $storeOrders = null;
        if($printfulKey != null){
            $storeOrders = Printful::get('orders');
        }
//        $data = ['storeOrders'];
        return view($this->_config['view'], compact( 'storeOrders', 'printfulKey', 'channels'));
    }

    public function saveAPI(Request $request){
        $client = new \GuzzleHttp\Client(['headers' => ['Authorization' => 'Basic '. base64_encode($request->request->get('API'))]]);
        try {
            $response = $client->get('https://api.printful.com/store')->getStatusCode();
        }catch (RequestException $e){
//            $response = $e;
            return dd($e);
        }
        if($response == 200){
            $api = $request->request->get('API');
            $channel = $request->request->get('channel');
            PrintfulKey::firstOrCreate([
                'api_key' => $api,
                'channel_id' => $channel
            ]);
            return redirect('admin/printful');
        }


//        dd($request->request->get('API'), $response);
    }

    public function syncStore()
    {

        $storeProducts = Printful::get('store/products');
//        dd($storeProducts);
        // GET ALL PRINTFUL PRODUCTS
        $syncData = [];
        foreach ($storeProducts as $storeProduct) {
            $productId = $storeProduct['id'];
            $productName = $storeProduct['name'];
//            $productImageURL = $storeProduct['image'];

//            $imageContent = file_get_contents($productImageURL);
//            $name = substr($productImageURL, strrpos($productImageURL, '/') + 1);
//            Storage::put( 'product/' . $productId . '/' . $name, $imageContent);

            $syncData['type'] = "configurable";
            $syncData['attribute_family_id'] = "1";
            $syncData['sku'] = $productId;
            $syncData['super_attributes']['size'] = [];
            $syncData['family'] = "1";




            $productVariants = Printful::get('store/products/'. $productId);
            foreach ($productVariants['sync_variants'] as $productVariant){
                $size = trim($productVariant['name'], $productName . ' - ');
                $sizeId = DB::table('attribute_options')->where('admin_name', $size)->first()->id;
                array_push($syncData['super_attributes']['size'], "{$sizeId}");
            }


            $syncDataId = $this->productRepository->create($syncData)->id;
            $this->syncVariants($syncDataId);
            return redirect()->route('admin.catalog.products.index');
        }

    }

    public function syncVariants($id)
    {
        $storeProducts = Printful::get('store/products');
        // GET ALL PRINTFUL PRODUCTS
        $syncData = [];
        foreach ($storeProducts as $storeProduct) {
            $productId = $storeProduct['id'];
            $productName = $storeProduct['name'];

            $productImageURL = $storeProduct['thumbnail_url'];
            $imageContent = file_get_contents($productImageURL);
            $name = substr($productImageURL, strrpos($productImageURL, '/') + 1);

            Storage::put( 'product/' . $id . '/' . $name, $imageContent);

            $syncVariants['channel'] = "default";
            $syncVariants['locale'] = "en";
            $syncVariants['_method'] = "PUT";
            $syncVariants['sku'] = $productId;
            $syncVariants['name'] = $productName;
            $syncVariants['url_key'] = strtolower(str_replace('+', '-', urlencode($productName)));
            $syncVariants['tax_category_id'] = "";
            $syncVariants['new'] = true;
            $syncVariants['featured'] = true;
            $syncVariants['visible_individually'] = true;
            $syncVariants['status'] = true;
            $syncVariants['guest_checkout'] = true;
            $syncVariants['color'] = "4";
            $syncVariants['short_description'] = "";
            $syncVariants['description'] = "";
            $syncVariants['categories'] = ["1"];
            $syncVariants['variants'] = [];
            $syncVariants['channels'] = ["1"];


            $productVariants = Printful::get('store/products/' . $productId);
            foreach ($productVariants['sync_variants'] as $productVariant) {
                $size = trim($productVariant['name'], $productName . ' - ');
                $sizeId = DB::table('attribute_options')->where('admin_name', $size)->first()->id;
                $variantId = DB::table('products')->where('sku', $productVariant['sync_product_id'] . '-variant-' . $sizeId)->first()->id;
                $variantData = [
                    "sku" => $productVariant['sync_product_id'] . '-variant-' . $sizeId,
                    "name" => $productVariant['name'],
                    "size" => "{$sizeId}",
                    "inventories" => [1 => "0"],
                    "price" => $productVariant['retail_price'],
                    "weight" => "1",
                    "status" => "1"

                ];
                $syncVariants['variants'][$variantId] = $variantData;
            }
            $this->productRepository->update($syncVariants, $id);
            $this->addImageToDB($id, $name);
        }

    }

    public function addImageToDB($id, $name){
        DB::table('product_images')->insert([
                'type' => null,
                'path' => 'product/' . $id . '/' . $name,
                'product_id' => $id
            ]);
    }

}