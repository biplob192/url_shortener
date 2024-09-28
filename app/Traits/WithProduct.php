<?php

namespace App\Traits;

use App\Models\ProductChannel;
use App\Models\ProductMedia;
use App\Models\ProductMeta;
use App\Models\ProductPrice;
use App\Models\ProductRecommendation;
use App\Models\ProductTag;
use App\Models\ProductVariant;
use App\Models\Stock;
use Illuminate\Support\Facades\Storage;

trait WithProduct
{
    public $product_id;

    public $product_type;

    public $selectattribute_list=[];

    public $variant_id = [];

    public $category;

    public $sub_category=[];

    public $shipping_class;

    public $tags = [];

    public $tags_de = [];

    public $tag_list = [];

    public $tag_list_de = [];

    public $unit_id;

    public $manufacturer_id;

    public $product_title;

    public $product_title_german;

    public $product_subtitle;

    public $product_subtitle_german;

    public $product_sort_description;

    public $product_sort_description_german;

    public $product_description;

    public $product_description_german;

    public $teaser_text_de = '';

    public $product_label;

    public $low_quantity_alert;

    public $discount_id;

    public $expire_period_indays;

    public $expiry_alert;

    public $is_suspended = false;

    public $is_component_item = false;

    public $is_tax_included_in_price = true;

    public $show_on_invoice = true;

    public $has_warehouse_limit = false;

    public $channel_select=[];

    public $SelectAll=false;

    public $product_attributes = [];

    //product optional multi language field
    public $flavours_german='';

    public $country_origin='';

    public $country_origin_german='';

    public $region='';

    public $region_german='';

    public $producer='';

    public $producer_german='';

    public $altitude='';

    public $altitude_german='';

    public $quality='';

    public $quality_german='';

    public $processing_method='';

    public $processing_method_german='';

    // end product optional multi language field
    /**
     * sync product meta.
     *
     * @return array
     */
    public function sync_product_meta(): array
    {
        $product_meta_common = [
            ['product_id' => $this->product_id, 'meta_key' => 'pro_pattern', 'meta_value'    => $this->pattern],
            ['product_id' => $this->product_id, 'meta_key' => 'body_intensity', 'meta_value' => $this->body_intensity],
            ['product_id' => $this->product_id, 'meta_key' => 'acidity', 'meta_value'        => $this->acidity_level],
            ['product_id' => $this->product_id, 'meta_key' => 'roast_level_num', 'meta_value' => $this->roast_level],
            ['product_id' => $this->product_id, 'meta_key' => 'altitude', 'meta_value'        => $this->altitude],
            ['product_id' => $this->product_id, 'meta_key' => 'shipping_class', 'meta_value'  => $this->shipping_class],
        ];

        $product_meta_en = [
            ['product_id' => $this->product_id, 'meta_key' => 'pro_subtitle', 'meta_value'   => $this->product_subtitle],
            ['product_id' => $this->product_id, 'meta_key' => 'short_description', 'meta_value' => $this->product_sort_description],
            ['product_id' => $this->product_id, 'meta_key' => 'description', 'meta_value'    => $this->product_description],
            ['product_id' => $this->product_id, 'meta_key' => 'teaser_text', 'meta_value'    => $this->teaser_text],
            ['product_id' => $this->product_id, 'meta_key' => 'flavours', 'meta_value'       => $this->flavours],
            ['product_id' => $this->product_id, 'meta_key' => 'product_region', 'meta_value' => $this->region],
            ['product_id' => $this->product_id, 'meta_key' => 'origin', 'meta_value'         => $this->country_origin],
            ['product_id' => $this->product_id, 'meta_key' => 'countries_of_origin', 'meta_value' => $this->country_origin],
            ['product_id' => $this->product_id, 'meta_key' => 'producer', 'meta_value'       => $this->producer],
            ['product_id' => $this->product_id, 'meta_key' => 'product_quality', 'meta_value' => $this->quality],
            ['product_id' => $this->product_id, 'meta_key' => 'process', 'meta_value'         => $this->processing_method],
            ['product_id' => $this->product_id, 'meta_key' => 'tags', 'meta_value'            => json_encode($this->tags)],
        ];

        $product_meta_de = [
            ['product_id' => $this->product_id, 'meta_key' => 'pro_subtitle_de', 'meta_value'     => $this->product_subtitle_german],
            ['product_id' => $this->product_id, 'meta_key' => 'short_description_de', 'meta_value'  => $this->product_sort_description_german],
            ['product_id' => $this->product_id, 'meta_key' => 'description_de', 'meta_value'     => $this->product_description_german],
            ['product_id' => $this->product_id, 'meta_key' => 'teaser_text_de', 'meta_value'     => $this->teaser_text_de],
            ['product_id' => $this->product_id, 'meta_key' => 'flavours_de', 'meta_value'     => $this->flavours_german],
            ['product_id' => $this->product_id, 'meta_key' => 'product_region_de', 'meta_value'     => $this->region_german],
            ['product_id' => $this->product_id, 'meta_key' => 'origin_de', 'meta_value'     => $this->country_origin_german],
            ['product_id' => $this->product_id, 'meta_key' => 'countries_of_origin_de', 'meta_value' => $this->country_origin_german],
            ['product_id' => $this->product_id, 'meta_key' => 'producer_de', 'meta_value'     => $this->producer_german],
            ['product_id' => $this->product_id, 'meta_key' => 'product_quality_de', 'meta_value'     => $this->quality_german],
            ['product_id' => $this->product_id, 'meta_key' => 'process_de', 'meta_value'     => $this->processing_method_german],
            ['product_id' => $this->product_id, 'meta_key' => 'tags_de', 'meta_value'     => json_encode($this->tags_de)],
        ];

        $product_meta = array_merge($product_meta_common, $product_meta_en, $product_meta_de);

        $new_product_meta['product_meta_common'] =  $product_meta_common;
        $new_product_meta['product_meta_en']     =  $product_meta_en;
        $new_product_meta['product_meta_de']     =  $product_meta_de;

        ProductMeta::upsert($product_meta, ['product_id', 'meta_key'], ['meta_value']);

        return $new_product_meta;
    }

