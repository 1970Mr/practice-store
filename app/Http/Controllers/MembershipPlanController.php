<?php

namespace App\Http\Controllers;

use App\Models\MembershipPlan;

class MembershipPlanController extends Controller
{
    public function index()
    {
        $plans = MembershipPlan::all();
        return view('membership_plans.index', compact('plans'));
    }
}
