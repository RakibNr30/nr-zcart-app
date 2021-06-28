<div class="product-info">
  @if($item->product->manufacturer->slug)
    <a href="{{ route('show.brand', $item->product->manufacturer->slug) }}" class="product-info-seller-name">{!! $item->product->manufacturer->name !!}</a>
  @else
    <a href="{{ route('show.store', $item->shop->slug) }}" class="product-info-seller-name">
      {!! $item->shop->getQualifiedName() !!}
    </a>
  @endif

  <h5 class="product-info-title space10" data-name="product_name">{!! $item->title !!}</h5>

  @if($item->feedbacks_count)
    @include('theme::layouts.ratings', ['ratings' => $item->feedbacks->avg('rating'), 'count' => $item->feedbacks_count])
  @endif

  <div class="row">
    <div class="col-sm-12">
      @include('theme::layouts.pricing', ['item' => $item])
    </div>
  </div>

  <div class="row">
    <div class="col-6 col-xs-12 nopadding-right">
        <div class="product-info-availability space10">@lang('theme.availability'):
          <span style="font-weight:bold;">{{ $item->stock_quantity > 0 ? trans('theme.in_stock') : trans('theme.out_of_stock') }}</span>
        </div>
    </div>
    <div class="col-6 col-xs-12 nopadding-left">
        <div class="product-info-condition space10">

          @lang('theme.condition'): <span><b id="item_condition">{!! $item->condition !!}</b></span>

          @if($item->condition_note)
            <sup><i class="fas fa-question" id="item_condition_note" data-toggle="tooltip" title="{!! $item->condition_note !!}" data-placement="top"></i></sup>
          @endif
        </div>
    </div>
  </div><!-- /.row -->

  <div class="row">
    <div class="col-4 col-xs-12">
      <a href="javascript:void(0)" data-link="{{ route('wishlist.add', $item) }}" class="btn btn-link add-to-wishlist">
        <i class="far fa-heart"></i> @lang('theme.button.add_to_wishlist')
      </a>
    </div>
    
    <div class="col-4 col-xs-12">
      <a href="{{ url('page/contact-us') }}" class="btn btn-link">
        <i class="far fa-flag"></i> Report Product
      </a>
    </div>
    
    <div class="col-4 col-xs-12">
      @if('quickView.product' == Route::currentRouteName())
        <a href="{{ route('show.store', $item->shop->slug) }}" class="btn btn-link">
          <i class="far fa-list"></i> @lang('theme.more_items_from_this_seller', ['seller' => $item->shop->name])
        </a>
      {{-- @elseif('quickView.product' == Route::currentRouteName()) --}}
        {{-- <a href="{{ route('show.brand', $item->product->manufacturer->slug) }}" class="product-info-seller-name"> @lang('theme.more_items_from_this_seller', ['seller' => $item->product->manufacturer->name])</a> --}}
      @else
        @if(Auth::guard('customer')->check())
            <a href="javascript:void(0);" class="btn btn-link" data-toggle="modal" data-target="#contactSellerModal">
              <i class="far fa-envelope"></i> @lang('theme.button.contact_seller')
            </a>
        @else
            <a href="{{ url('customer/login') }}" class="btn btn-link">
              <i class="far fa-envelope"></i> @lang('theme.button.contact_seller')
            </a>
        @endif
      @endif
    </div>
  </div><!-- /.row -->
</div><!-- /.product-info -->

@include('theme::layouts.share_btns')
