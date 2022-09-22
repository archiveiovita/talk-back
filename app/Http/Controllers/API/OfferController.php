<?php

namespace App\Http\Controllers\API;

use App\Factories\ProductOffersFactory;
use App\Http\Requests\OfferGetRequest;
use App\Http\Requests\OfferPostRequest;
use App\Http\Resources\OfferResource;
use App\Models\FeedBack;
use App\Models\Product;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class OfferController extends ApiController
{
    private $productOffersFactory;


    public function __construct(ProductOffersFactory $productOffersFactory)
    {
        $this->productOffersFactory = $productOffersFactory;
    }

    public function submitBook(Request $request)
    {
        try {
            $this->swithLang('ro');
            $this->swithCurrency('5');
        } catch (\Exception $e) {
            return response(['message' => 'Language or currency not found'], 500);;
        }

        if ($request->get('name') && $request->get('email')) {
            $feedback = new FeedBack();
            $feedback->first_name = $request->get('name');
            $feedback->email = $request->get('email');
            $feedback->phone = $request->get('phone');
            $feedback->subject = 'Add A book';
            $feedback->status = 'new';

            $message = 'Category - <b>' . $request->get('categorySelected') . '</b><br>';
            $message .= 'Title - <b>' . $request->get('title') . '</b><br>';
            $message .= 'Author -  <b>' . $request->get('author') . '</b><br>';
            $message .= 'Second Author -  <b>' . $request->get('secondAuthor') . '</b><br>';
            $message .= 'Subject -  <b>' . $request->get('subject') . '</b><br>';
            $message .= 'Publication -  <b>' . $request->get('publication') . '</b><br>';
            $message .= 'Illustrator -  <b>' . $request->get('illustrator') . '</b><br>';
            $message .= 'Language -  <b>' . $request->get('language') . '</b><br>';
            $message .= 'Country -  <b>' . $request->get('country') . '</b><br>';
            $message .= 'ISBN -  <b>' . $request->get('isbn') . '</b><br>';
            $message .= 'Description -  <b>' . $request->get('description') . '</b><br>';
            $message .= 'Near Account -  <b>' . $request->get('nearAcc') . '</b><br>';

            $feedback->message = $message;

            if ($request->file) {
                $picture = uniqid() . '-' . $request->file->getClientOriginalName();
                $request->file->move('images/leads', $picture);
                $feedback->image = $picture;
            }

            $feedback->save();

            return response()->json(['data' => $feedback], 200);
            // return $feedback;
        }
    }

    public function createOffer(OfferPostRequest $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return response(['message' => 'Language or currency not found'], 500);;
        }

        $product = Product::find($request->get('productId'));

        if ($product) {
            $message = 'New offer from ' . $request->get('userId') . ': ' . $request->get('price') . ' NEAR for ' . $product->translation->name;
            $offer = FeedBack::create([
                'form' => 'make-offer',
                'status' => 'offer',
                'first_name' => $request->get('userId'),
                'subject' => $product->translation->name,
                'message' => $message,
                'additional_1' => $product->id,
                'additional_2' => number_format($request->get('price'), 2, '.', ' '),
            ]);

            return new OfferResource($offer);
        }

        return response(['message' => 'This product not exists'], 404);;
    }

    public function getOffers(OfferGetRequest $request)
    {
        try {
            $this->swithLang($request->get('lang'));
            $this->swithCurrency($request->get('currency'));
        } catch (\Exception $e) {
            return response(['message' => 'Language or currency not found'], 500);;
        }

        return $this->productOffersFactory->getProductOffers($request->get('productId'));
    }
}
