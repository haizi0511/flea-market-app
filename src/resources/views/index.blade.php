@extends('layouts.app')

@section('header-button')
  @include('layouts.header_button')
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset('css/index.css') }}">
@endsection

@section('content')
<div class="items__tabs">
  <a href="/" class="{{ $tab === 'recommend' ? 'active' : '' }}">
    おすすめ
  </a>

  <a href="/mylist" class="{{ $tab === 'mylist' ? 'active' : '' }}">
    マイリスト
  </a>
</div>

{{-- 商品グリッド --}}
<div class="items__grid">
  @foreach ($items as $item)

  {{-- カード1 --}}
      <a href="/item/{{ $item->id }}" class="item-card">
        <div class="item-card__image">
          <img src="{{ asset('storage/' . $item->item_image) }}" alt="商品画像">

          @if($item->purchase)
            <span class="item-card__sold">sold</span>
          @endif
        </div>

        <div class="item-card__name">
          {{ $item->item_name }}
        </div>
      </a>
  @endforeach

</div>
@endsection