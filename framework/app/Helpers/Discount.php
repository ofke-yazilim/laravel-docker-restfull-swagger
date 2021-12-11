<?php


namespace App\Helpers;


class Discount
{
    public $products;
    public $total;
    public $sub_total = 0;
    public $total_discount  = 0;
    public $response;

    public function __construct(){}

    public function over1000(){
        if($this->total>=1000){
            $this->response['discounts'][] = [
                "discountReason"=> "10_PERCENT_OVER_1000",
                "discountAmount"=> round($this->total*20/100,2),
                "subtotal"=> round($this->total,2)
            ];
            $this->total_discount += round($this->total*20/100,2);
            $this->total          -= round($this->total*20/100,2);
        }
    }

    public function category1(){
        $productsOfCategory1 = $this->products->where('category',1)->sortBy('unit_price')->all();
        $count               = collect($productsOfCategory1)->sum('quantity');

        if($count>=2){
            $this->response['discounts'][] = [
                "discountReason"=> "BUY_2_GET_1",
                "discountAmount"=> round(collect($productsOfCategory1)->first()->unit_price,2),
                "subtotal"=> round($this->total,2),
                "product_id"=> collect($productsOfCategory1)->first()->product_id
            ];
            $this->total_discount += round(collect($productsOfCategory1)->first()->unit_price,2);
            $this->total          -= round(collect($productsOfCategory1)->first()->unit_price,2);
        }
    }

    public function category2(){
        $productsOfCategory2 = $this->products->where('category',2)->groupBy('product_id')->all();
        //$count               = collect($productsOfCategory2)->sum('quantity');

        foreach($productsOfCategory2 as $product){
            $count               = $product->sum('quantity');
            if($count>=6){
                $this->response['discounts'][] = [
                    "discountReason"=> "BUY_6_GET_2",
                    "discountAmount"=> round($product[0]->unit_price,2),
                    "subtotal"=> round($this->total,2),
                    "product_id"=> $product[0]->product_id
                ];
                $this->total_discount += round($product[0]->unit_price,2);
                $this->total          -= round($product[0]->unit_price,2);
            }
        }
    }

    public function calculate($products,$total,$order_id){
        $this->products = $products;
        $this->total    = $total;

        $this->response['order_id']   = $order_id;
        $functions      = get_class_methods($this);
        foreach ($functions as $function) {
            if($function != '__construct' && $function != 'calculate'){
                $this->{$function}();
            }
        }

        $this->response['totalDiscount']   = (string)round($this->total_discount,2);
        $this->response['discountedTotal'] = (string)round($this->total,2);
        return $this->response;
    }
}