    /**
     * sync product channels.
     *
     * @return array
     */
    public function sync_product_channels(): array
    {
        $product_channel_data = [];

        ProductChannel::where('product_id', $this->product_id)->delete();
        ProductPrice::where('product_id', $this->product_id)->update(['is_active'=>0]);
        foreach ($this->channel_select as $channel) {
            $channel_data_where['product_id'] = $this->product_id;
            $channel_data_where['name']       = $channel;

            $channel_data['product_id'] = $this->product_id;
            $channel_data['name']       = $channel;

            ProductChannel::updateOrCreate($channel_data_where, $channel_data);
            ProductPrice::where('product_id', $this->product_id)->where('price_type', $channel)->update(['is_active'=>1]);

            $product_channel_data[] = $channel;
        }

        return $product_channel_data;
    }

    /**
     * add or update upsell or crosssell products.
     *
     * @param bool $is_cross_sell
     *
     * @return array
     */
    public function sync_recommended_products($is_cross_sell = false): array
    {
        $recommended_products = [];

        if (count($this->upsell_products) <1 && count($this->cross_sell_products) < 1) {
            ProductRecommendation::where('product_id', $this->product_id)->delete();

            return $recommended_products;
        }
        $upsell_or_crosssell_products = $is_cross_sell ? $this->cross_sell_products : $this->upsell_products;
        $delete_product['product_id'] = $this->product_id;
        if ($is_cross_sell) {
            $delete_is['is_cross_sell']= 1;
        } else {
            $delete_is['is_upsell'] = 1;
        }
        ProductRecommendation::where($delete_product)->where($delete_is)->delete();

        foreach ($upsell_or_crosssell_products as $upsell_or_crosssell_product) {
            $upsell_where['product_id']             = $this->product_id;
            $upsell_where['recommended_product_id'] = $upsell_or_crosssell_product;

            $upsell_row['product_id']             = $this->product_id;
            $upsell_row['recommended_product_id'] = $upsell_or_crosssell_product;

            if ($is_cross_sell) {
                $upsell_row['is_cross_sell'] = 1;
            } else {
                $upsell_row['is_upsell'] = 1;
            }

            ProductRecommendation::Create($upsell_row);
            $recommended_products[] = $upsell_or_crosssell_product;
        }

        return $recommended_products;
    }

