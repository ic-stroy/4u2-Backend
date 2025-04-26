<?php

namespace App\Service;

class ProductService {

    public function getFirstProduct($warehouse, $discount){
        if($discount){
            $warehouseProductSum_ = $discount->percent?$warehouse->sum - $warehouse->sum*(int)$discount->percent/100:$warehouse->sum;
        }else{
            $warehouseProductSum_ = $warehouse->sum;
        }
        if($warehouse->color){
            $translate_color_name = optional($warehouse->color->getTranslatedModel)->name??$warehouse->color->name;
            $color = [
                'id' => $warehouse->color->id,
                'name' => $translate_color_name??'',
                'code' => $warehouse->color->code,
            ];
        }else{
            $color = [];
        }
        $firstProducts[] = [
            'id' => $warehouse->id,
            'size' => $warehouse->size ? $warehouse->size->name : '',
            'color' => $color,
            'sum' => $warehouseProductSum_,
            'discount' => optional($discount)->percent ?? null,
            'price' => $warehouse->sum,
            'count' => $warehouse->count
        ];
        return $firstProducts;
    }

    public function getProductsByColor($warehouses, $discount, $product_id){
        return $warehouses->filter(fn($categorizedProduct) => $categorizedProduct->product_id == $product_id)->map(function($categorizedProduct) use ($discount){
            if ($discount) {
                $categorizedProductSum = $discount->percent ? $categorizedProduct->sum - $categorizedProduct->sum * (int)$discount->percent / 100 : $categorizedProduct->sum;
            } else {
                $categorizedProductSum = $categorizedProduct->sum;
            }
            $translate_color_name_ = optional($categorizedProduct->color->getTranslatedModel)->name??$categorizedProduct->color->name;
            return [
                'id' => $categorizedProduct->id,
                'size' => optional($categorizedProduct->size)->name ?? '',
                'sum' => $categorizedProductSum,
                'color' => [
                    'id' => $categorizedProduct->color->id,
                    'name' => $translate_color_name_??'',
                    'code' => $categorizedProduct->color->code,
                ],
                'discount' => $discount->percent ?? null,
                'price' => $categorizedProduct->sum,
                'count' => $categorizedProduct->count
            ];
        });
    }

}

?>
