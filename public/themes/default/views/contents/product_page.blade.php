<style>
    .sc-add-to-cart:hover{
       border-color: #53a653 !important; 
    }
    
    .vi-pv2-visa, .vi-pv2-master_card, .vi-pv2-am_ex, .vi-pv2-discover, .vi-pv2-paypal, .vi-pv2-payment_method_direct_debit, .vi-pv2-ppc, .vi-pv2-maestro, .vi-pv2-apple_pay, .vi-pv2-google_pay, .vi-pv2-postepay, .vi-pv2-samsung_pay, .vi-pv2-jcb, .vi-pv2-union_pay, .vi-pv2-qiwi, .vi-pv2-sofort {
        background-image: url(https://alibuss.com/images/payments.png);
        background-repeat: no-repeat;
        width: 39px;
        height: 24px;
        display: inline-block;
    }
    
    .vi-pv2-paypal {
        background-position: -155px 0;
    }
    
    .vi-pv2-google_pay {
        background-position: -310px 0;
    }
    
    .vi-pv2-visa {
        background-position: 0 0;
    }
    
    .vi-pv2-master_card {
        background-position: -39px 0;
    }
    
    .vi-pv2-payment_method_direct_debit {
        background-position: -542px 0;
    }
    
    .vi-pv2-sofort {
        background-position: -583px 0;
    }
</style>

@php
	$geoip = geoip(request()->ip());
	$shipping_country = $business_areas->where('iso_code', $geoip->iso_code)->first();
	$shipping_state = \DB::table('states')->select('id', 'name', 'iso_code')->where([
		['country_id', '=', $shipping_country->id], ['iso_code', '=', $geoip->state]
	])->first();

	$shipping_zone = get_shipping_zone_of($item->shop_id, $shipping_country->id, optional($shipping_state)->id);
	$shipping_options = isset($shipping_zone->id) ? getShippingRates($shipping_zone->id) : 'NaN';
@endphp

<section>
	<div class="container">
		<div class="row sc-product-item" id="single-product-wrapper">
		  	<div class="col-md-5 col-sm-6">
		  		@include('theme::layouts.jqzoom', ['item' => $item, 'variants' => $variants])
		  	</div><!-- /.col-md-5 col-sm-6 -->

		  	<div class="col-md-7 col-sm-6">
		  		<div class="row">
				  	<div class="col-md-7 col-sm-6 nopadding">
				      	<div class="product-single">
					  		@include('theme::layouts.product_info', ['item' => $item])

			              	<div class="space20"></div>

				          	<div class="product-info-options space10">
				              	<div class="select-box-wrapper">
				              		@foreach($attributes as $attribute)
					                  	<div class="row product-attribute">
										  	<div class="col-3 col-xs-4">
					    	              		<span class="info-label" id="attr-{{Str::slug($attribute->name)}}" >{{ $attribute->name }}:</span>
											</div>
										  	<div class="col-9 col-xs-8 nopadding-left">
							                    <select class="product-attribute-selector {{$attribute->css_classes}}" id="attribute-{{$attribute->id}}" required="required">
								              		@foreach($attribute->attributeValues as $option)
						                          		<option value="{{ $option->id }}" data-color="{{ $option->color ?? $option->value }}" {{ in_array($option->id, $item_attrs) ? 'selected' : '' }}>{{ $option->value }}</option>
								              		@endforeach
						                      	</select>
												<div class="help-block with-errors"></div>
							              	</div><!-- /.col-sm-9 .col-xs-6 -->
						              	</div><!-- /.row -->
				              		@endforeach
				              	</div><!-- /.row .select-box-wrapper -->

					          	<div class="sep"></div>

				              	<div id="calculation-section">
				                  	<div class="row">
									  	<div class="col-3 col-xs-4">
				    	              		<span class="info-label" data-options="{{ $shipping_options }}" id="shipping-options" >@lang('theme.shipping'):</span>
								            {{ Form::hidden('shipping_zone_id', isset($shipping_zone->id) ? $shipping_zone->id : Null, ['id' => 'shipping-zone-id']) }}
								            {{ Form::hidden('shipping_rate_id', Null, ['id' => 'shipping-rate-id']) }}
								            {{ Form::hidden('shipto_country_id', $shipping_country->id, ['id' => 'shipto-country-id']) }}
								            {{ Form::hidden('shipto_state_id', optional($shipping_state)->id, ['id' => 'shipto-state-id']) }}
										</div>
									  	<div class="col-9 col-xs-8 nopadding-left">
				                            <span id="summary-shipping-cost" data-value="0"></span>
					                        <div id="product-info-shipping-detail">
					                            <span>{{ strtolower(trans('theme.to')) }}

					                            	<a id="shipTo" class="ship_to" data-country="{{$shipping_country->id}}" data-state="{{optional($shipping_state)->id}}" href="javascript:void(0)">
					                            		{{ $shipping_state ? $shipping_state->name : $geoip->country }}
					                            	</a>

													<select id="width_tmp_select"><option id="width_tmp_option"></option></select>
					                            </span>

										  		<span class="dynamic-shipping-rates" data-toggle="popover" title="{{ trans('theme.shipping_options') }}">
						                            <span id="summary-shipping-carrier"></span>
						                            <small><i class="fas fa-caret-square-o-down"></i></small>
										  		</span>
									  		</div>
									  		<small class="text-muted" id="delivery-time" style="color: #1dbf73 !important; font-weight: bold !important;"></small>
						              	</div><!-- /.col-sm-9 .col-xs-6 -->
					              	</div><!-- /.row -->

				                  	<div class="row">
									  	<div class="col-3 col-xs-4">
				    	              		<span class="info-label qtt-label">@lang('theme.quantity'):</span>
										</div>
									  	<div class="col-9 col-xs-8 nopadding">
							              	<div class="product-qty-wrapper">
							                  	<div class="product-info-qty-item">
							                      	<button class="product-info-qty product-info-qty-minus">-</button>
							                      	<input class="product-info-qty product-info-qty-input" data-name="product_quantity" data-min="{{$item->min_order_quantity}}" data-max="{{$item->stock_quantity}}" type="text" value="{{$item->min_order_quantity}}">
							                      	<button class="product-info-qty product-info-qty-plus">+</button>
								                </div>
							                  	<span class="available-qty-count">@lang('theme.stock_count', ['count' => $item->stock_quantity])</span>
							              	</div>
						              	</div><!-- /.col-sm-9 .col-xs-6 -->
				                  	</div><!-- /.row -->

				                  	<div class="row" id="order-total-row">
									  	<div class="col-3 col-xs-4">
				    	              		<span class="info-label">@lang('theme.total'):</span>
										</div>
									  	<div class="col-9 col-xs-8 nopadding">
				                            <span id="summary-total" class="text-muted" style="color: #1dbf73 !important; font-weight: bold !important;">{{ trans('theme.notify.will_calculated_on_select') }}</span>
						              	</div><!-- /.col-sm-9 .col-xs-6 -->
					              	</div><!-- /.row -->
				              	</div>
				              	
				              
				          	</div><!-- /.product-option -->

				          	<div class="sep"></div>
				          	
				          	<div id="calculation-section">
			              	    <div class="row" id="order-total-row">
								  	<div class="col-3 col-xs-4">
			    	              		<span class="info-label">Delivery:</span>
									</div>
								  	<div class="col-9 col-xs-8 nopadding">
			                            <span id="approx-date"></span>
			                            
					              	</div><!-- /.col-sm-9 .col-xs-6 -->
				              	</div><!-- /.row -->
			              	</div>
			              	<br>
			              	<div id="calculation-section">
			              	    <div class="row" id="order-total-row">
								  	<div class="col-3 col-xs-4">
			    	              		<span class="info-label">Payments:</span>
									</div>
								  	<div class="col-9 col-xs-8 nopadding">
			                            <div>
                        		            <img src="{{ asset('images/payment-background.png') }}" class=" vi-pv2-paypal" alt="PayPal" title="PayPal">
                        					<img src="{{ asset('images/payment-background.png') }}" class=" vi-pv2-google_pay" alt="Google Pay" title="Google Pay">
                        					<img src="{{ asset('images/payment-background.png') }}" class=" vi-pv2-visa" alt="Visa" title="Visa">
                        					<img src="{{ asset('images/payment-background.png') }}" class=" vi-pv2-master_card" alt="MasterCard" title="MasterCard">
                        					<img src="{{ asset('images/payment-background.png') }}" class=" vi-pv2-payment_method_direct_debit" alt="Direct debit" title="Direct debit">
                        					<img src="{{ asset('images/payment-background.png') }}" class="notranslate vi-pv2-sofort" alt="Sofort" title="Sofort">
                        				</div>
					              	</div><!-- /.col-sm-9 .col-xs-6 -->
				              	</div><!-- /.row -->
			              	</div>
 				          	
 				          	@if($item->product->inventories_count > 1)
						        <a href="{{ route('show.offers', $item->product->slug) }}" class="btn btn-sm btn-link">
					        		@lang('theme.view_more_offers', ['count' => $item->product->inventories_count])
						        </a>
					        @endif
				      	</div><!-- /.product-single -->
			  		</div>

				  	<div class="col-md-5 col-sm-6">
				        <div class="seller-info space20">
				            <div class="text-muted small space10">
				            	@lang('theme.sold_by')
				            	<a href="{{ route('show.store', $item->shop->slug) }}" class="btn-link pull-right">
				            		{{ trans('theme.button.visit_store') }}
				            	</a>
				            </div>

							<img src="{{ get_storage_file_url(optional($item->shop->logoImage)->path, 'thumbnail') }}" class="seller-info-logo img-sm img-circle" alt="{{ trans('theme.logo') }}">

					        <a href="javascript:void(0)" data-toggle="modal" data-target="#shopReviewsModal" class="seller-info-name">
				            	{!! $item->shop->getQualifiedName() !!}
				            </a>

				            <div class="space10"></div>

							@include('theme::layouts.ratings', ['ratings' => $item->shop->feedbacks->avg('rating'), 'count' => $item->shop->feedbacks_count, 'shop' => true])

				        </div><!-- /.seller-info -->

			          	<a data-link="{{ route('cart.addItem', $item->slug) }}" style="background: #26bf31;" class="btn btn-primary btn-lg btn-block flat space10 sc-add-to-cart">
			          		<i class="fas fa-shopping-bag"></i> @lang('theme.button.add_to_cart')
			          	</a>
			          	
			          	<a href="{{ route('direct.checkout', $item->slug) }}" class="btn btn-lg btn-warning flat" style="width:100%; background: #f57f17;" id="buy-now-btn"><i class="fas fa-rocket"></i> @lang('theme.button.buy_now')</a>

			          	@if($item->product->inventories_count > 1)
					        <a href="{{ route('show.offers', $item->product->slug) }}" class="btn btn-block btn-link btn-sm">
					        	@lang('theme.view_more_offers', ['count' => $item->product->inventories_count])
					        </a>
						@endif

			          	{{-- @if($item->product->offers > 1)
					        <a href="{{ route('show.offers', $item->product->slug) }}" class="btn btn-block btn-link btn-sm">
					        	@lang('theme.view_more_offers', ['count' => $item->product->offers])
					        </a>
						@endif --}}

						<div class="clearfix space20"></div>

						@if($item->key_features)
							<div>
						        <div class="section-title">
						          <h4>{!! trans('theme.section_headings.key_features') !!}</h4>
						        </div>
								<ul class="key_feature_list" id="item_key_features">
									@foreach(unserialize($item->key_features) as $key_feature)
										<li>{!! $key_feature !!}</li>
									@endforeach
								</ul>
							</div>
						@endif
			  		</div>
		  		</div><!-- /.row -->
		      	<div class="space20"></div>
		  	</div><!-- /.col-md-7 col-sm-6 -->
		</div><!-- /.row -->
	</div><!-- /.container -->
</section>

<div class="clearfix space20"></div>

<section id="item-desc-section">
    <div class="container">
      	<div class="row">
      		@if($linked_items->count())
		        <div class="col-md-3 bg-light nopadding-right">
			        <div class="section-title">
			          <h4 class="space20">@lang('theme.section_headings.bought_together'):</h4>
			        </div>
					<ul class="sidebar-product-list">
                        @include('theme::partials._product_vertical', ['products' => $linked_items])
					</ul>
		        </div><!-- /.col-md-2 -->
	        @endif

	        <div class="col-md-{{$linked_items->count() ? '9' : '12'}}" id="product_desc_section">
          		<div role="tabpanel">
	              	<ul class="nav nav-tabs" role="tablist">
						<li role="presentation" class="active">
							<a href="#desc_tab" aria-controls="desc_tab" role="tab" data-toggle="tab" aria-expanded="true">@lang('theme.product_desc')</a>
						</li>
						<li role="presentation">
							<a href="#seller_desc_tab" aria-controls="seller_desc_tab" role="tab" data-toggle="tab" aria-expanded="false">@lang('theme.product_desc_seller')</a>
						</li>
						<li role="presentation">
							<a href="#reviews_tab" aria-controls="reviews_tab" role="tab" data-toggle="tab" aria-expanded="false">@lang('theme.customer_reviews')</a>
						</li>
					</ul><!-- /.nav-tab -->

              		<div class="tab-content">
                  		<div role="tabpanel" class="tab-pane fade active in" id="desc_tab">

							{!! $item->product->description !!}

							<div class="clearfix space30"></div>

                		  	<hr class="style4 muted"/>

                		  	<h4>{{ trans('theme.technical_details') }}: </h4>

							<table class="table table-striped noborder">
								<tbody>
						            @if($item->product->brand)
						                <tr class="noborder">
						                	<th class="text-right noborder">{{ trans('theme.brand') }}: </th>
						                	<td class="noborder" style="width: 75%;">{{ $item->product->brand }}</td>
						                </tr>
						            @endif

						            @if($item->product->model_number)
										<tr class="noborder">
											<th class="text-right noborder">{{ trans('theme.model_number') }}:</th>
											<td class="noborder" style="width: 75%;">{{ $item->product->model_number }}</td>
										</tr>
									@endif

						            @if($item->product->gtin_type && $item->product->gtin )
						                <tr class="noborder">
						                	<th class="text-right noborder">{{ $item->product->gtin_type }}: </th>
						                	<td class="noborder" style="width: 75%;">{{ $item->product->gtin }}</td>
						                </tr>
						            @endif

						            @if($item->product->mpn)
						                <tr class="noborder">
						                	<th class="text-right noborder">{{ trans('theme.mpn') }}: </th>
						                	<td class="noborder" style="width: 75%;">{{ $item->product->mpn }}</td>
						                </tr>
						            @endif

						            @if($item->sku)
						                <tr class="noborder">
						                	<th class="text-right noborder">{{ trans('theme.sku') }}: </th>
						                	<td class="noborder" id="item_sku" style="width: 75%;">{{ $item->sku }}</td>
						                </tr>
						            @endif

						            @if(optional($item->product->manufacturer)->name)
						                <tr class="noborder">
						                	<th class="text-right noborder">{{ trans('theme.manufacturer') }}: </th>
						                	<td class="noborder" style="width: 75%;">{{ $item->product->manufacturer->name }}</td>
						                </tr>
						            @endif

						            @if($item->product->origin)
						                <tr class="noborder">
						                	<th class="text-right noborder">{{ trans('theme.origin') }}: </th>
						                	<td class="noborder" style="width: 75%;">{{ $item->product->origin->name }}</td>
						                </tr>
						            @endif

						            @if($item->min_order_quantity)
						                <tr class="noborder">
						                	<th class="text-right noborder">{{ trans('theme.min_order_quantity') }}: </th>
						                	<td class="noborder" id="item_min_order_qtt" style="width: 75%;">{{ $item->min_order_quantity }}</td>
						                </tr>
						            @endif

						            @if($item->shipping_weight)
						                <tr class="noborder">
						                	<th class="text-right noborder">{{ trans('theme.shipping_weight') }}: </th>
						                	<td class="noborder" id="item_shipping_weight" style="width: 75%;">{{ $item->shipping_weight . ' ' . config('system_settings.weight_unit') }}</td>
						                </tr>
						            @endif

						            @if($item->product->created_at)
										<tr class="noborder">
											<th class="text-right noborder">{{ trans('theme.first_listed_on', ['platform' => get_platform_title()]) }}:</th>
											<td class="noborder" style="width: 75%;">{{ $item->product->created_at->toFormattedDateString() }}</td>
										</tr>
						            @endif
								</tbody>
							</table>
		                </div>

         		        <div role="tabpanel" class="tab-pane fade" id="seller_desc_tab">

         		        	<div id="seller_seller_desc">
	                		  	{!! $item->description !!}
         		        	</div>

                		  	@if($item->shop->config->show_shop_desc_with_listing)
        	        		  	@if($item->description)
		                		  	<br/><br/><hr class="style4 muted"/>
	                		  	@endif
	                		  	<br/>
	                		  	<h4>{{ trans('theme.seller_info') }}: </h4>
	                		  	{!! $item->shop->description !!}
                		  	@endif

                		  	@if($item->shop->config->show_refund_policy_with_listing && $item->shop->config->return_refund)
	                		  	<br/><br/><hr class="style4 muted"/><br/>

	                		  	<h4>{{ trans('theme.return_and_refund_policy') }}: </h4>
	                		  	{!! $item->shop->config->return_refund !!}
                		  	@endif
	                  	</div>

		              	<div role="tabpanel" class="tab-pane fade" id="reviews_tab">
                      		<div class="reviews-tab">
	                      		@forelse($item->feedbacks->sortByDesc('created_at') as $feedback)
									<p>
										<b>{{ optional($feedback->customer)->getName() }}</b>

										<span class="pull-right small">
											<b class="text-success">@lang('theme.verified_purchase')</b>
											<span class="text-muted"> | {{ $feedback->created_at->diffForHumans() }}</span>
										</span>
									</p>

									<p>{{ $feedback->comment }}</p>

			                        @include('theme::layouts.ratings', ['ratings' => $feedback->rating])

			                        @unless($loop->last)
										<div class="sep"></div>
			                        @endunless
	                          	@empty
	                          		<div class="space20"></div>
	                          		<p class="lead text-center text-muted">@lang('theme.no_reviews')</p>
	                          	@endforelse
	                      	</div>
    	              	</div>
	              	</div><!-- /.tab-content -->
          		</div><!-- /.tabpanel -->
        	</div><!-- /.col-md-9 -->
      	</div><!-- /.row -->
    </div><!-- /.container -->
</section>

<div class="clearfix space20"></div>


