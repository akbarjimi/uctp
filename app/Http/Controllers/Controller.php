<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function messages(): array
    {
        return [
            \trans("messages.over"),
            \trans("messages.success"),
            \trans("messages.not_enough"),
            \trans("messages.not_found"),
            \trans("messages.empty"),
        ];
    }
}
