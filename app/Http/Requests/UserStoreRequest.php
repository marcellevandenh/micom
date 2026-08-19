<?php

namespace App\Http\Requests;

use Backpack\PermissionManager\app\Http\Requests\UserStoreCrudRequest;

class UserStoreRequest extends UserStoreCrudRequest
{
    public function rules()
    {
        return array_merge(parent::rules(), [
            'super' => ['sometimes', 'boolean'],
        ]);
    }

    public function attributes()
    {
        return [
            'super' => 'super user',
        ];
    }
}
