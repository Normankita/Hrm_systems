<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\InStock;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Models\OutStock;
use App\Models\Setting;
use App\Models\Stock;
use PDF;
use App\Models\StockModes;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use stdClass;

class OutStocksController extends Controller
{
    /**
     * Summary of index
     * @return \Illuminate\Contracts\View\View
     */
    public function index()
    {
        $modes = StockModes::where('name', 'expired')
        ->orWhere('name', 'damaged')
        ->orWhere('name', 'give')
        ->where('operation', 0)->get();

        $modes_ids = StockModes::where('name', 'expired')
        ->orWhere('name', 'damaged')
        ->orWhere('name', 'give')
        ->where('operation', 0)->pluck('id');

        $outStock = OutStock::whereIn('stock_mode_id', $modes_ids)->paginate(500);

        return view('admin.outstock.complementaryRecords')
        ->with('modes', $modes)
        ->with('outStocks', $outStock);
    }

    /**
     * Summary of create
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        $modes = StockModes::where('name', 'expired')
        ->orWhere('name', 'damaged')
        ->orWhere('name', 'give')
        ->where('operation', 0)->get();
        return view('admin.outstock.createComplementary', compact('modes'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        /** stock logic will be taken from salesController */
        $saleController = new SalesController();

        $stock = Stock::find($request->stock_id);
        $outItems = $request->out_stock_items;
        $stockMode = StockModes::find($request->stock_mode);
        $inStockController = new InStocksController();

        /** making sure that we have enough items in stock */
        foreach( $outItems as $out ) {
            $obj = $this->arrToObj($out);
            $item = $this->stdToItem($obj);
            $activeInStocksItems = $inStockController->currentItemQuantity($item, $stock);
            /**
             *  checking if we have enough quantity of product x in the sell queue
             * return if we have defficiency
             */
                if($obj->quantity > $activeInStocksItems) {
                    return response()->json([
                        'status' => 'error',
                        'message' => "stock run out of ".$item->name.", ".$activeInStocksItems." remains",
                    ], 200);
                }
        }
        /**
        * after making sure that all demanded products and their quantity are available
        * collect the sold items and all neccessary info to add on out stock
        */
        $outStockList = [];
        foreach( $outItems as $out ) {
            $obj = $this->arrToObj($out);
            $item = $this->stdToItem($obj);

            $index = 0;
            $soldInStock = [];
            $outStockList[$item->id] = $saleController->sellingItem($index, $obj->quantity, $soldInStock, $item, $stock);
        }
        /**
         * add the selected stocks to their corresponding outstock
         */
        foreach($outStockList as $key => $selectedPacks){
            foreach( $selectedPacks as $selected ){
                $inStock = InStock::where('id', $selected['inStock_id'])->first();
                OutStock::create([
                    'in_stock_id' => $selected['inStock_id'],
                    'quantity' => $selected['gives'],
                    'date_out'=> \Carbon\Carbon::now(),
                    'stock_mode_id' => $request->stock_mode,
                    'user_id' => $user->id,
                ]);
                if( $selected["deactivate"] ){
                    InStock::where("id", $selected['inStock_id'])
                    ->update(['isActive' => false]);
                }
            }
        }
        return response()->json([
            'status' => 'success',
            'message' => "data saved successfully",
        ]);
    }

    private function stdToItem(stdClass $obj) {
        $item = new Item();
        $item->id = $obj->id;
        $item->name = $obj->name;
        $item->code = $obj->code;
        $item->selling_price = $obj->selling_price;
        $item->desc = $obj->desc;
        $item->pref_supplier = $obj->pref_supplier;
        return $item;
    }

    private function arrToObj(array $out) {
        $obj = new stdClass();
        foreach($out as $key => $value) {
            $obj->{$key} = $value;
        }
        return $obj;
    }

    public function showComplementaryReport() {
        $modes = StockModes::where('name', 'expired')
        ->orWhere('name', 'damaged')
        ->orWhere('name', 'give')
        ->where('operation', 0)->get();

        $modes_ids = StockModes::where('name', 'expired')
        ->orWhere('name', 'damaged')
        ->orWhere('name', 'give')
        ->where('operation', 0)->pluck('id');

        $outStock = OutStock::whereIn('stock_mode_id', $modes_ids)->paginate(500);

        return view('admin.outstock.showComplementaryReport')
        ->with('modes', $modes)
        ->with('outStocks', $outStock);
    }

    public function createReport(Request $request) {
        $upTo = Carbon::parse($request->upTo)->format('Y-m-d');
        $from = Carbon::parse($request->from)->format('Y-m-d');
        $selected_modes = $request->selected_modes;
        if($upTo < $from ) {
            return redirect()->back()
            ->with(['status' => 'error', 'message' => 'upTo date must be large than from date']);
        }
        $settings = Setting::all();
        $config = [];
        foreach($settings as $setting) {
            $config[$setting->key] = $setting->value;
        }
        $outStocks = OutStock::where('created_at', '>=', $from)
            ->where('created_at', '<=', $upTo)
            ->whereIn('stock_mode_id', $selected_modes)
            ->get();
        $pdf = PDF::loadView('admin.pdfs.complementaryDatedReport',
            compact('outStocks', 'upTo', 'from', 'config'));
        $pdf->output();
        $dom_pdf = $pdf->getDomPDF();

        $canvas = $dom_pdf ->get_canvas();
        $canvas->page_text(500, 800, "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10, array(0, 0, 0));
        return  $pdf->stream('datedSales.pdf'.time());
    }
}