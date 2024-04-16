@extends('layouts.admin')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-11">
                <h1>Plants</h1>
                <button type="button" class=" mb-3 btn btn-dark" data-toggle="modal" data-target="#staticBackdrop">
                    Create a new Plant
                </button>

                <!-- Creating Modal -->
                <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
                    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">New Plant</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('plants.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Plant name"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="description" class="form-control"
                                            placeholder="Plant description" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="file" name="cover" class="form-control-file">
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
                <!-- Edit Plant Modal -->
                <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
                    role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Plant</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="editForm" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')@csrf
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Plant name"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <input type="text" name="description" class="form-control"
                                            placeholder="Plant description" required>
                                    </div>
                                    <div class="form-group">
                                        <input type="file" name="cover" class="form-control-file">
                                    </div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-sm btn-dark" id="saveChangesBtn">Save Changes</button>
                                <button type="button" class="btn btn-sm btn-light" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th> Name</th>
                            <th> Description</th>
                            <th> Cover</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plants as $plant)
                            <tr data-plant-id="{{ $plant->id }}">
                                <td class=" plant-name">{{ $plant->name }}</td>
                                <td class=" plant-description">{{ $plant->description }}</td>
                                <td class="plant-cover"><img src="{{ asset($plant->cover) }}" alt="Plant Cover"
                                        style="max-width: 100px;"></td>
                                <td class="d-flex">
                                    <button type="button" class="btn btn-sm btn-light btn-edit" data-toggle="modal"
                                        data-target="#editModal">
                                        Edit
                                    </button>
                                    <form action="{{ route('plants.destroy', ['plant' => $plant->id]) }}" method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class=" ml-3 btn btn-sm btn-dark">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('.btn-edit').on('click', function() {
                var PlantName = $(this).closest("tr").find(".plant-name").text();
                $('#editModal input[name="name"]').val(PlantName);
                var PlantDescription = $(this).closest("tr").find(".plant-description").text();
                $('#editModal input[name="description"]').val(PlantDescription);
                var PlantCover = $(this).closest("tr").find(".plant-cover").text();
                $('#editModal input[name="cover"]').val(PlantCover);
                var PlantId = $(this).closest('tr').data('plant-id');
                $('#editForm').attr('action', '/plants/' + PlantId);
                $('#editModal').modal('show');
            });
            $('#saveChangesBtn').on('click', function() {
                $('#editForm').submit();
            });
        });
    </script>
@endsection
