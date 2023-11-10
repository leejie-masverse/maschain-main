<?php

namespace App\Console\Commands;

use App\Http\Controllers\Manage\Report\DessertSummaryController;
use App\Http\Controllers\Manage\Report\IngredientSummaryController;
use App\Http\Helpers\EasyParcel;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Src\People\User;
use Src\Reward\Reward\Facades\RewardTransactionRepository;
use Src\Reward\Reward\Reward;
use Src\Reward\Reward\RewardTransaction;
use Src\Store\Booking\Order;
use function env;
use function now;

class CheckExpiredRewardTransaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    public $signature = 'reward:check-expired-transaction';
    public static $command = 'reward:check-expired-transaction';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create and update expired reward transaction to expired';

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
        $this->checkExpiredTransactions();
    }

    protected function checkExpiredTransactions()
    {
        $now = now(env('USER_TIMEZONE'))->toDateTimeString();

        $transactions = Reward::whereIn('status', [RewardTransaction::STATUS_CREATED])->where('expired_at', '<=', $now)->get();

        foreach ($transactions as $transaction) {
            RewardTransactionRepository::expired($transaction);
        }
    }

}
