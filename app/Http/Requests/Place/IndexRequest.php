<?php

namespace App\Http\Requests\Place;

use App\Models\UserToken;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $userToken = UserToken::where('token', $this->input('token'))->first();
        return $userToken;
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
    public function validateResolved()
    {
        $this->prepareForValidation();

        if (!$this->passesAuthorization()) {
            $this->failedAuthorization();
        }

        $instance = $this->getValidatorInstance();

        if ($instance->fails()) {
            $this->failedValidation($instance);
        }
    }
    public function failedAuthorization()
    {
        throw new HttpResponseException(response(['message' => 'Unauthorized user'], 401));
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response(['message' => 'Data cannot be deleted'], 422));
    }
}
