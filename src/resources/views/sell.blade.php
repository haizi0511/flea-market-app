@extends('layouts.app')

@section('header-button')
  @include('layouts.header_button')
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell">
  <div class="sell__inner">
    <h2 class="sell__title">商品の出品</h2>

    <form class="sell-form" action="/sell" method="post" enctype="multipart/form-data">
      @csrf

      {{-- 商品画像 --}}
      <section class="sell-section">
        <h3 class="sell-section__label">商品画像</h3>

        <div class="image-box">
          <input id="item_image" class="image-box__input" type="file" name="item_image" accept="image/*">
          <label class="image-box__label" for="item_image">
            画像を選択する
          </label>
        </div>

        @error('item_image')
          <p class="error">{{ $message }}</p>
        @enderror
      </section>

      {{-- 商品の詳細 --}}
      <section class="sell-section">
        <h3 class="sell-section__title">商品の詳細</h3>

        <div class="field">
          <p class="field__label">カテゴリー</p>
          <div class="category-tags">
            @foreach($categories as $category)
              <label class="category-tag">
                <input
                  class="category-tag__input"
                  type="checkbox"
                  name="category_id[]"
                  value="{{ $category->id }}" >
                <span class="category-tag__text">{{ $category->name }}</span>
              </label>
            @endforeach
          </div>

          @error('category_id')
            <p class="error">{{ $message }}</p>
          @enderror
        </div>

        <div class="field">
          <label class="field__label" for="condition_id">商品の状態</label>
          <div class="select">
            <select name="condition">
              <option value="">選択してください</option>

              @foreach(\App\Models\Item::CONDITIONS as $condition)
                <option value="{{ $condition }}">
                  {{ $condition }}
                </option>
              @endforeach
            </select>
          </div>

          @error('condition_id')
            <p class="error">{{ $message }}</p>
          @enderror
        </div>
      </section>

      {{-- 商品名と説明 --}}
      <section class="sell-section">
        <h3 class="sell-section__title">商品名と説明</h3>

        <div class="field">
          <label class="field__label" for="item_name">商品名</label>
          <input id="item_name" class="input" type="text" name="item_name" value="{{ old('item_name') }}">
          @error('item_name')
            <p class="error">{{ $message }}</p>
          @enderror
        </div>

        <div class="field">
          <label class="field__label" for="brand_name">ブランド名</label>
          <input id="brand_name" class="input" type="text" name="brand_name" value="{{ old('brand_name') }}">
          @error('brand_name')
            <p class="error">{{ $message }}</p>
          @enderror
        </div>

        <div class="field">
          <label class="field__label" for="item_detail">商品の説明</label>
          <textarea id="item_detail" class="textarea" name="item_detail" rows="5">{{ old('item_detail') }}</textarea>
          @error('item_detail')
            <p class="error">{{ $message }}</p>
          @enderror
        </div>

        <div class="field">
          <label class="field__label" for="price">販売価格</label>
          <div class="price">
            <span class="price__yen">¥</span>
            <input id="price" class="input input--price" type="text" name="price" value="{{ old('price') }}">
          </div>
          @error('price')
            <p class="error">{{ $message }}</p>
          @enderror
        </div>
      </section>

      <button class="submit" type="submit">出品する</button>
    </form>
  </div>
</div>
@endsection