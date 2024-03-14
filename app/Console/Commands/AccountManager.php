<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tenant;
use App\ResourceSetting;
use App\ResourceCustomSetting;
use App\UserPorfile;
use App\User;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableSeparator;
use Symfony\Component\Console\Helper\TableCell;
use App\Account\Generator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use App\Meta\Manager;

class AccountManager extends Command
{

    protected $meta;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:manage';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manage accounts';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->mainMenu();
    }

    public function mainMenu()
    {
        $tenants = Tenant::select('id', 'code', 'name')->get()->map(function ($tenant) {
            return $tenant->toArray();
        })->all();        
        $actions = ['List', 'Create', 'Delete', 'Exit'];
        $selectedAction = $this->choice('Choose action:', $actions, count($actions) - 1);
        switch ($selectedAction) {
            case 'List':
                $table = new Table($this->output);
                $table->setHeaders(['#', 'code', 'name']);
                $table->setRows($tenants);
                $table->render();        
                $this->mainMenu();
                break;
            case 'Create':
                $meta = [];
                // Name
                $meta['name'] = $this->ask('Account name');
                // Code
                $meta['code'] = $this->ask('Account code');
                // Email
                $meta['email'] = $this->ask('Admin email');
                // Admin First name
                $meta['first_name'] = $this->ask('Admin first name');
                // Admin Last name
                $meta['last_name'] = $this->ask('Admin last name');
                $password = (new Generator($meta))->generateAccount();
                $this->info('The account has been created. The admin password is: ' . $password);
                break;
            case 'Delete':
                $tenantCodes = collect($tenants)->pluck('code')->all();
                $tenantCode = $this->choice('Choose tenant to delete:', $tenantCodes, count($tenantCodes) - 1);
                if ($this->confirm('Are you sure you want to delete the account?')) {
                    $meta = [];
                    $meta['code'] = $tenantCode;
                    (new Generator($meta))->deleteAccount();
                    $this->info('The account has been deleted');
                }
                $this->mainMenu();
                break;
            default:
                break;
        }
    }

}