    /**
     * sync product price.
     *
     * @param  int $variant_id
     * @return array
     */
    public function sync_product_price($variant_id, $sku): array
    {
        $price_data = [];

        $price_row_where['product_id'] = $this->product_id;
        $price_row_where['variant_id'] = $variant_id;

        $price_row['product_id']    = $this->product_id;
        $price_row['variant_id']    = $variant_id;
        $price_data['variant_id']   = $variant_id;
        $price_collection           = collect($this->price_section);
        $price                      = $price_collection->where('variant_sku', $sku)->first();
        //organization dynamic price code
        $price_data['variant_name'] = $price['variant_name'];
        $price_data['variant_sku']  = $price['variant_sku'];
        foreach ($this->channel_select as $channel) {
            $price_data[$channel]       = $price[$channel];
            $index_name_price                 =$channel;
            $index_name_account_number        =$channel.'_account_number';
            $index_name_cost_center_1         =$channel.'_cost_center_1';
            $index_name_cost_center_2         =$channel.'_cost_center_2';
            $price_type              = $index_name_price;
            $p_price                 = $price[$index_name_price];
            ProductPrice::updateOrCreate(
                ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type],
                ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type, 'cost_price' => $price['cost_price'], 'price' => $p_price, 'currency' => $price['currency'], 'account_number' => $price[$index_name_account_number], 'cost_center_1' => $price[$index_name_cost_center_1], 'cost_center_2' => $price[$index_name_cost_center_2]]
            );
        }

        //organization dynamic price code end 

