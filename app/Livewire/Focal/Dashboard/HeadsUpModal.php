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
        $activities = [];

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

            if ($batch->approval_status === 'encoding') {
                $encoding++;
            }

            if ($batch->approval_status === 'revalidate') {
                $revalidate++;
            }
        }

        $activities = [
            'approved' => $approved,
            'submitted' => $submitted,
            'encoding' => $encoding,
            'revalidate' => $revalidate,
        ];

        # -----------------------------------------------
        ## FOR TESTING

        // $users = User::where('regional_office', Auth::user()->regional_office)
        //     ->where('field_office', Auth::user()->field_office)
        //     ->where('user_type', 'coordinator')
        //     ->select(['first_name', 'last_name'])
        //     ->get()
        //     ->toArray();

        // for ($row = 0; $row < $rows; $row++) {
        //     $start = Carbon::now()->subMonth()->getTimestamp();
        //     $end = Carbon::now()->getTimestamp();

        //     # date time
        //     $randomTimestamp = Carbon::createFromTimestamp(mt_rand($start, $end))->format('M d, Y | H:i:s');

        //     # random user
        //     $id = mt_rand(0, sizeof($users) - 1);
        //     $user = $users[$id]['first_name'] . ' ' . $users[$id]['last_name'];

        //     $activities->add([
        //         'log_timestamp' => $randomTimestamp,
        //         'user' => $user,
        //     ]);
        // }

        return $activities;
    }

    public function render()
    {

        return view('livewire.focal.dashboard.heads-up-modal');
    }
}
