<?php

namespace App\Observers;

use App\Models\Donation;
use App\Models\Project;

class DonationObserver
{
    public function saved(Donation $donation): void
    {
        $this->syncProject($donation->project_id);
    }

    public function deleted(Donation $donation): void
    {
        $this->syncProject($donation->project_id);
    }

    private function syncProject(?int $projectId): void
    {
        if (!$projectId) return;

        $project = Project::find($projectId);
        if (!$project) return;

        $collected = Donation::where('project_id', $projectId)
            ->where('status', 'completed')
            ->sum('amount');

        $project->update(['collected_amount' => $collected]);
    }
}