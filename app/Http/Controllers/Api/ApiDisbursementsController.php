<?php

namespace App\Http\Controllers\Api;

use App\Enums\AllowanceGroups;
use App\Http\Controllers\Controller;
use App\Models\DisbursedAllowance;
use App\Models\GroupCategoryEmployeeAllowance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
            $results = $this->groupAllowanceDisbursement($ids);
            if ($results['status'] == 'success') {
                $result = $results['data'];
                dd($result);
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

    private function groupAllowanceDisbursement($ids)
    {
        // most high ranked table that connect all for group allowance
        $tableColumns = GroupCategoryEmployeeAllowance::whereIn(
            'id',
            $ids
        );
        return [
            'status' => 'success',
            'data' => $tableColumns
        ];
    }
}
