<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
//            'wallet' => 'required',
//            'region' => 'required',
//            'duration' => 'required|int',
//            'price' => 'required|int',
//            'categoryId' => 'required|int',
//            'avatar' => 'required|image|mimes:jpg,png,jpeg,gif,svg|max:2048',
//            'video' => 'required|image|mimes:mp4,png,jpeg,gif,svg|max:4048',
        ];
    }
}
