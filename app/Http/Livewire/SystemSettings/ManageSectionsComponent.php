<?php

namespace App\Http\Livewire\SystemSettings;

use App\Models\AgencySection;
use App\Models\AgencyUnit;
use App\Models\User;

use Livewire\Component;
use Livewire\WithPagination;

use function PHPUnit\Framework\isEmpty;

class ManageSectionsComponent extends Component
{

    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    protected $listeners = ['deleteSection', 'refreshListOfSections' => '$refresh'];

    public function deleteSectionConfirmation($sectionId)
    {
        $this->dispatchBrowserEvent('deleteSectionConfirmation', ['sectionId' => $sectionId]);
    }

    public function deleteSection($sectionId){

        $checkUserAgencySection = User::select(
            "users.name",
        )
        ->join('agency_units', 'agency_units.id', '=', 'users.agency_unit_id')
        ->join('agency_sections', 'agency_sections.id', '=', 'agency_units.agency_section_id')
        ->where('agency_units.agency_section_id', '=', $sectionId)
        ->first();

        $checkUnitAgencySection = AgencyUnit::where('agency_section_id', '=', $sectionId)->first();
        
        // dd($checkUserAgencySection->toArray());

        if(!$checkUserAgencySection){
            if(!$checkUnitAgencySection){
                AgencySection::findOrFail($sectionId)->delete();
                $this->dispatchBrowserEvent('fireToast', ['icon' => 'success', 'title' => 'Successfully deleted section.']);
                $this->resetPage();
            }else{
                $this->dispatchBrowserEvent('invalidDeletionSection', ['constraints' => $checkUnitAgencySection]);
            }
        }else{
            $this->dispatchBrowserEvent('invalidDeletionSection', ['constraints' => $checkUserAgencySection]);
        }
    }

    public function showEditSectionModal($unitId)
    {
        $this->emit('openEditSectionModal', $unitId);
    }


    public function render()
    {
        $listOfSections = AgencySection::paginate(10);
        return view('livewire.system-settings.manage-sections-component', ['listOfSections' => $listOfSections]);
    }
}
