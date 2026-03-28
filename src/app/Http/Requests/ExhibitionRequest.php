<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
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
        'item_name' => 'required',
        'item_detail' => 'required|max:255',
        'item_image' => 'required|image|mimes:jpeg,png',
        'condition' => 'required',
        'price' => 'required|integer|min:0',
        'category_id'   => 'required|array',
        'category_id.*' => 'exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
        'item_name.required' => '商品名を入力してください',
        'item_detail.required' => '商品の説明を入力してください',
        'item_detail.max' => '商品の説明を255文字以内で入力してください',
        'item_image.required' => '商品画像を選択してください',
        'item_image.image' => '画像を選択してください',
        'item_image.mimes' => 'jpegかpng形式のものを選択してください',
        'condition.required' => '商品の状態を選択してください',
        'price.required' => '販売価格を入力してください',
        'price.integer' => '販売価格を数字で入力てください',
        'price.min' => '販売価格は0円以上で入力してください',
        'category_id.required' => 'カテゴリーを選択してください',
        'category_id.array' => 'カテゴリーの形式が不正です',
        'category_id.*.exists' => '存在しないカテゴリーが選択されています',
        ];
    }
}
