@extends('auth.layouts.master')

@isset($product)
    @section('title', 'Редактировать Продукт ' . $product->name)
@else
    @section('title', 'Создать категорию')
@endisset

@section('content')
    <div class="col-md-12">
        @isset($product)
            <h1>Редактировать Продукт <b>{{ $product->name }}</b></h1>
        @else
            <h1>Добавить Продукт</h1>
        @endisset

        <form method="POST" enctype="multipart/form-data"
              @isset($product)
              action="{{ route('product.update', $product) }}"
              @else
              action="{{ route('product.store') }}"
            @endisset
        >
            <div>
                @isset($product)
                    @method('PUT')
                @endisset
                @csrf
                <div class="input-group row">
                    <label for="code" class="col-sm-2 col-form-label">Код: </label>
                    <div class="col-sm-6">
                        @error('code')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input type="text" class="form-control" name="code" id="code"
                               value="{{ old('code', isset($product) ? $product->code : null) }}">
                    </div>
                </div>
                <br>
                <div class="input-group row">
                    <label for="name" class="col-sm-2 col-form-label">Название: </label>
                    <div class="col-sm-6">
                        @error('name')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <input type="text" class="form-control" name="name" id="name"
                               value="@isset($product){{ $product->name }}@endisset">
                    </div>
                </div>

                <br>
                <div class="input-group row">
                    <label for="category_id" class="col-sm-2 col-form-label">Категория: </label>
                    <div class="col-sm-6">
                        <select name="category_id" id="category_id" class="form-control">
                            @foreach($categories as $category)
                            <option value="{{ $category->id }}" @isset($product)
                            @if($category->id == $product->category_id)
                            selected
                                @endif
                                @endisset>{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <br>
                <div class="input-group row">
                    <label for="description" class="col-sm-2 col-form-label">Описание: </label>
                    <div class="col-sm-6">
                        @error('description')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <textarea name="description" id="description" cols="72"
                                  rows="7">@isset($product){{ $product->description }}@endisset</textarea>
                    </div>
                </div>
                <br>

                <div class="input-group row">
                    <label for="description" class="col-sm-2 col-form-label">Описание en: </label>
                    <div class="col-sm-6">
                        @error('description_en')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <textarea name="description_en" id="description_en" cols="72"
                                  rows="7">@isset($product){{ $product->description_en }}@endisset</textarea>
                    </div>
                </div>
                <br>

                <div class="input-group row">
                    <label for="price" class="col-sm-2 col-form-label">Цена: </label>
                    <div class="col-sm-6">
                        @error('price')
                        <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                        <textarea name="price" id="price" cols="72"
                                  rows="2">@isset($product){{ $product->price }}@endisset</textarea>
                    </div>
                </div>
                <br>



                    <div class="input-group row">
                    <label for="image" class="col-sm-2 col-form-label">Картинка: </label>
                    <div class="col-sm-10">
                        <label class="btn btn-default btn-file">
                            Загрузить <input type="file" style="display: none;" name="image" id="image">
                        </label><img src="@isset($product){{ Storage::url($product->image) }}@endisset"
                                     height="240px">
                        </label>
                    </div>
                </div>
                <button class="btn btn-success">Сохранить</button>
            </div>
        </form>
    </div>
@endsection
