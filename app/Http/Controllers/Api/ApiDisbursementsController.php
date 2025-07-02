<?php

namespace App\Http\Controllers\Api;

use App\Enums\AllowanceGroups;
use App\Http\Controllers\Controller;
use App\Models\DisbursedAllowance;
use Illuminate\Http\Request;

class ApiDisbursementsController extends Controller
{
    public function fetchCategoryWise(Request $request)
    {
        $category  = $request->get('category');
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
}
