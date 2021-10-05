<?php

namespace App\Http\Requests\Place;

use App\Exceptions\NotAuthorizedException;
use App\Models\UserToken;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Response;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $userToken = UserToken::where('token', $this->input('token'))->first();
        return $userToken && $userToken->user->role == 'ADMIN';
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'latitude' => 'required|digits',
            'longitude' => 'required|digits',
            'image' => 'required|file',
            'description' => 'nullable',
        ];
    }

    public function failedAuthorization()
    {
        throw new HttpResponseException(response(['message' => 'Unauthorized user'], 401));
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response(['message' => 'Data cannot be processed'], 422));
    }
}
