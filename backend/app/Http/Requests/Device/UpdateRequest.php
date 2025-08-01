<?php

namespace App\Http\Requests\Device;

use App\Traits\failedValidationWithName;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    use failedValidationWithName;
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
            'name' => ['required', 'min:2', 'max:50'],
            'short_name' => ['required', 'nullable', 'min:3', 'max:4'],
            // 'device_id' => ['required', 'min:3', 'max:100'],
            'location' => ['nullable', 'min:2', 'max:300'],
            'company_id' => ['required', 'min:1', 'integer'],
            'status_id' => ['required', 'min:1', 'integer'],

            'model_number' => ['nullable', 'min:4', 'max:50'],
            'device_type' =>  'required',

            'mode' => ['nullable'],

            'ip' => 'required|ip',
            'port' => 'required',
            'function' => 'nullable',
            'utc_time_zone' => 'required',
            'branch_id' => 'required',
            'serial_number' => 'required',
            'camera_save_images' => 'required',
            // 'smoke_enabled' => 'required',
            // 'water_enabled' => 'required',
            // 'acpower_enabled' => 'required',
            // 'door_enabled' => 'required',
            // 'temperature_threshold' => 'required',
        ];
    }
}
