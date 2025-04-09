<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品一覧ページ</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/detail.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}" />
</head>

<body>
    @include('components.header')
    <div class="all-contents">
        <form action="/products/update" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <div class="top-contents">
                <div class="left-content">
                    <p><span class="span-item">商品一覧></span>{{$product->name}}</p>
                    <img src="{{ asset($product->image) }}" alt="商品画像" class="img-content" />
                </div>
                <div class="right-content">
                    <label class="name-label">商品名</label>
                    <input type="text" placeholder="{{$product->name}}" name="product_name" class="text">
                    @error('product_name')
                        <span class="input_error">
                            <p class="input_error_message">{{$errors->first('product_name')}}</p>
                        </span>
                    @enderror
                    <label class="price-label">値段</label>
                    <input type="text" placeholder="{{$product->price}}" name="product_price" class="text">
                    @error('product_price')
                        <span class="input_error">
                            <p class="input_error_message">{{$errors->first('product_price')}}</p>
                        </span>
                    @enderror
                    <label class="season-label">季節</label>
                    @foreach ($seasons as $season)
                        <label for="season">{{$season->name}}</label>
                        <input type="checkbox" id="season" value="{{$season->id}}" {{in_array($season->id, $product->seasons->pluck('id')->toArray()) ? 'checked' : ''}} name="product_season[]">
                    @endforeach
                    @error('product_season')
                        <span class="input_error">
                            <p class="input_error_message">{{$errors->first('product_season')}}</p>
                        </span>
                    @enderror
                </div>
            </div>
            <div class="under-content">
                <input type="file" id="product_image" class="image" name="product_image">
                @error('product_image')
                    <span class="input_error">
                        <p class="input_error_message">{{$errors->first('product_image')}}</p>
                    </span>
                @enderror
                <label class="description-label">商品説明</label>
                <textarea cols="30" rows="5" name="product_description"
                    class="product-description">{{$product->description}}</textarea>
                @error('product_description')
                    <span class="input_error">
                        <p class="input_error_message">{{$errors->first('product_description')}}</p>
                    </span>
                @enderror
                <div class="button-content">
                    <a href="/products" class="back">戻る</a>
                    <button type="submit" class="button-change">変更を保存</button>
                    <div class="trash-can-content">
                        <a href="/products/{{$product->id}}/delete">
                            <img src="{{ asset('/images/trash-can.png') }}" alt="ゴミ箱の画像" class="img-trash-can" />
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</body>

</html>