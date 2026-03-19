@extends('layouts.app')


@section('header-button')
  @include('layouts.header_button')
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')
<div class="purchase">

  {{-- 左エリア --}}
  <div class="purchase__left">

    {{-- 商品情報 --}}
    <div class="purchase__item">
      <div class="purchase__image">
        <img src="{{ asset('storage/' . $item->item_image) }}" alt="商品画像">
      </div>

      <div class="purchase__info">
        <h2>{{ $item->item_name }}</h2>
        <p class="price">¥{{ number_format($item->price) }}</p>
      </div>
    </div>

    <hr>

    <form method="POST" action="{{ route('purchase.store', $item->id) }}">
      @csrf
    {{-- 支払い方法 --}}
    <div class="purchase__section">
      <h3>支払い方法</h3>

      <select name="payment_method_id">
          <option value="">
            選択してください
          </option>

          @foreach($paymentMethods as $paymentmethod)
              <option value="{{ $paymentmethod->id }}">
                  {{ $paymentmethod->payment_method }}
              </option>
          @endforeach
      </select>
    </div>

    <hr>

    {{-- 配送先 --}}
    <div class="purchase__section">
      <div class="section__head">
        <h3>配送先</h3>
        <a href="{{ route('purchase.address.edit', $item->id) }}" class="link">変更する</a>
      </div>

      <p>
        〒 {{ session('shipping.postal_code') ?? optional($user->profile)->postal_code }}<br>
        {{ session('shipping.address') ?? optional($user->profile)->address }}<br>
        {{ session('shipping.building') ?? optional($user->profile)->building }}
      </p>
    </div>

  </div>

  {{-- 右サマリー --}}
  <div class="purchase__right">

    <div class="summary">

      <div class="summary__row">
        <span>商品代金</span>
        <span>¥{{ number_format($item->price) }}</span>
      </div>

      <div class="summary__row">
        <span>支払い方法</span>
        <span>{{ number_format($item->price) }}</span>
      </div>

    </div>

      <button class="btn-buy">購入する</button>
    </form>

  </div>

</div>

@endsection