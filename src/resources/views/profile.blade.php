@extends('layouts.app')

@section('header-button')
  @include('layouts.header_button')
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
@endsection

@section('content')
<div class="profile-form__content">
  <div class="profile-form__heading">
    <h2>プロフィール設定</h2>
  </div>

  @if($profile)
      <form class="form" method="POST" action="/mypage/profile" enctype="multipart/form-data" >
          @csrf
          @method('PUT')
  @else
      <form class="form" method="POST" action="/mypage/profile" enctype="multipart/form-data">
          @csrf
  @endif

    <div class="profile-form__image-area">
      <div class="profile-form__image">
        <img src="{{ $user->profile_image ?? asset('images/default.png') }}" id="preview" >
      </div>
      <div class="profile-form__image-button">
        <label for="image-upload" class="image-upload-label">
          画像を選択する
        </label>
        <input type="file" id="image-upload" name="profile_image" accept="image/*" hidden >
      </div>
    </div>

      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">ユーザー名</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--text">
            <input type="text" name="name" value="{{ $user -> name }}" />
          </div>
          <div class="form__error">
            @error('name')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">郵便番号</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--text">
            <input type="text" name="postal_code" value="{{ old('postal_code', optional($profile)->postal_code) }}" />
          </div>
          <div class="form__error">
            @error('postal_code')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">住所</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--text">
            <input type="text" name="address" value="{{ old('address', optional($profile)->address) }}" />
          </div>
          <div class="form__error">
            @error('address')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      <div class="form__group">
        <div class="form__group-title">
          <span class="form__label--item">建物名</span>
        </div>
        <div class="form__group-content">
          <div class="form__input--text">
            <input type="text" name="building" value="{{ old('building', optional($profile)->building) }}" />
          </div>
          <div class="form__error">
            @error('building')
            {{ $message }}
            @enderror
          </div>
        </div>
      </div>
      <div class="form__button">
        <button class="form__button-submit" type="submit">更新する</button>
      </div>
  </form>
</div>
@endsection
