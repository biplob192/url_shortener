<?php

namespace App\Traits;

use App\Models\Stock;
use App\Models\Transaction;

trait StockAndTransaction
{
    public function createStock($stock_data)
    {
        return Stock::create($stock_data);
    }
    public function stockIncrement($sku, $quantity)
    {
        return Stock::where('sku_id', $sku)->increment('quantity', $quantity);
    }

    public function stockDecrement($sku, $quantity)
    {
        return Stock::where('sku_id', $sku)->decrement('quantity', $quantity);
    }

    public function currentStock($sku)
    {
        return Stock::where('sku_id', $sku)->first()?->quantity;
    }

    public function createTransaction( $item, $type = 'out', $is_adjust = 0, $note = null)
    {
        $current_stock = $this->currentStock($item['sku_id']);
        $transaction['outlet_id']               = $item['outlet_id'];
        $transaction['product_id']              = $item['product_id'];
        $transaction['variation_id']            = $item['variation_id'];
        $transaction['supplier_id']             = $item['supplier_id'];
        $transaction['sku_id']                  = $item['sku_id'];
        $transaction['quantity']                = $item['quantity'];
        $transaction['stock_after_transaction'] = $current_stock ? $current_stock : 0;
        $transaction['type']                    = $type;
        $transaction['is_adjust']               = $is_adjust;
        $transaction['note']                    = $note;
        $transaction['created_by']              = auth()->user()->id;

        return Transaction::create($transaction);
    }

    public function todayTransactions()
    {
        return Transaction::query()
            ->select('variation_id','sku_id','quantity','type','is_adjust','stock_after_transaction')
            ->with(['variation:id,variation_name'])
            ->latest()
            ->take(8)
            ->get();
    }

}
