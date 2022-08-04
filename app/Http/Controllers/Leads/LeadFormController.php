<?php

namespace App\Http\Controllers\Leads;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

class LeadFormController extends Controller
{
    /**
     * Handle requests to frontend index.
     *
     * @return  Response
     */
    public function index(): Response
    {
        return response()->view('subscription_form');
    }
}
