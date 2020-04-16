<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class nodeInfoPost extends FormRequest {
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

      'parts_name'  => 'max:255',
      'parts_alias' => 'max:255',
      'parts_type'  => 'integer|max:10',
      'parent_id'   => 'integer',
      'id'          => 'integer',

    ];
  }
}
