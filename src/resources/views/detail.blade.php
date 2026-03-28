@extends('layouts.app')


@section('header-button')
  @include('layouts.header_button')
@endsection

@section('css')
  <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
@endsection

@section('content')
<div class="detail">
  <div class="detail__left">
    <div class="detail__image">
      <img src="{{ $item->item_image ?? '/img/noimage.png' }}" alt="商品画像">
    </div>
  </div>

  <div class="detail__right">

    <h1 class="detail__title">
      {{ $item->item_name }}
    </h1>

    <div class="detail__brand">
      {{ $item->brand_name }}
    </div>

    <div class="detail__price">
      ¥{{ number_format($item->price) }} <span>（税込）</span>
    </div>

    <div class="detail__meta">
      <div class="meta-item">
        <form method="post" action="/item/{{ $item->id }}/like" class="detail__meta-mylist">
            @csrf

            @if($isInMylist)
              @method('DELETE')
            @endif

          <button type="submit" class="like-button">
            @if($isInMylist)
              <img src="{{ asset('img/ハートロゴ_ピンク.png') }}" alt="♥" >
            @else
              <img src="{{ asset('img/ハートロゴ_デフォルト.png') }}" alt="♡" >
            @endif
          </button>
        </form>

        <span class="like-count">
          {{ $item->mylists->count() }}
        </span>
      </div>

      <div class="meta-item">
        <div class="detail__meta-comment">
            <img src="{{ asset('img/ふきだしロゴ.png') }}" alt="💬">

        <span class="comment-count">
          {{ $item->comments->count() }}
        </span>

        </div>
      </div>
    </div>

    @if(!$item->purchase)
      <a href="/purchase/{{ $item->id }}" class="btn-buy">
        購入手続きへ
      </a>
    @else
      <div class="btn-sold">SOLD</div>
    @endif

    <h2 class="sec">商品説明</h2>
    <div class="detail__desc">
      {!! nl2br(e($item->item_detail)) !!}
    </div>

    <h2 class="sec">商品の情報</h2>

    <div class="info">
      <div class="info__row">
        <span>カテゴリー</span>
        @foreach($item->categories as $category)
          <span class="tag">{{ $category->name }}</span>
        @endforeach
      </div>

      <div class="info__row">
        <span>商品の状態</span>
        <span>{{  $item->condition  }}</span>
      </div>
    </div>

    <h2 class="sec">コメント</h2>

    @foreach($item->comments as $comment)
      <div class="comment">
        <div class="comment__icon">
          <img src="{{ $comment->user->profile->profile_image ?? '/img/noicon.png' }}">
        </div>
        <div class="comment__body">
          <div class="comment__user">
            {{ $comment->user->name }}
          </div>
          <div class="comment__text">
            {{ $comment->comment }}
          </div>
        </div>
      </div>
    @endforeach

    @auth
    <form method="post" action="/item/{{ $item->id }}/comments" class="comment-form">
      @csrf
      <h2 class="sec">商品へのコメント</h2>
      <textarea name="comment">{{ old('comment') }}</textarea>
      <div class="comment__error">
        @error('comment')
          <p>{{ $message }}</p>
        @enderror
      </div>
      <button class="btn-buy">コメントを送信する</button>
    </form>
    @endauth

  </div>
</div>

@endsection