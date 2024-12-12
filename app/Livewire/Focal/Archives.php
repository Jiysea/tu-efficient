<?php

namespace App\Livewire\Focal;

use App\Models\Archive;
use App\Models\Batch;
use App\Models\Beneficiary;
use App\Models\Implementation;
use App\Models\UserSetting;
use App\Services\Essential;
use App\Services\JaccardSimilarity;
use App\Services\LogIt;
use Auth;
use Carbon\Carbon;
use DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Storage;

#[Layout('layouts.app')]
#[Title('Archives | TU-Efficient')]
class Archives extends Component
{
    #[Locked]
    public $archiveId;
    #[Locked]
    public $actionId;
    public $defaultArchive;
    public $selectedRowKey = -1;
    public $searchArchives;
    public $alerts = [];
    public $start;
    public $end;
    public $calendarStart;
    public $calendarEnd;
    public $defaultStart;
    public $defaultEnd;

    # -------------------------------------

    public $promptRestoreModal = false;
    public $promptDeleteModal = false;

    # -------------------------------------

    public $similarityResults = null;
    public $isResolved = false;
    public $isPerfectDuplicate = false;
    public $isSameImplementation = false;
    public $isSamePending = false;
    public $isIneligible = false;

    # -------------------------------------

    public function restoreRow()
    {
        # Before anything else, authorize the action
        $archive = Archive::find($this->actionId ? decrypt($this->actionId) : null);
        $data = $archive->data;
        $this->authorize('restore-beneficiary-focal', [$archive]);

        # First, initialize the values for checking if the origin (batch) 
        # is not full or on `approved` status
        $batchId = $archive->data['batches_id'];
        $batch = Batch::find($batchId);
        $implementation = Implementation::find($batch->implementations_id);

        # parse the birthdate first
        $data['birthdate'] = Carbon::parse($data['birthdate'])->format('Y-m-d');
        $archive->data = $data;
        $archive->save();

        # Then do a name check for similarities and other stuffs
        $this->nameCheck($archive->data['first_name'], $archive->data['middle_name'], $archive->data['last_name'], $archive->data['extension_name'], $archive->data['birthdate']);

        # If yes then prompt or show something to not allow the restoration
        if (!$batch) {
            $this->alerts[] = [
                'message' => 'Restoration unsuccessful. Make sure the batch is existing.',
                'id' => uniqid(),
                'color' => 'red'
            ];
        } elseif ($batch?->approval_status === 'approved') {
            $this->alerts[] = [
                'message' => 'Restoration unsuccessful. Make sure the batch is still pending.',
                'id' => uniqid(),
                'color' => 'red'
            ];
        } elseif ($batch?->slots_allocated <= $this->beneficiaryCount($batchId)) {
            $this->alerts[] = [
                'message' => 'Restoration unsuccessful. Make sure the batch is not full-slotted.',
                'id' => uniqid(),
                'color' => 'red'
            ];
        } elseif ($this->isPerfectDuplicate) {
            $this->alerts[] = [
                'message' => 'Restoration unsuccessful. This record has a perfect duplicate.',
                'id' => uniqid(),
                'color' => 'red'
            ];
        } elseif ($this->isSameImplementation) {
            $this->alerts[] = [
                'message' => 'Restoration unsuccessful. This record is already on the same implementation.',
                'id' => uniqid(),
                'color' => 'red'
            ];
        } elseif ($this->isSamePending) {
            $this->alerts[] = [
                'message' => 'Restoration unsuccessful. This record is already on a pending batch.',
                'id' => uniqid(),
                'color' => 'red'
            ];
        } elseif ($this->isIneligible) {
            $this->alerts[] = [
                'message' => 'Restoration unsuccessful. This record is ineligible to apply.',
                'id' => uniqid(),
                'color' => 'red'
            ];
        } else {

            # If not, next is to get the credentials information (if any)
            $credentials = Archive::where('source_table', 'credentials')
                ->where('data->beneficiaries_id', $archive->data['id'])
                ->get();

            # then parse the datetime formats
            $data['created_at'] = Carbon::parse($data['created_at'])->format('Y-m-d h:i:s');
            $data['updated_at'] = Carbon::parse($data['updated_at'])->format('Y-m-d h:i:s');
            $archive->data = $data;
            $archive->save();

            # then insert them back to their original tables
            DB::table($archive->source_table)->insert($archive->data);
            $archive->delete();

            # same with the credentials
            if ($credentials->isNotEmpty()) {
                foreach ($credentials as $credential) {
                    $data = $credential->data;
                    $data['created_at'] = Carbon::parse($data['created_at'])->format('Y-m-d h:i:s');
                    $data['updated_at'] = Carbon::parse($data['updated_at'])->format('Y-m-d h:i:s');
                    $credential->data = $data;
                    $credential->save();

                    DB::table($credential->source_table)->insert($credential->data);

                    $credential->delete();
                }
            }

            # Log this
            LogIt::set_restore_archive($implementation, $batch, $archive, auth()->user());

            # bust the archives cache
            unset($this->archives);

            # end the code block with a modal closure and sweet alert
            $this->alerts[] = [
                'message' => 'Record successfully restored!',
                'id' => uniqid(),
                'color' => 'indigo'
            ];
        }

        $this->js('promptRestoreModal = false;');
        $this->actionId = null;
        $this->archiveId = null;
        $this->selectedRowKey = -1;
    }

