<?php

namespace App\Http\Controllers\LeadsGroup;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class LeadGroupFormController extends Controller
{
    /**
     * Handle requests to frontend index.
     *
     * @return  Response
     */
    public function index(): Response
    {
        return response()->view('subscription_form_group');
    }
}
