<?php

namespace App\Livewire\Focal\Dashboard;

use App\Models\Batch;
use App\Models\SystemsLog;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class HeadsUpModal extends Component
{

    #[Computed]
    public function activities()
    {
        $last_login = Carbon::parse(session('heads-up'))->format('Y-m-d H:i:s');
        $now = Carbon::parse(now())->format('Y-m-d H:i:s');
        $activities = collect();

        $batches = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
            ->join('users', 'users.id', '=', 'implementations.users_id')
            ->where('users.regional_office', Auth::user()->regional_office)
            ->where('users.field_office', Auth::user()->field_office)
            ->whereBetween('batches.updated_at', [$last_login, $now])
            ->get();

        $approved = 0;
        $submitted = 0;
        $encoding = 0;
        $revalidate = 0;
        foreach ($batches as $key => $batch) {

            if ($batch->approval_status === 'approved') {
                $approved++;
            }

            if ($batch->submission_status === 'submitted') {
                $submitted++;
            }

            if ($batch->submission_status === 'encoding') {
                $encoding++;
            }

            if ($batch->submission_status === 'revalidate') {
                $revalidate++;
            }
        }

        if ($approved !== 0) {
            $activities->add([
                'approved' => $approved
            ]);
        }
        if ($submitted !== 0) {
            $activities->add([
                'submitted' => $submitted
            ]);
        }
        if ($encoding !== 0) {
            $activities->add([
                'encoding' => $encoding
            ]);
        }
        if ($revalidate !== 0) {
            $activities->add([
                'revalidate' => $revalidate
            ]);
        }

        return $activities->count() !== 0 ? $activities->collapseWithKeys() : null;
    }

    public function render()
    {

        return view('livewire.focal.dashboard.heads-up-modal');
    }
}
