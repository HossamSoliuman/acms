@extends('layouts.admin')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-11">
                <h1>{{ $plant->name }} Plant</h1>
                <button type="button" class="btn btn-sm btn-light btn-edit" data-toggle="modal" data-target="#editModal">
                    Edit
                </button>

                <!-- Display Plant Details -->
                <div class="card mt-4">
                    <div class="card-body">
                        <h3>Description</h3>

                        <div>{!! $plant->description !!}</div>
                        <h3 class="mt-4">Cover</h3>
                        <img src="{{ asset($plant->cover) }}" alt="Plant Cover" style="max-width: 100%; height: auto;">
                    </div>
                </div>

                <!-- Edit Plant Modal -->
                <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
                    role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
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
                                            value="{{ $plant->name }}" required>
                                    </div>
                                    <div class="form-group">
                                        <textarea name="description" id="description_edit" class="form-control" style="min-height: 300px"
                                            placeholder="Plant description" required>{{ $plant->description }}</textarea>
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

            </div>
        </div>
    </div>

    <!-- CKEditor Script -->
    <script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
    <script>
        // Initialize CKEditor for edit form
        let editEditor;
        ClassicEditor
            .create(document.querySelector('#description_edit'), {
                mediaEmbed: {
                    previewsInData: true
                }
            })
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
                var plantDescription = `{!! $plant->description !!}`;
                var plantId = {{ $plant->id }};

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
        document.querySelectorAll('div[data-oembed-url]').forEach(element => {
            $(element).addClass("parent_container_iframe");

            let child = element.firstChild;
            $(child).addClass("video_container_iframe");

            let iframe = child.firstChild;
            $(iframe).addClass("video_iframe");
        });
    </script>
@endsection
