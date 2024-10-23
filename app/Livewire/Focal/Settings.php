<?php

namespace App\Livewire\Focal;

use App\Models\UserSetting;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Layout('layouts.app')]
#[Title('Settings')]
class Settings extends Component
{
    public $user_type;
    public $savedTechnical = false;

    #[Validate]
    public $minimum_wage;

    #[Validate]
    public $duplication_threshold;

    #[Validate]
    public $extensive_matching;

    public function rules()
    {

        return [
            'minimum_wage' => 'required|integer|min:1',
            'duplication_threshold' => 'required|integer|min:1|max:100',
            'extensive_matching' => 'required',
        ];
    }

    public function messages()
    {
        return [
            'minimum_wage.required' => 'Minimum wage must not be empty.',
            'duplication_threshold.required' => 'This field must not be empty.',
            'extensive_matching.required' => 'Please pick a selection.',

            'minimum_wage.integer' => 'Minimum wage should be a valid number.',
            'duplication_threshold.integer' => 'The threshold should be a valid number.',

            'minimum_wage.min' => 'Value should be a valid wage.',
            'duplication_threshold.min' => 'Threshold should be between 1 to 100.',

            'duplication_threshold.max' => 'Threshold should be between 1 to 100.',
        ];
    }

    public function savedTechnical()
    {
        $this->savedTechnical = false;
        $this->validate();


        $this->savedTechnical = true;
    }

    public function mount()
    {
        $user = Auth::user();
        if (Auth::user()->user_type === 'focal' || $user->isOngoingVerification()) {
            $this->user_type = 'focal';
        } else if ($user->user_type === 'coordinator' || $user->isOngoingVerification()) {
            $this->user_type = 'coordinator';
        } else {
            $this->redirectIntended();
        }

        $settings = UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');

        $minimumWage = $settings->get('minimum_wage', config('settings.minimum_wage'));

        $this->minimum_wage = intval(str_replace([',', '.'], '', number_format(floatval($minimumWage), 2)));
        $this->duplication_threshold = intval($settings->get('duplication_threshold', config('settings.duplication_threshold')));
        $this->extensive_matching = $settings->get('extensive_matching', config('settings.extensive_matching'));

    }
    public function render()
    {
        return view('livewire.focal.settings');
    }
}
