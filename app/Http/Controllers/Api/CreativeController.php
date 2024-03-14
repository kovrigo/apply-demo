<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Ad;
use App\Creative;

class CreativeController extends \App\Http\Controllers\Controller
{

    public function promote(Request $request, Creative $creative)
    {  
        $creative->promoted = true;
        $creative->save();
        Ad::promote($creative->job);
        Ad::allocateBudget($creative->job);
        return response()->json();
    }

    public function stopPromotion(Request $request, Creative $creative)
    {  
        $creative->promoted = false;
        $creative->save();
        Ad::promote($creative->job);
        Ad::allocateBudget($creative->job);
        return response()->json();
    }

    public function refresh(Request $request, Creative $creative)
    {  
        $creative->promoted = false;
        $creative->save();
        Ad::promote($creative->job);
        $creative->promoted = true;
        $creative->save();
        Ad::promote($creative->job);
        Ad::allocateBudget($creative->job);
        return response()->json();
    }

}
