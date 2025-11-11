<?php

namespace App\Http\Controllers\Api;

use App\Enums\AllowanceGroups;
use App\Http\Controllers\Controller;
use App\Http\Resources\IndividualDisbursementResource;
use App\Http\Services\AllowanceDisbursementService;
use App\Models\AllowanceGroupEmployeePivot;
use App\Models\DisbursedAllowance;
use App\Models\EmployeeAllowance;
use App\Models\GroupCategoryEmployeeAllowance;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

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
        $user = User::find($request->user);
        $category = $request->post('basedOn');
        $results = [];
        if ($category == AllowanceGroups::CATEGORY) {
            $ids = $request->post('group_allowance_employee_pivotIds');
            // fetching group category allowances based of ids
            $results = $this->getGroupCategoryAllawances($ids);
            if ($results['status'] == 'success') {
                $response = AllowanceDisbursementService::disburseWithGroupCategory(
                    $results['data']
                );
                return response()->json($response);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => $results['message'],
                ]);
            }
            /**
             *  This is the disbursement of selected categories in all groups
             */
        } elseif ($category == 'groupCategory') {
            $allowanceDisbursementService = new AllowanceDisbursementService();
            $groups = $request->post('groups');
            $categories = $request->post('categories');
            $response = $allowanceDisbursementService->handleDisbursement(
                AllowanceGroups::CATEGORY,
                $groups,
                $categories
            );
            if ($response['status'] == 'error') {
                return response()->json([
                    'status' => 'error',
                    'message' => $response['message']
                ], 500);
            }
            return response()->json($response);
        } elseif ($category == AllowanceGroups::GROUP) {
            // $results = $this->getGroupBasedDisbursement();
        } elseif ($category == AllowanceGroups::INDIVIDUAL) {
            $response = AllowanceDisbursementService::disburseWithIndividualCategory(
                $request->post('allowanceEmployeePivotIds'),
                $user
            );
            if ($response['status'] == 'error') {
                return response()->json([
                    'status' => 'error',
                    'message' => $response['message']
                ], 500);
            }
            return response()->json($response);
        }
        return response()->json([
            'status' => 'success',
            'message' => 'Disbursement created successfully.',
            'data' => $results
        ]);
    }


    public function disburseGrouped(Request $request)
    {
        $allowanceDisbursementService = new AllowanceDisbursementService();
        $groupIds = $request->post('groupIds', []);
        $allowanceIds = $request->post('allowanceIds', []);

        $response = $allowanceDisbursementService->handleDisbursement(
            AllowanceGroups::GROUP,
            $groupIds,
            $allowanceIds
        );
        if ($response['status'] == 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $response['message']
            ], 500);
        }
        return response()->json($response);
    }



    public function disburseIndividualInGroup(Request $request)
    {
        $allowanceDisbursementService = new AllowanceDisbursementService();
        $groupIds = $request->post('groupIds', []);
        $allowanceIds = $request->post('allowanceIds', []);
        $employeesIds = $request->post('employeesIds', []);

        $response = $allowanceDisbursementService->handleDisbursement(
            'individialGroup',
            $groupIds,
            $allowanceIds,
            null,
            $employeesIds
        );
        if ($response['status'] == 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $response['message']
            ], 500);
        }
        return response()->json($response);
    }


    public function fetchDisbursements(Request $request)
    {
        // define the parameters rules
        $rules = [
            'category' => 'required|string|in:individual,group,category',
        ];
        $validated = Validator::make($request->all(), $rules);
        if ($validated->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validated->errors()->first(),
            ], 400);
        }
        $category = $request->get('category');
        $results = $this->getIndividualBasedDisbursement();
        switch ($category) {
            case ('group'):
                    $results = $this->getGroupBasedDisbursement();
                break;
            case ('category'):
                    $results = $this->getCategorizedDisbursement();
                break;
            case ('individual'):
                    $results = $this->getIndividualBasedDisbursement();
                break;
        }
        return response()->json([
            'status' => 'success',
            'disbursements' => $results
        ]);
    }


    private function getIndividualBasedDisbursement()
    {
        return DisbursedAllowance::paginate(20);
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
        )
            ->where('effective_from', '<=', now())
            ->get();
        return [
            'status' => 'success',
            'data' => $tableColumns
        ];
    }
}
