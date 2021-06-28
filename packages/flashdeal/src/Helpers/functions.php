<?php

if (! function_exists('get_flash_deals'))
{
	/**
	 * Get Flash Deals
	 * @return array | null
	 */
	function get_flash_deals()
	{
	    $deals = get_from_option_table('flashdeal_items', []);

	    if (empty($deals)){
	        return null;
        }
	    // Return null if not in valid time period
	    if ($deals['start_time'] > \Carbon\Carbon::now() || \Carbon\Carbon::now() > $deals['end_time']) {
	        return Null;
	    }

	    $items = [];

	    if (! empty($deals['listings'])){
	        $items['listings'] = \App\Inventory::whereIn('id', $deals['listings'])
	            ->with([
	                'feedbacks:rating,feedbackable_id,feedbackable_type',
	                'image:path,imageable_id,imageable_type',
	                'product:id,slug',
	                'product.image:path,imageable_id,imageable_type'
	            ])
	            ->groupBy('product_id')
	            ->get();
	    }

	    if (! empty($deals['featured'])){
	        $items['featured'] = \App\Inventory::whereIn('id', $deals['featured'])
	            ->with([
	                'feedbacks:rating,feedbackable_id,feedbackable_type',
	                'image:path,imageable_id,imageable_type',
	                'product:id,slug',
	                'product.image:path,imageable_id,imageable_type'
	            ])
	            ->groupBy('product_id')
	            ->get();
	    }

	    return array_merge($deals, $items);
	}
}