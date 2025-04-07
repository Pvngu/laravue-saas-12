<?php

namespace App\SuperAdmin\Http\Requests\Api\Lang;

use Sqids\Sqids;
use App\SuperAdmin\Http\Requests\Api\SuperAdminBaseRequest;

class UpdateRequest extends SuperAdminBaseRequest
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
     * @return array
     */
    public function rules()
    {
        $sqids = new Sqids();
        $convertedId = $sqids->decode($this->route('lang'));
        $id = $convertedId[0];

        return [
            'name' => 'required',
            'key' => 'required|unique:langs,key,' . $id,
        ];
    }
}
