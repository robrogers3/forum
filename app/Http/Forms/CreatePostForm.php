<?php

namespace App\Http\Forms;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Auth\Access\AuthorizationException;

class CreatePostForm extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //        return Gate::allows('create', new \App\Reply);
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|spamfree'
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException('This action is unauthorized.');
    }
}
