@extends('layouts.admin')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-11">
                <h1>Product Details</h1>
                <button type="button" class="btn btn-sm btn-light btn-edit" data-toggle="modal" data-target="#editModal"
                    data-product-id="{{ $product->id }}" data-product-name="{{ $product->name }}"
                    data-product-description="{{ $product->description }}" data-product-price="{{ $product->price }}">
                    Edit
                </button>
                <div class="card mb-3">
                    <img style="max-width: 50%" src="{{ asset($product->cover) }}" class="card-img-top"
                        alt="{{ $product->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="card-text"><strong>Price: </strong>{{ $product->price }}</p>
                        <a href="{{ route('products.index') }}" class="btn btn-dark">Back to Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
        aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Product</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <input type="text" name="name" class="form-control" placeholder="Product name" required>
                        </div>
                        <div class="form-group">
                            <textarea name="description" class="form-control" placeholder="Product description" required style="height: 150px;"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="file" name="cover" class="form-control" placeholder="Product cover">
                        </div>
                        <div class="form-group">
                            <input type="number" name="price" class="form-control" placeholder="Product price" required>
                        </div>
                        <input type="hidden" name="product_id" id="productId">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-dark" id="saveChangesBtn">Save Changes</button>
                    <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.btn-edit').on('click', function() {
                var productId = $(this).data('product-id');
                var productName = $(this).data('product-name');
                var productDescription = $(this).data('product-description');
                var productPrice = $(this).data('product-price');

                $('#editModal input[name="name"]').val(productName);
                $('#editModal textarea[name="description"]').val(productDescription);
                $('#editModal input[name="price"]').val(productPrice);
                $('#productId').val(productId);

                $('#editForm').attr('action', '/products/' + productId);
                $('#editModal').modal('show');
            });

            $('#saveChangesBtn').on('click', function() {
                $('#editForm').submit();
            });
        });
    </script>
@endsection
