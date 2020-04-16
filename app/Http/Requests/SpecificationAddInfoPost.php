<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SpecificationAddInfoPost extends FormRequest {
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

      'specification_name'  => 'required|max:250',
      'id_part' => 'numeric',
      'specification_dimansion' => 'numeric',
      'dimansion_limit' => 'numeric',

    ];
  }
}
