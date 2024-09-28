<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return ($this->isMethod('POST') ? $this->store() : $this->update());
    }

    protected function store()
    {
        return [
            'name'      => 'required|max:100',
            'email'     => 'required|email|unique:users',
            'phone'     => 'required|unique:users',
            'password'  => 'required',

            // This password validation should enable in the production stage.
            // 'password'  => [
            //     'required',
            //     'confirmed',
            //     Password::min(6)
            //         ->mixedCase()
            //         ->numbers()
            //         ->symbols()
            // ],
        ];
    }

    protected function update()
    {
        return [
            'name'      => 'required|max:100',
            'email'     => ['required', 'email', Rule::unique('users')->ignore($this->route('id'))],
            'phone'     => ['required', Rule::unique('users')->ignore($this->route('id'))],


            // Use 'user' instead of 'id' in apiResourceRoute
            // 'email'     => ['required', 'email', Rule::unique('users')->ignore($this->route('user'))],
            // 'phone'     => ['required', Rule::unique('users')->ignore($this->route('user'))],
        ];
    }
}
