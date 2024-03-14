<?php

namespace App\Marketing\Jobs\Vkontakte;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use VK\Client\VKApiClient;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Redis;
use App\Job;

class ImportStats implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $jobModel;

    public function __construct(Job $jobModel)
    {
        $this->jobModel = $jobModel;
    }

    public function handle()
    {
        Redis::funnel('vkontakte')->limit(1)->then(function () {
            $job = $this->jobModel;
      
            $access_token = config('app.vk_access_token');
            $account_id = config('app.vk_account_id');
            $vk = new VKApiClient();

            $jobAds = $job->ads()
                ->ofAdvertisingPlatform('VKONTAKTE')
                ->whereNotNull('advertising_platform_uuid')
                ->whereNotNull('advertising_platform_data')
                ->get();
            $adIds = $jobAds->pluck('advertising_platform_uuid');

            $client_id = $jobAds->first()->advertising_platform_data['client_id'];

            // Clicks, spent
            $stats = $vk->ads()->getStatistics($access_token, [
                    'account_id' => $account_id,
                    'ids_type' => 'ad',
                    'ids' => $adIds->implode(','),
                    'period' => 'overall',
                    'date_from' => 0,
                    'date_to' => 0,
                ]);
            usleep(500000);

            foreach ($stats as $stat) {
                if (isset($stat['stats'][0])) {
                    $adId = $stat['id'];
                    $ad = \App\Ad::where('advertising_platform_uuid', $adId)->first();
                    $statData = $stat['stats'][0];
                    $ad->spent = $statData['spent'];
                    $ad->clicks = $statData['clicks'];
                    $ad->saveWithoutEvents();
                }
            }

            // Recomendations
            $vkAds = $vk->ads()->getAds($access_token, [
                    'account_id' => $account_id,
                    'client_id' => $client_id,
                    'ad_ids' => json_encode($adIds->all()),
                ]);
            usleep(500000);

            foreach ($vkAds as $vkAd) {
                $adId = $vkAd['id'];
                $ad = \App\Ad::where('advertising_platform_uuid', $adId)->first();
                if ($vkAd['approved'] == 3) {
                    $moderation = '';               
                    $recommendations = $vk->ads()->getRejectionReason($access_token, [
                            'account_id' => $account_id,
                            'ad_id' => $adId,
                        ]);
                    usleep(500000);
                    if (isset($recommendations['rules'])) {
                        $moderation = collect($recommendations['rules'])->map(function ($recommendation) {
                            return strip_tags((new \App\Helpers\Parser)->parseString($recommendation['content_html']));
                        })->implode("\n\n");
                    }
                    $ad->moderation = $moderation;  
                } else {
                    $ad->moderation = null; 
                }
                $ad->saveWithoutEvents();
            }            
        }, function () {
            return $this->release();
        });   
    }

    /**
     * Determine the time at which the job should timeout.
     *
     * @return \DateTime
     */
    public function retryUntil()
    {
        return now()->addYears(1);
    }

}