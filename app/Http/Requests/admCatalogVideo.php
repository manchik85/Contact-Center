<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class admCatalogVideo extends FormRequest {
  /**
   * Determine if the user is authorized to make this request.
   *
   * @return bool
   */
  public function authorize() {
    return true;
  }

  /**
   * Get the validation rules that apply to the request.
   *
   * @return array
   */
  public function rules() {
    return [

      'video_linc_add' => 'required|max:350',
      'video_title_add' => 'max:350',
      'video_description_add' => 'max:550',
    ];
  }
}
