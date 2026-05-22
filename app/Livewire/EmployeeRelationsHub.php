<?php

namespace App\Livewire;

use Livewire\Component;

class EmployeeRelationsHub extends Component
{
    public string $activeTab = 'complaints';

    /** Scope records to this employee (null = all, for admin / HR manage). */
    public ?int $employeeId = null;

    /** Full manage UI (create/edit all fields) like admin. */
    public bool $allowManage = true;

    /** Employee self-service: file own complaints/conflicts; disciplines & resolutions view-only. */
    public bool $personalMode = false;

    /** When true, create/edit actions require edit_employee_relations permission. */
    public bool $requirePermission = false;

    public string $downloadRoute = 'admin.employee-relations.download';

    public function setTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function render()
    {
        return view('livewire.employee-relations-hub');
    }
}
