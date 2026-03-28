@extends('layouts.app')

@section('header-button')
  @include('layouts.header_button')
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endsection

@section('content')

<div class="profile">

  <div class="profile__image">
    <img src="{{ asset('images/default.png') }}" >
  </div>

  <div class="profile__name">
    {{ $user->name }}
  </div>

  <a href="/mypage/profile" class="profile__edit">
    プロフィールを編集
  </a>

</div>

<div class="mypage">
  <div class="mypage__tabs">
    <a href="/mypage?tab=sell" class="mypage__tab {{ $tab === 'sell' ? 'is-active' : '' }}">
      出品した商品
    </a>

    <a href="/mypage?tab=buy" class="mypage__tab {{ $tab === 'buy' ? 'is-active' : '' }}">
      購入した商品
    </a>
  </div>

    {{-- 商品一覧 --}}
  <div class="items__grid">
    @foreach ($items as $item)

    {{-- カード1 --}}
        <a href="/item/{{ $item->id }}" class="item-card">
          <div class="item-card__image">
            <img src="{{ $item->item_image }}" alt="商品画像">

          </div>

          <div class="item-card__name">
            {{ $item->item_name }}
          </div>
        </a>
    @endforeach
  </div>

</div>

@endsection