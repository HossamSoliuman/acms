<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Meeting;
use Carbon\Carbon;

class UpdateReviewStatus extends Command
{
    protected $signature = 'update:review-status';
    protected $description = 'Update review status to finished when start_at has passed';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();

        $updated = Meeting::where('status', Meeting::STATUS_USER_BOOK)
            ->where('start_at', '<', $now)
            ->update(['status' => Meeting::STATUS_MEETING_FINISHED]);
        if ($updated > 0) {
            $this->info('Updated ' . $updated . ' meetings to status "meeting_finished".');
        } else {
            $this->info('No meetings found to update.');
        }
    }
}
