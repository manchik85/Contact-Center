<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class addTaskForJur extends FormRequest {
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
      'tasc_type' => 'required|max:255',
      'tasc_name' => 'required|max:255',

    ];
  }
}
