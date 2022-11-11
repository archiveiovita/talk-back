<?php

namespace App\Http\Controllers\API\v2;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductCreateRequest;
use App\Http\Requests\SearchProductsRequest;
use App\Http\Resources\ProductResource;
use App\Models\Collection;
use App\Models\Currency;
use App\Models\Lang;
use App\Models\Parameter;
use App\Models\ParameterValueProduct;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductCategoryTranslation;
use App\Models\ProductCollection;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use App\Models\ProductTranslation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\ImageManagerStatic as Image;
use SebastianBergmann\Diff\Exception;

class ProductController extends Controller
{
    public function index(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $categoryId = $request->get('category_id');

        return ProductResource::collection(
            Product::when($categoryId,
                function ($query, $categoryId) {
                    return $query->where('category_id', $categoryId);
                })->orderBy('id', 'desc')
                ->paginate(20)
        );
    }

    public function getById($id): ProductResource
    {
        $product = Product::findOrFail($id);
        return new ProductResource($product);
    }

    public function search(Request $request)
    {
        if ($request->get('find')) {
            $find = stripslashes(trim(htmlspecialchars($request->get('find'))));

            $productsFindIds = ProductTranslation::where('name', 'like', '%' . $find . '%')
                ->orWhere('body', 'like', '%' . $find . '%')
                ->orWhere('description', 'like', '%' . $find . '%')
                ->pluck('product_id')->toArray();

            $categoriesFindIds = ProductCategoryTranslation::where('name', 'like', '%' . $find . '%')
                ->orWhere('description', 'like', '%' . $find . '%')
                ->pluck('product_category_id')->toArray();

            $products = Product::whereIn('id', $productsFindIds)
                ->orwhereIn('category_id', $categoriesFindIds)
                ->get();

            return ProductResource::collection($products);
        } else {
            $data = ['errors' => [
                'message' => 'field find is required.',
                'status_code' => '422'
            ]];
            return response()->json($data, 422);
        }
    }

    public function getFeatured()
    {
        return ProductResource::collection(
            Product::where('bijoux', 1)->orderBy('id', 'desc')
                ->paginate(20)
        );
    }

    public function createProduct(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'profession' => 'required',
            'wallet' => 'required',
            'region' => 'required',
            'duration' => 'required',
            'price' => 'required|int',
            'categoryId' => 'required|int',
            'image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'videoUrl' => [
                'required',
                'url',
                function ($attribute, $yt_url, $fail) {
                    $url_parsed_arr = parse_url($yt_url);
                    if ($url_parsed_arr['host'] == "www.youtube.com" && $url_parsed_arr['path'] == "/watch" && substr($url_parsed_arr['query'], 0, 2) == "v=" && substr($url_parsed_arr['query'], 2) != "") {
                        return;
                    } else {
                        $fail(trans("validation.not_youtube_url", ["name" => trans("validation.active_url")]));
                    }
                },
            ],
            'services.*.name' => 'required',
            'services.*.image' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            $data = ['errors' => $validator->errors()];
            return response()->json($data, 422);
        }

        $product = $this->storeProduct($request->all());
        $this->storePrice($product, $request->get('price'));
        $this->storeImage($product, $request->file('image'));
        $this->storeVideo($product, $request->get('videoUrl'));

        return new ProductResource($product);
        $this->storeProperty($product, $request->get('wallet'), 'wallet');
        $this->storeProperty($product, $request->get('region'), 'region');
        $this->storeProperty($product, $request->get('duration'), 'duration');
        $this->storeProperty($product, $request->get('profession'), 'profession');
        $this->storeServices($product, $request->get('services'), $request->file('services'));

        return new ProductResource($product);
    }


    //    Replace into a Service
    //_________________________________________________________
    private function storeProduct($data)
    {
        $product = Product::create([
            'category_id' => $data['categoryId'],
            'alias' => str_slug($data['name']),
            'active' => 1
        ]);

        foreach ($this->langs as $lang) {
            $product->translations()->create([
                'lang_id' => $lang->id,
                'name' => $data['name'],
                'description' => $data['description'],
                'atributes' => 0,
                'info' => $data['wallet']
            ]);
        }

        return $product;
    }

    private function storePrice($product, $price)
    {
        if ($price) {
            $currencies = Currency::get();

            foreach ($currencies as $currency) {
                ProductPrice::create([
                    'product_id' => $product->id,
                    'currency_id' => $currency->id,
                    'old_price' => $price,
                    'price' => $price,
                ]);
            }
        }
    }

    private function storeImage($product, $file)
    {
        if ($file) {
            $imageName = uniqid() . $file->getClientOriginalName();

            $image_resize = Image::make($file->getRealPath());

            $product_image_size = json_decode(file_get_contents(storage_path('globalsettings.json')), true)['crop']['product'];

            $image_resize->save(public_path('images/products/og/' . $imageName), 75);

            $image_resize->resize($product_image_size[2]['smfrom'], $product_image_size[2]['smto'])->save('images/products/sm/' . $imageName, 85);

            return ProductImage::create([
                'product_id' => $product->id,
                'src' => $imageName,
                'main' => 1,
                'first' => 0
            ]);
        }
    }

    private function storeVideo($product, $videoUrl)
    {
        if ($videoUrl) {
            parse_str(parse_url($videoUrl, PHP_URL_QUERY), $youtubeVideoId);

            if (is_array($youtubeVideoId) && array_key_exists('v', $youtubeVideoId)) {
                $videoField = '<iframe width="100%" height="315" src="https://www.youtube.com/embed/' . $youtubeVideoId['v'] . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

                $product->update([
                    'video' => $videoField,
                ]);
            }
        }
    }

    private function getParameterByKey($key)
    {
        return Parameter::select('id')->where('key', $key)->first();
    }

    private function storeProperty($product, $property, $key)
    {
        try {
            if ($property) {
                $parameter = $this->getParameterByKey($key);
                $parameterValueProduct = ParameterValueProduct::create([
                    'parameter_id' => $parameter->id,
                    'product_id' => $product->id,
                ]);

                foreach ($this->langs as $lang) {
                    $parameterValueProduct->translations()->create([
                        'lang_id' => $lang->id,
                        'value' => $property
                    ]);
                }
            }
        } catch (Exception $e) {

        }
    }

    public function storeServices($product, $services, $files)
    {
        foreach ($services as $key => $service) {
            $imageName = $this->uploadServiceImage($files[$key]['image']);

            $collection = Collection::create([
                'banner' => $imageName,
                'active' => 1,
                'position' => 1
            ]);

            foreach ($this->langs as $lang) {
                $collection->translations()->create([
                    'lang_id' => $lang->id,
                    'name' => $service['name'],
                ]);
            }

            ProductCollection::create([
                'product_id' => $product->id,
                'collection_id' => $collection->id,
            ]);
        }
    }

    private function uploadServiceImage($file): string
    {
        $imageName = null;

        if ($file) {
            $imageName = uniqid() . $file->getClientOriginalName();
            $imageResize = Image::make($file->getRealPath());

            $imageResize->save(public_path('images/collections/og/' . $imageName), 75);
            $imageResize->save(public_path('images/collections/' . $imageName), 75);

            $imageResize->resize(480, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save('images/collections/sm/' . $imageName);

        }
        return $imageName;

    }
}