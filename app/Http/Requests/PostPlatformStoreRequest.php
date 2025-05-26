<?php

namespace App\Http\Requests;

use App\Rules\NotNumbersOnly;
use Illuminate\Foundation\Http\FormRequest;

class PostPlatformStoreRequest extends FormRequest
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
        return [
            'title'=> ['required', 'string', 'max:255', new NotNumbersOnly()],
            'content' => ['required', 'string',new NotNumbersOnly()],
            'image_url' => [ 'image' , 'mimes:jpg,jpeg,png,gif,webp', 'max:600'],
            'scheduled_time' => ['required', 'date', 'after_or_equal:now'],
            'status' => ['required', 'string', 'in:draft,published,scheduled'],
            'platforms' => ['required', 'array'],
            'platforms.*.id' => ['required', 'exists:platforms,id'],
          

        ];
    }
}
