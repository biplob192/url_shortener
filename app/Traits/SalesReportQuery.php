<?php

namespace App\Traits;

use App\Services\SalesReportService;
use Exception;

trait SalesReportQuery
{
    public $variation_id    = null;
    public $product_id      = null;
    public $processingData  = [];
    public $variation_list  = [];
    public $product_list    = [];
    public $get_variation_id = 0;
    public $get_product_id   = 0;

    public function intFilterOptionsData()
    {

        $this->productList();
        $this->variationList();
    }


    private function productList()
    {
        try {
            $default_product[]  = ['id'=>0, 'title'=>'All'];
            $this->product_list = (new SalesReportService())->getProducts();
            $this->product_list = array_merge($default_product, $this->product_list);
        } catch ( Exception $ex){
            return $ex->getMessage();
        }
    }

    private function variationList()
    {
        try {
            $default_variationt[] = ['id'=>0, 'variation_name'=>'All'];
            $this->variation_list = (new SalesReportService())->getVariations($this->get_product_id);
            $this->variation_list = array_merge($default_variationt, $this->variation_list);
        } catch ( Exception $ex){
            return $ex->getMessage();
        }
    }

    private function getSalesData()
    {
        try {
            return (new SalesReportService())->getSalesData($this->dates, $this->product_id, $this->variation_id);
        }catch ( Exception $ex){
            return $ex->getMessage();
        }
    }
}
