<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GroupingCatalogInfoPost extends FormRequest {
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

      'catalog_g_name'  => 'required|max:255',
      'catalog_g_alias' => 'required|max:255',

    ];
  }
}