//         $price_data['b2b']          = $price['b2b'];
//         $price_data['online']       = $price['online'];
//         $price_data['gastronomy']   = $price['gastronomy'];
//         $price_data['retail']       = $price['retail'];
//         $price_data['espresso']     = $price['espresso'];
//         $price_data['variant_name'] = $price['variant_name'];
//         $price_data['variant_sku']  = $price['variant_sku'];
//         if ($price['b2b']) {
//             $price_type              = 'b2b';
//             $p_price                 = $price['b2b'];
//             $is_price_option_enabled = 1;
//             ProductPrice::updateOrCreate(
//                 ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type],
//                 ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type, 'cost_price' => $price['cost_price'], 'price' => $p_price, 'currency' => $price['currency'], 'account_number' => $price['b2b_account_number'], 'cost_center_1' => $price['b2b_cost_center_1'], 'cost_center_2' => $price['b2b_cost_center_2'], 'is_price_option_enabled' => $is_price_option_enabled]
//             );
//         }
//         if ($price['online']) {
//             $price_type             ='online';
//             $p_price                =$price['online'];
// //            $price_data['online']  = $price['online'];
//             $is_price_option_enabled=1;
//             ProductPrice::updateOrCreate(
//                 ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type],
//                 ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type, 'cost_price' => $price['cost_price'], 'price' => $p_price, 'currency' => $price['currency'], 'account_number' => $price['online_account_number'], 'cost_center_1' => $price['online_cost_center_1'], 'cost_center_2' => $price['online_cost_center_2'], 'is_price_option_enabled' => $is_price_option_enabled]
//             );
//         }
//         if ($price['gastronomy']) {
//             $price_type             ='gastronomy';
//             $p_price                =$price['gastronomy'];
//             $is_price_option_enabled=1;
//             ProductPrice::updateOrCreate(
//                 ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type],
//                 ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type, 'cost_price' => $price['cost_price'], 'price' => $p_price, 'currency' => $price['currency'],  'account_number' => $price['gastronomy_account_number'], 'cost_center_1' => $price['gastronomy_cost_center_1'], 'cost_center_2' => $price['gastronomy_cost_center_2'], 'is_price_option_enabled' => $is_price_option_enabled]
//             );
//         }
//         if ($price['retail']) {
//             $price_type             ='retail';
//             $p_price                =$price['retail'];
//             $is_price_option_enabled=0;
//             ProductPrice::updateOrCreate(
//                 ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type],
//                 ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type, 'cost_price' => $price['cost_price'], 'price' => $p_price, 'currency' => $price['currency'],  'account_number' => $price['retail_account_number'], 'cost_center_1' => $price['retail_cost_center_1'], 'cost_center_2' => $price['retail_cost_center_2'], 'is_price_option_enabled' => $is_price_option_enabled]
//             );
//         }
//         if ($price['espresso']) {
//             $price_type             ='espresso';
//             $p_price                =$price['espresso'];
//             $is_price_option_enabled=1;
//             ProductPrice::updateOrCreate(
//                 ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type],
//                 ['product_id' => $this->product_id, 'variant_id' => $variant_id, 'price_type' => $price_type, 'cost_price' => $price['cost_price'], 'price' => $p_price, 'currency' => $price['currency'], 'account_number' => $price['espresso_account_number'], 'cost_center_1' => $price['espresso_cost_center_1'], 'cost_center_2' => $price['espresso_cost_center_2'], 'is_price_option_enabled' => $is_price_option_enabled]
//             );
//         }

        // //update account number & cost center in product_varinats table
        // ProductVariant::updateOrCreate(
        //     ['id' => $variant_id],
        //     ['account_number' => $price['account_number'], 'cost_center_1' => $price['cost_center_1'], 'cost_center_2' => $price['cost_center_2']]
        // );

        return $price_data;
    }

    /**
     * sync product media data.
     *
     * @return array
     */
    public function sync_product_media(): array
    {
        $media_data = [];

        foreach ($this->image_section as $image) {
            $path                  =null;
            $where['id']           = $image['id'];
            $data['product_id']    = $this->product_id;
            $data['image_title']   = $image['title'];
            $data['display_order'] = $image['display_order'];
            $data['is_featured']   = ($image['is_featured'] == '1') ? 1 : 0;
            if ($image['path']) {
                $data['type']           = $image['path']->extension();
                $uploadedFile           = $image['path'];
                $filename               = time().$uploadedFile->getClientOriginalName();
                $filename               =str_replace(' ', '_', $filename);
                $url                    =Storage::disk('azure')->putFileAs('product', $uploadedFile, $filename);
                $path                   =Storage::disk('azure')->url($url);
                //$data['path']           = $image['path']->store('product', 'public');
                $data['path']           = $path;
            }

            ///TODO: DELETE OLD IMAGES WHEN UPDATE
            ProductMedia::updateOrCreate($where, $data);

            /*
             * map data for product sync with eshop
             */
            $data['current_image'] = isset($image['path']) ? $data['path'] : $image['current_image'];
            $data['id']            = $image['id'];
            $data['title']         = $image['title'];
            $media_data[]          = $data;
        }

        return $media_data;
    }

    /**
     * get variant map for eshop.
     *
     * @return void
     */
    public function map_variant_for_eshop()
    {
        $variants = [];

        foreach ($this->variant_section as $variant) {
            $data['variant_name']                           = $this->createVariantName($variant);
            $data['sku']                                    = $variant['sku'];
            $data['barcode']                                = $variant['barcode'];
            $data['en_number']                              = $variant['en_number'];
            $data['frequency']                              = $variant['frequency']      ?? '';
            $data['weight']                                 = $variant['weight']         ?? '';
            $data['shipping_class']                         = $variant['shipping_class'] ?? '';
            $data['stock_status']                           = $variant['stock_status']   ?? '';
            $data['updated_at']                             = now();

            $variant_data['variant']       = $data;
            $variant_data['variant']['id'] = $variant['id'];

            foreach ($variant['attributes'] as $key => $value) {
                $attr_where['variant_id'] = $variant['id'];
                $attr_where['key']        = $key;
                $attr_data['variant_id']  = $variant['id'];
                $attr_data['key']         = $key;
                $attr_data['value']       = $value;

                $variant_data['attributes'][] = $attr_data;
            }

            $variants[] = $variant_data;
        }

        return $variants;
    }

    public function new_map_variant_for_eshop($product_id = null)
    {
        $variants = [];

        $variant_records = ProductVariant::with('attributes')->where('product_id', $product_id)->get();
        foreach ($variant_records as $variant) {
            $data['id']                                     = $variant->id;
            $data['variant_name']                           = $variant->variant_name;
            $data['sku']                                    = $variant['sku'];
            $data['barcode']                                = $variant['barcode'];
            $data['en_number']                              = $variant['en_number'];
            $data['frequency']                              = $variant['frequency']      ?? '';
            $data['weight']                                 = $variant['weight']         ?? '';
            $data['shipping_class']                         = $variant['shipping_class'] ?? '';
            $data['stock_status']                           = $variant['stock_status']   ?? '';
            $data['updated_at']                             = now();

            $variant_data['variant']       = $data;
            $variant_data['variant']['id'] = $variant->id;

            foreach ($variant->attributes as $key => $attribute) {
                $attr_data['variant_id']  =  $variant->id;
                $attr_data['key']         = $attribute->key;
                $attr_data['value']       = $attribute->value;

                $variant_data['attributes'][] = $attr_data;
            }

            $variants[] = $variant_data;
        }

        return $variants;
    }

    /**
     * get Product Tags.
     *
     * @return void
     */
    public function displayTagList()
    {
        $tag_lists = ProductTag::get();
        if (count($tag_lists) > 0) {
            $this->tag_list    = $tag_lists->where('lang', 'en');
            $this->tag_list_de = $tag_lists->where('lang', 'de');
        }
    }

    public function productIntoStock($product_id)
    {
        $variants=ProductVariant::where('product_id', $product_id)->get();
        foreach ($variants as $variant) {
            Stock::updateOrCreate(
                ['organization_id' => 1, 'product_id'=>$variant->product_id, 'variant_id' => $variant->id],
                ['organization_id' => 1, 'product_id'=>$product_id, 'variant_id' => $variant->id, 'sku' => $variant->sku, 'type' => 'warehouse']
            );
        }
    }
}
