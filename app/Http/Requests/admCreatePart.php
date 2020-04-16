<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class admCreatePart extends FormRequest {
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

      'id_lang' => 'required|integer',
      'id_menu' => 'required|integer',
      'id_master' => 'required|integer',
      'part_type' => 'required|integer|max:25',
      'name' => 'required|max:255',
      'alias' => 'required|max:255',
    ];
  }
}
