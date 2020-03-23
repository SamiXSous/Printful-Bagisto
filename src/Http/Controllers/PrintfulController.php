<?php

namespace SamiXSous\Printful\Http\Controllers;



use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $syncData['type'] = "configurable";
            $syncData['attribute_family_id'] = "1";
            $syncData['sku'] = $productId;
            $syncData['family'] = "1";
            $syncDataId = $this->productRepository->create($syncData);


            $this->syncVariants($syncDataId);
        }

    }

    public function syncVariants($id)
    {
        $storeProducts = Printful::get('store/products');
//        dd($storeProducts);
        // GET ALL PRINTFUL PRODUCTS
        $syncVariants = [];
        foreach ($storeProducts as $storeProduct){
            $productId = $storeProduct['id'];
            $productName = $storeProduct['name'];
            $syncVariants['channel'] = "default";
            $syncVariants['locale'] = "en";
            $syncVariants['sku'] = $productId;
            $syncVariants['name'] = $productName;
            $syncVariants['url_key'] = urlencode($productName);
            $syncVariants['tax_category_id'] = "";
            $syncVariants['new'] = true;
            $syncVariants['featured'] = true;
            $syncVariants['visible_individually'] = false;
            $syncVariants['status'] = true;
            $syncVariants['guest_checkout'] = true;
            $syncVariants['color'] = "4";

            $syncVariants['short_description'] = "";
            $syncVariants['description'] = "";
            $syncVariants['categories'] = ["1"];
            $syncVariants['variants'] = [];
            $syncVariants['channels'] = ["1"];

            // GET ALL PRODUCTS VARIANTS
            $productVariants = Printful::get('store/products/'. $productId);
            foreach ($productVariants['sync_variants'] as $productVariant){
                $size = trim($productVariant['name'], $productName . ' - ');
                $sizeId = DB::table('attribute_options')->where('admin_name', $size)->first()->id;
//                dump($productVariant);
                $variantData = [
                    "sku" => $productVariant['sync_product_id']. '-variant-' . $productVariant['variant_id'],
                    "name" => $productVariant['name'],
                    "size" => $sizeId,
                    "inventories" => ["0"],
                    "price" => $productVariant['retail_price'],
                    "weight" => "1",
                    "status" => true

                ];
                array_push($syncVariants['variants'], $variantData);


            }
        }
        $this->productRepository->update($syncVariants, $id);

    }
}