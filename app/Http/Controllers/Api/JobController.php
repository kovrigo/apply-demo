<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Ad;
use App\Job;
use App\Transaction;
use App\Tenant;
use sngrl\SphinxSearch\SphinxSearch;
use App\Settings\RequestContext;
use App\Settings\Scripting\Script;

class JobController extends \App\Http\Controllers\Controller
{

    public function search(Request $request)
    {  
        $sphinx = new SphinxSearch();
        $results = $sphinx->search($request->search, 'jobs')
            ->filter('tenant_id', apply()->tenantId)
            ->setFieldWeights([
                'name' => 100,
                'skills' => 5,
                'qualifications' => 5,
                'incentives' => 5,
                'responsibilities' => 5,
            ])    
            ->query();
        if (isset($results['total']) && $results['total'] > 0) {
            $results = collect(array_keys($results['matches']))->map(function ($item) {
                return ['id' => $item];
            })->all();
        } else {
            $results = [];
        }
        return response()->json($results);
    }

    public function promote(Request $request, Job $job)
    {     
        $job->promoted = true;
        $job->save();
        Ad::promote($job);
        return response()->json();
    }

    public function stopPromotion(Request $request, Job $job)
    {  
        $job->promoted = false;
        $job->save();
        Ad::promote($job);
        return response()->json();
    }

    public function publish(Request $request, Job $job)
    {  
        $job->published = true;
        $job->save();
        return response()->json();
    }

    public function unpublish(Request $request, Job $job)
    {  
        $job->published = false;
        $job->save();
        return response()->json();
    }

    public function reserve(Request $request, Job $job)
    {  
        $balance = Tenant::where('id', apply()->tenantId)->first()->balance;
        $amount = $request->amount;
        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $balance,
        ]);
        // Create transaction
        $currency = $job->tenant->currency;
        $transaction = new Transaction;
        $transaction->currency()->associate($currency);
        $transaction->job()->associate($job);
        $transaction->type = 'debit';
        $transaction->amount = $amount;
        $transaction->save();
        // Update job fields
        $job->reserved += $amount;
        $job->save();
        return response()->json();
    }

    public function reservableAmount(Request $request, Job $job)
    {
        return response()->json(Tenant::where('id', apply()->tenantId)->first()->balance);
    }

    public function refund(Request $request, Job $job)
    {  
        $balance = $job->reserved - $job->spent;
        // Create transaction
        $currency = $job->tenant->currency;
        $transaction = new Transaction;
        $transaction->currency()->associate($currency);
        $transaction->job()->associate($job);
        $transaction->type = 'refund';
        $transaction->amount = $balance;
        $transaction->save();
        // Update job fields
        $job->reserved -= $balance;
        $job->save();
        return response()->json();
    }

}
