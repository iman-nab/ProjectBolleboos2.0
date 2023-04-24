@extends('layouts.app')
@section('content')
 <main class="container">
    <section>
        <form method="post" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="titlebar">
                <h1>Edit Product</h1>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="card">
                <div>
                    <label>Name</label>
                    <input type="text" name="name" value="{{ $product->name}}">
                    <label>Description (optional)</label>
                    <textarea cols="10" rows="5" name="description" value="{{ $product->description}}" >{{ $product->description}}</textarea>
                    <label>Add Image</label>
                    <img src="{{ asset('images/'.$product->image) }}" alt="" class="img-product" id="file-preview" />
                    <input type="hidden" name="hidden_product_image" value={{ $product->image}}>
                    <input type="file" name="image" accept="image/*" onchange="showFile(event)">
                </div>
                <div>
                    <label>Category</label>
                    <select  name="category" >
                        @foreach (json_decode('{"Proudct1":"Product 1","Product2":"Product 2","Product3":"Product 3","Product4":"Product 4"}',true ) as $optionKey => $optionValue)
                        <option value="{{$optionKey}}" {{ (isset($product->category) && $product->category == $optionKey) ? 'selected' : '' }} >{{ $optionValue }}</option>
                        @endforeach
                    </select>
                    <hr>
                    <label>Inventory</label>
                    <input type="text" name="quantity" value="{{ $product->quantity}}">
                    <hr>
                    <label>Price</label>
                    <input type="text" name="price" value="{{ $product->price}}">
                </div>
            </div>
            <div class="titlebar">
                <h1></h1>
                <input type="hidden" name="hidden_id" value="{{ $product->id}}">
                <button>Save</button>
            </div>
        </form>
    </section>
 </main>
 <script>
    function showfile(event){
        var input = event.target;
        var reader = new FileReader();
        reader.onload = function(){
            var dataURL = reader.result;
            var output = document.getElementById('file-preview');
            output.src = dataURL;
        };
        reader.readAsDataURL(input.files[0]);
    }
    </script>
@endsection