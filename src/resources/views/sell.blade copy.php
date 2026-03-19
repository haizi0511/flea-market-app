@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/sell.css') }}">
@endsection

@section('content')
<div class="sell-form__content">
  <div class="sell-form__inner">
    <div class="sell-form__heading">
      <h2>商品の出品</h2>
    </div>
    <form class="form" action="/sell" method="post">
      @csrf
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">商品画像</span>
        </div>

        <div class="form__group-content">
          <div class="sell__image">

          </div>
          <div class="form__error">
            @error('item_image')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      <h3>商品の詳細</h3>
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">カテゴリー</span>
        </div>
          <div class="category-tags">
            @foreach($categories as $category)
              <label class="category-tag">
                <input class="category-tag__input" type="radio" name="category_id" value="{{ $category->id }}" >
                <span class="category-tag__text">
                  {{ $category->name }}
                </span>
              </label>
            @endforeach
          </div>
          <div class="form__error">
            @error('building')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">商品の状態</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--text">
            <select name="condition_id">
                <option value="">
                  選択してください
                </option>

                @foreach($conditions as $condition)
                    <option value="">
                        {{ $condition->condition}}
                    </option>
                @endforeach
            </select>
          </div>
          <div class="form__error">
            @error('condition_id')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      <h3>商品と説明</h3>
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">商品名</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--text">
            <input type="text" name="item_name" >
          </div>
          <div class="form__error">
            @error('item_name')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">ブランド名</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--text">
            <input type="text" name="brand_name" >
          </div>
          <div class="form__error">
            @error('brand_name')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">商品の説明</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--text">
            <textarea name="item_detail" ></textarea>
          <div class="form__error">
            @error('item_detail')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">販売価格</span>
        </div>
        <div class="form__group-content">
          <div class="price-input">
            <span class="price-input__yen">¥</span>
            <input type="text" name="price">
          </div>
          <div class="form__error">
            @error('price')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      <div class="form__button">
        <button class="form__button-submit" type="submit">出品する</button>
      </div>
    </form>
  </div>
</div>
@endsection
