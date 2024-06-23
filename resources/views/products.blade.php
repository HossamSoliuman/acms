@extends('layouts.admin')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-11">
                <h1>Products</h1>
                <button type="button" class="mb-3 btn btn-sm btn-dark" data-toggle="modal" data-target="#staticBackdrop">
                    Create a new Product
                </button>

                <!-- Creating Modal -->
                <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
                    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">New Product</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <!-- Display Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <form action="{{ route('products.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Product name"
                                            value="{{ old('name') }}" required>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="description" class="form-control" placeholder="Product description" required style="height: 150px;">{{ old('description') }}</textarea>
                                    </div>
                                    <div class="form-group">
                                        <input type="file" name="cover" class="form-control"
                                            placeholder="Product cover" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="number" name="price" class="form-control"
                                            placeholder="Product price" value="{{ old('price') }}" required>
                                    </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-sm btn-dark">Submit</button>
                                <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Existing Products Table -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Main Image</th>
                            <th>Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr data-product-id="{{ $product->id }}">
                                <td>{{ $product->name }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($product->description, 50, '...') }}</td>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $product->cover }}" alt="Product Image" class="img-fluid product-img"
                                            style="max-width: 100px;">
                                    </div>
                                </td>
                                <td>{{ $product->price }}</td>
                                <td>
                                    <div class="d-flex">
                                        <div class="btn btn-sm btn-light">
                                            <a href="{{ route('products.show', ['product' => $product->id]) }}">View</a>
                                        </div>
                                        <form action="{{ route('products.destroy', ['product' => $product->id]) }}"
                                            method="post">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="ml-3 btn btn-sm btn-dark">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Image Modal -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img src="" alt="Product Image" id="largeImage" class="img-fluid">
                </div>
            </div>
        </div>
    </div>


@endsection