    public function permanentlyDelete()
    {
        # Before anything else, authorize the action
        $archive = Archive::find($this->actionId ? decrypt($this->actionId) : null);
        $this->authorize('permdelete-beneficiary-focal', [$archive]);

        # initialize some values for logging
        $batchId = $archive->data['batches_id'];
        $batch = Batch::find($batchId);
        $implementation = Implementation::find($batch->implementations_id);

        # find its credentials (if any)
        $credentials = Archive::where('source_table', 'credentials')
            ->where('data->beneficiaries_id', $archive->data['id'])
            ->get();

        # then delete the records
        if ($credentials->isNotEmpty()) {
            foreach ($credentials as $credential) {
                if (isset($credential->data['image_file_path']) && Storage::exists($credential->data['image_file_path'])) {
                    Storage::delete($credential->data['image_file_path']);
                }
                $credential->delete();
            }
        }

        $archive->delete();

        # Log this
        LogIt::set_permanently_delete_archive($implementation, $batch, $archive, auth()->user());

        # bust the archives cache
        unset($this->archives);

        # end the code block with a modal closure and sweet alert
        $this->alerts[] = [
            'message' => 'Record has been permanently deleted!',
            'id' => uniqid(),
            'color' => 'indigo'
        ];

        # end the code block with a modal closure and sweet alert
        $this->js('promptDeleteModal = false;');
        $this->actionId = null;
        $this->archiveId = null;
        $this->selectedRowKey = -1;
    }

    public function selectRestore($encryptedId)
    {
        $this->actionId = $encryptedId;
        $this->promptRestoreModal = true;
    }

    public function selectDelete($encryptedId)
    {
        $this->actionId = $encryptedId;
        $this->promptDeleteModal = true;
    }

    public function selectRow($key, $encryptedId)
    {
        if ($this->selectedRowKey === $key) {
            $this->selectedRowKey = -1;
            $this->archiveId = null;
        } else {
            $this->selectedRowKey = $key;
            $this->archiveId = $encryptedId;
        }
    }

    #[Computed]
    public function archives()
    {
        $archives = Archive::where('source_table', 'beneficiaries')
            ->whereBetween('archived_at', [$this->start, $this->end])
            ->when($this->searchArchives, function ($q) {
                $q->where('data->first_name', 'LIKE', '%' . $this->searchArchives . '%')
                    ->orWhere('data->middle_name', 'LIKE', '%' . $this->searchArchives . '%')
                    ->orWhere('data->last_name', 'LIKE', '%' . $this->searchArchives . '%');
            })
            ->get();

        $archivesList = collect();
        foreach ($archives as $archive) {
            $batchId = $archive->data['batches_id'];
            $users_id = Implementation::find(Batch::find($batchId)?->implementations_id)?->users_id;

            if ($users_id === auth()->id()) {
                $archivesList->push([
                    'id' => $archive->id,
                    'last_id' => $archive->last_id,
                    'source_table' => $archive->source_table,
                    'data' => $archive->data,
                    'archived_at' => $archive->archived_at
                ]);
            }
        }
        return $archivesList;
    }

    #[Computed]
    public function archivesCount()
    {
        return Archive::where('source_table', 'beneficiaries')
            ->where('data->city_municipality', auth()->user()->field_office)
            ->count();
    }

    #[Computed]
    public function isDateChanged()
    {
        return Carbon::parse($this->start)->format('Y-m-d') !== Carbon::parse($this->defaultStart)->format('Y-m-d') ||
            Carbon::parse($this->end)->format('Y-m-d') !== Carbon::parse($this->defaultEnd)->format('Y-m-d');
    }

    #[Computed]
    public function full_name($person)
    {
        $full_name = $person['first_name'];

        if ($person['middle_name']) {
            $full_name .= ' ' . $person['middle_name'];
        }

        $full_name .= ' ' . $person['last_name'];

        if ($person['extension_name']) {
            $full_name .= ' ' . $person['extension_name'];
        }

        return $full_name;
    }

    protected function nameCheck($first_name, $middle_name, $last_name, $extension_name, $birthdate)
    {
        # clear out any previous similarity results
        $this->reset('similarityResults', 'isPerfectDuplicate', 'isSameImplementation', 'isSamePending', 'isIneligible');

        # double checking again before handing over to the algorithm
        # basically we filter the user input along the way
        $first_name = Essential::trimmer($first_name);
        $filteredInputString = $first_name;

        if ($middle_name && $middle_name !== '') {
            $middle_name = Essential::trimmer($middle_name);
            $filteredInputString .= ' ' . $middle_name;
        } else {
            $middle_name = null;
        }

        $last_name = Essential::trimmer($last_name);
        $filteredInputString .= ' ' . $last_name;

        # checks if there's an extension_name input
        if ($extension_name && $extension_name !== '') {
            $extension_name = Essential::trimmer($extension_name);
            $filteredInputString .= ' ' . $extension_name;
        } else {
            $extension_name = null;
        }

        $duplicationThreshold = floatval($this->settings->get('duplication_threshold', config('settings.duplication_threshold'))) / 100;

        $this->similarityResults = JaccardSimilarity::getResults($first_name, $middle_name, $last_name, $extension_name, $duplicationThreshold);

        $this->setCheckers($this->similarityResults);
    }

