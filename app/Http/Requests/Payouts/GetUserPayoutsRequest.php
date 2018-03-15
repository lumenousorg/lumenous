<?php

namespace lumenous\Http\Requests\Payouts;

use Illuminate\Foundation\Http\FormRequest;

class GetUserPayoutsRequest extends FormRequest {

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->route('user')->id == $this->user()->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                //
        ];
    }

}
