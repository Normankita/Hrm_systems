<?php

namespace App\Http\Utils\Traits;

use App\Models\EmployeeComplaint;
use App\Models\EmployeeConflict;
use App\Models\EmployeeDiscipline;
use App\Models\EmployeeRelationDocument;
use App\Models\EmployeeRelationResolution;
use Illuminate\Database\Eloquent\Model;

trait AuthorizesRelationAccess
{
    protected function currentEmployeeId(): ?int
    {
        return auth()->user()?->employee?->id;
    }

    protected function employeeOwnsComplaint(EmployeeComplaint $complaint): bool
    {
        return $complaint->employee_id === $this->currentEmployeeId();
    }

    protected function employeeOwnsDiscipline(EmployeeDiscipline $discipline): bool
    {
        return $discipline->employee_id === $this->currentEmployeeId();
    }

    protected function employeeOwnsConflict(EmployeeConflict $conflict): bool
    {
        $employeeId = $this->currentEmployeeId();

        return $conflict->employee_id === $employeeId
            || $conflict->other_employee_id === $employeeId;
    }

    protected function employeeOwnsResolution(EmployeeRelationResolution $resolution): bool
    {
        $employeeId = $this->currentEmployeeId();
        $resolvable = $resolution->resolvable;

        if (! $resolvable) {
            return false;
        }

        return match ($resolvable::class) {
            EmployeeComplaint::class => $resolvable->employee_id === $employeeId,
            EmployeeDiscipline::class => $resolvable->employee_id === $employeeId,
            EmployeeConflict::class => $resolvable->employee_id === $employeeId
                || $resolvable->other_employee_id === $employeeId,
            default => false,
        };
    }

    protected function authorizeOwnRelationModel(Model $model): void
    {
        $allowed = match ($model::class) {
            EmployeeComplaint::class => $this->employeeOwnsComplaint($model),
            EmployeeDiscipline::class => $this->employeeOwnsDiscipline($model),
            EmployeeConflict::class => $this->employeeOwnsConflict($model),
            EmployeeRelationResolution::class => $this->employeeOwnsResolution($model),
            default => false,
        };

        if (! $allowed) {
            abort(403, 'You are not authorized to access this record.');
        }
    }

    protected function authorizeManageRelations(): void
    {
        if (! auth()->user()?->can('view_employee_relations')) {
            abort(403, 'You are not authorized to manage employee relations.');
        }
    }

    protected function authorizeRelationDocument(EmployeeRelationDocument $document, bool $ownOnly = false): void
    {
        $document->loadMissing('documentable');

        if (! $document->documentable) {
            abort(404, 'Related record not found.');
        }

        if ($ownOnly) {
            $this->authorizeOwnRelationModel($document->documentable);
        } else {
            $this->authorizeManageRelations();
            if (! auth()->user()?->can('download_employee_relations')) {
                abort(403, 'You are not authorized to download documents.');
            }
        }
    }
}