    protected function setCheckers(?array $results)
    {
        $batchId = Archive::find($this->actionId ? decrypt($this->actionId) : null)->data['batches_id'];
        # Checks if there are any results
        if ($results) {

            # Queries the project number of this editted beneficiary
            $project_num = Batch::join('implementations', 'implementations.id', '=', 'batches.implementations_id')
                ->where('batches.id', $batchId)
                ->select([
                    'implementations.project_num'
                ])
                ->first();

            # counts how many perfect duplicates encountered from the database
            $perfectCounter = 0;
            foreach ($results as $result) {

                # Queries the batch if it's pending on the possible duplicate beneficiary
                $batch_pending = Batch::where('batch_num', $result['batch_num'])
                    ->where('approval_status', 'pending')
                    ->exists();

                # checks if the result row is a perfect duplicate
                if ($result['is_perfect'] === true) {
                    $this->isPerfectDuplicate = true;
                    $perfectCounter++;
                }

                # checks if the result row is in the same project implementation as this editted beneficiary
                if (isset($project_num)) {
                    if ($result['project_num'] === $project_num->project_num && $this->isPerfectDuplicate) {
                        $this->isSameImplementation = true;
                    }
                }

                if ($result['is_perfect'] && $batch_pending) {
                    $this->isSamePending = true;
                }
            }

            # checks if there are already more than 2 perfect duplicates and mark this editted beneficiary as `ineligible`
            if ($perfectCounter >= 2) {
                $this->isIneligible = true;
            }
        }
    }

    public function updated($prop)
    {
        if ($prop === 'calendarStart') {
            $format = Essential::extract_date($this->calendarStart, false);
            if ($format !== 'm/d/Y') {
                $this->calendarStart = $this->defaultStart;
                return;
            }

            $this->reset('searchArchives');
            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->calendarStart)->format('Y-m-d');
            $currentTime = now()->startOfDay()->format('H:i:s');

            $this->start = $choosenDate . ' ' . $currentTime;
            if (strtotime($this->start) > strtotime($this->end)) {
                $end = Carbon::parse($this->start)->addMonth()->endOfDay()->format('Y-m-d H:i:s');
                $this->end = $end;
                $this->calendarEnd = Carbon::parse($this->end)->format('m/d/Y');
            }

            $this->archiveId = null;

            $this->dispatch('init-reload')->self();
            $this->dispatch('scroll-top-archives')->self();
        }

        if ($prop === 'calendarEnd') {
            $format = Essential::extract_date($this->calendarEnd, false);
            if ($format !== 'm/d/Y') {
                $this->calendarEnd = $this->defaultEnd;
                return;
            }

            $this->reset('searchArchives');
            $choosenDate = Carbon::createFromFormat('m/d/Y', $this->calendarEnd)->format('Y-m-d');
            $currentTime = now()->endOfDay()->format('H:i:s');

            $this->end = $choosenDate . ' ' . $currentTime;
            if (strtotime($this->start) > strtotime($this->end)) {
                $start = Carbon::parse($this->end)->subMonth()->startOfDay()->format('Y-m-d H:i:s');
                $this->start = $start;
                $this->calendarStart = Carbon::parse($this->start)->format('m/d/Y');
            }

            $this->archiveId = null;

            $this->dispatch('init-reload')->self();
            $this->dispatch('scroll-top-archives')->self();
        }
    }

    #[Computed]
    protected function beneficiaryCount($batchId)
    {
        $count = Beneficiary::join('batches', 'beneficiaries.batches_id', '=', 'batches.id')
            ->where('batches.id', $batchId)
            ->count();

        return $count;
    }

    public function removeAlert($id)
    {
        $this->alerts = array_filter($this->alerts, function ($alert) use ($id) {
            return $alert['id'] !== $id;
        });
    }

    #[Computed]
    public function settings()
    {
        return UserSetting::where('users_id', Auth::id())
            ->pluck('value', 'key');
    }

    public function mount()
    {
        if (auth()->user()->user_type !== 'focal') {
            $this->redirectIntended();
        }
        $this->defaultArchive = intval($this->settings->get('default_archive', config('settings.default_archive')));

        $this->start = now()->startOfYear()->format('Y-m-d H:i:s');
        $this->end = now()->endOfDay()->format('Y-m-d H:i:s');

        $this->calendarStart = Carbon::parse($this->start)->format('m/d/Y');
        $this->calendarEnd = Carbon::parse($this->end)->format('m/d/Y');

        $this->defaultStart = $this->calendarStart;
        $this->defaultEnd = $this->calendarEnd;
    }

    public function render()
    {
        return view('livewire.focal.archives');
    }
}
