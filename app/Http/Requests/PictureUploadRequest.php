<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PictureUploadRequest extends FormRequest
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
        $rules = [];

        $pictures = $this->file('pictures');
        if ($pictures != null) {
            $picturesCount = count($pictures);
            foreach(range(0, $picturesCount) as $index) {
                $rules['pictures.' . $index] = 'image|mimes:jpeg,bmp,png|max:2000';
            }
//            TODO Handle empty
//        } else {
//            return redirect($this->route('albums.create'))
//                ->withErrors($this, 'pictures')
//                ->withInput();
        }


        return $rules;
    }
}
