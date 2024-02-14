<?php

namespace App\Http\Requests\Community\Room;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'room_number' => [
                'required',
                Rule::unique('rooms')->where(function ($query) {
                    return $query
                        ->where('floor_id', $this->floor_id)
                        ->where('room_category_id', $this->room_category_id)
                        ->where('room_number', $this->room_number)
                        ->where('company_id', $this->company_id);
                }),
            ],

            'floor_id' => 'required',
            'room_category_id' => 'required',
            "room_sub_category_id" => "required",
            'status_id' => 'required',
            'company_id' => 'required',
        ];
    }
}
