@extends('layouts.app')

@section('header-button')
  @include('layouts.header_button')
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset('css/purchase.css') }}">
@endsection

@section('content')

<form method="POST" action="{{ route('purchase.store', $item->id) }}">
  @csrf

  <input type="hidden" name="postal_code"
        value="{{ session('shipping.postal_code') ?? optional($user->profile)->postal_code }}">

  <input type="hidden" name="address"
        value="{{ session('shipping.address') ?? optional($user->profile)->address }}">

  <input type="hidden" name="building"
        value="{{ session('shipping.building') ?? optional($user->profile)->building }}">

  <div class="purchase">

    <div class="purchase__left">

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

      <div class="purchase__section">
        <h3>支払い方法</h3>

        <select name="payment_methods_id">
          <option value="">選択してください</option>

          @foreach($paymentMethods as $paymentmethod)
            <option value="{{ $paymentmethod->id }}">
              {{ $paymentmethod->payment_method }}
            </option>
          @endforeach
        </select>
      </div>

      <hr>

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

    </div>

  </div>

</form>

@endsection