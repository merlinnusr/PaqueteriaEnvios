<?php

namespace App\Jobs;

use App\Models\Picking;
use App\Models\User;
use App\Notifications\PickingUpdatedNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendPickingUpdatedJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $picking;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Picking $picking)
    {
        //
        $this->picking = $picking;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $user = User::find(auth()->id());
        $user->notify(new PickingUpdatedNotification($this->picking));

    }
}
