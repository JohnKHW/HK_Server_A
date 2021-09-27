<?php

namespace App\Http\Requests\Place;

use App\Exceptions\NotAuthorizedException;
use App\Models\UserToken;
use Illuminate\Foundation\Http\FormRequest;

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

    public function failedAuthorization()
    {
        error_log('failed');
        $exception = new NotAuthorizedException('Unauthorized user', 401);
        throw $exception;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        error_log('rule');
        return [
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'image' => 'required|file',
            'description' => 'nullable',
        ];
    }
}
