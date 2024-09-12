<?php

namespace App\Livewire\Focal\Dashboard;

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
        // $last_login = session('heads-up');
        $rows = 10;
        // $activities = SystemsLog::whereBetween('created_at', [$last_login, now()])
        //     ->get();

        # -----------------------------------------------
        ## FOR TESTING
        $activities = collect();
        $users = User::where('regional_office', Auth::user()->regional_office)
            ->where('field_office', Auth::user()->field_office)
            ->where('user_type', 'coordinator')
            ->select(['first_name', 'last_name'])
            ->get()
            ->toArray();

        for ($row = 0; $row < $rows; $row++) {
            $start = Carbon::now()->subMonth()->getTimestamp();
            $end = Carbon::now()->getTimestamp();

            # date time
            $randomTimestamp = Carbon::createFromTimestamp(mt_rand($start, $end))->format('M d, Y | H:i:s');

            # random user
            $id = mt_rand(0, sizeof($users) - 1);
            $user = $users[$id]['first_name'] . ' ' . $users[$id]['last_name'];

            $activities->add([
                'log_timestamp' => $randomTimestamp,
                'user' => $user,
            ]);
        }
        return $activities;
    }

    public function render()
    {

        return view('livewire.focal.dashboard.heads-up-modal');
    }
}
