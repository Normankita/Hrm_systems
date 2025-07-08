<?php

namespace App\Http\Controllers\Api;

use App\Enums\AllowanceGroups;
use App\Http\Controllers\Controller;
use App\Models\AllowanceGroupEmployeePivot;
use App\Models\DisbursedAllowance;
use App\Models\GroupCategoryEmployeeAllowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

class ApiDisbursementsController extends Controller
{
    public function fetchCategoryWise(Request $request)
    {
        $category = $request->get('category');
        $results = [];
        if ($category == AllowanceGroups::INDIVIDUAL) {
            $results = $this->getIndividualBasedDisbursement();
        } elseif ($category == AllowanceGroups::GROUP) {
            $results = $this->getGroupBasedDisbursement();
        } elseif ($category == AllowanceGroups::CATEGORY) {
            $results = $this->getCategorizedDisbursement();
        }
        return response()->json([
            'status' => 'success',
            'category' => $category,
            'response' => $results
        ]);
    }


    public function disburse(Request $request)
    {
        $category = $request->post('basedOn');
        if ($category == AllowanceGroups::INDIVIDUAL) {
            $ids = $request->post('group_allowance_employee_pivotIds');
            // fetching group category allowances based of ids
            $results = $this->getGroupCategoryAllawances($ids);
            if ($results['status'] == 'success') {
                // fetching its corresponding employees with intermediates datas
                $gr_cat_emp_collection = $results['data'];
                $gr_cat_emp_collection = $gr_cat_emp_collection->map(
                    function ($gr_cat_emp) {
                        return $gr_cat_emp->getRealDetailsDynamic();
                    }
                );
            }

            // checking the durability of the employee to get this allowance
            /**  LOGIC AWAITED */

            // Give employee the desired allowance
            DB::beginTransaction();
            try {
                $gr_cat_emp_collection->each(function ($disburse) {
                    DisbursedAllowance::create([
                        'type' => AllowanceGroups::INDIVIDUAL,
                        'amount' => $disburse->amount,
                        'employee_id' => $disburse->employee->id,
                        'status' => true,
                        'company_id' => $disburse->employee->company_id
                    ]);
                });
                DB::commit();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Disbursement created successfully.',
                ]);
            } catch (\Throwable $th) {
                DB::rollBack();
                return response()->json([
                    'status' => 'error',
                    'message' => $th
                ], 500);
            }

        } elseif ($category == AllowanceGroups::GROUP) {
            $results = $this->getGroupBasedDisbursement();
        } elseif ($category == AllowanceGroups::CATEGORY) {
            $results = $this->getCategorizedDisbursement();
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Disbursement created successfully.',
            'data' => $request->all()
        ]);
    }


    private function getIndividualBasedDisbursement()
    {
        return DisbursedAllowance::getIndividialDisbursements();
    }

    private function getGroupBasedDisbursement()
    {
        return DisbursedAllowance::getGroupDisbursements();
    }

    private function getCategorizedDisbursement()
    {
        return DisbursedAllowance::getCategorizedDisbursements();
    }

    /**
     * returning the group category allowances with their ids
     * @param mixed $ids
     * @return array{data: \Illuminate\Database\Eloquent\Collection<int, GroupCategoryEmployeeAllowance>, status: string}
     */
    private function getGroupCategoryAllawances($ids)
    {
        // most high ranked table that connect all for group allowance
        $tableColumns = GroupCategoryEmployeeAllowance::whereIn(
            'id',
            $ids
        )->get();
        return [
            'status' => 'success',
            'data' => $tableColumns
        ];
    }
}
