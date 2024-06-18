@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-11">
                <h1>Plants</h1>
                <button type="button" class="mb-3 btn btn-sm btn-dark" data-toggle="modal" data-target="#staticBackdrop">
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
                                <form id="createForm" action="{{ route('plants.store') }}" method="post"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Plant name">
                                    </div>
                                    <div class="form-group">
                                        <textarea name="description" id="description_create" class="form-control" style="min-height: 150px"
                                            placeholder="Plant description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <input id="file" type="file" name="cover" class="form-control-file">
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
                                    @method('PUT')
                                    <div class="form-group">
                                        <input type="text" name="name" class="form-control" placeholder="Plant name"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="description" id="description_edit" class="form-control" style="min-height: 150px"
                                            placeholder="Plant description" required></textarea>
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
                            <th> Cover</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($plants as $plant)
                            <tr data-plant-id="{{ $plant->id }}">
                                <td class=" plant-name">{{ $plant->name }}</td>
                                <td class="plant-cover"><img src="{{ asset($plant->cover) }}" alt="Plant Cover"
                                        style="max-width: 100px;"></td>
                                <td class="d-flex">
                                    <div class="">
                                        <a class="btn btn-sm btn-light btn-edit"
                                            href="{{ route('plants.show', ['plant' => $plant->id]) }}">
                                            Show</a>

                                    </div>
                                    <div class="">

                                        <form action="{{ route('plants.destroy', ['plant' => $plant->id]) }}"
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

    <!-- CKEditor Script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
    <script>
        // Initialize CKEditor for create form
        let createEditor;
        ClassicEditor
            .create(document.querySelector('#description_create'))
            .then(editor => {
                createEditor = editor;
                // Sync CKEditor data before form submission
                document.getElementById('createForm').addEventListener('submit', function() {
                    document.querySelector('#description_create').value = createEditor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });

        // Initialize CKEditor for edit form
        let editEditor;
        ClassicEditor
            .create(document.querySelector('#description_edit'))
            .then(editor => {
                editEditor = editor;
                // Sync CKEditor data before form submission
                document.getElementById('editForm').addEventListener('submit', function() {
                    document.querySelector('#description_edit').value = editEditor.getData();
                });
            })
            .catch(error => {
                console.error(error);
            });

        $(document).ready(function() {
            $('.btn-edit').on('click', function() {
                var plantRow = $(this).closest("tr");
                var plantName = plantRow.find(".plant-name").text();
                var plantDescription = plantRow.find(".plant-description").html();
                var plantId = plantRow.data('plant-id');

                $('#editModal input[name="name"]').val(plantName);
                editEditor.setData(plantDescription);

                $('#editForm').attr('action', '/plants/' + plantId);
                $('#editModal').modal('show');
            });

            $('#saveChangesBtn').on('click', function() {
                // Sync the CKEditor data with the textarea before submitting
                document.querySelector('#description_edit').value = editEditor.getData();
                $('#editForm').submit();
            });
        });
    </script>
@endsection
