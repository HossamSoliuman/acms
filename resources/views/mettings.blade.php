@extends('layouts.admin')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-11">
                <h1>Mettings</h1>
                <button type="button" class=" mb-3 btn btn-primary" data-toggle="modal" data-target="#staticBackdrop">
                    Create a new Metting
                </button>

                <!-- Creating Modal -->
                <div class="modal fade" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
                    role="dialog" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="staticBackdropLabel">New Metting</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('mettings.store') }}" method="post">
                                    @csrf
									<div class="form-group">
										<input type="text" name="start_at" class="form-control" placeholder="Metting start_at" required>
									</div>
									<div class="form-group">
										<input type="text" name="user_id" class="form-control" placeholder="Metting user_id" required>
									</div>
									<div class="form-group">
										<input type="text" name="url" class="form-control" placeholder="Metting url" required>
									</div>
									<div class="form-group">
										<input type="text" name="rating" class="form-control" placeholder="Metting rating" required>
									</div>
									<div class="form-group">
										<input type="text" name="status" class="form-control" placeholder="Metting status" required>
									</div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Edit Metting Modal -->
                <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1"
                    role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Metting</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="editForm" method="post">
                                    @csrf
                                    @method('PUT')@csrf
									<div class="form-group">
										<input type="text" name="start_at" class="form-control" placeholder="Metting start_at" required>
									</div>
									<div class="form-group">
										<input type="text" name="user_id" class="form-control" placeholder="Metting user_id" required>
									</div>
									<div class="form-group">
										<input type="text" name="url" class="form-control" placeholder="Metting url" required>
									</div>
									<div class="form-group">
										<input type="text" name="rating" class="form-control" placeholder="Metting rating" required>
									</div>
									<div class="form-group">
										<input type="text" name="status" class="form-control" placeholder="Metting status" required>
									</div>
                                </form>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="saveChangesBtn">Save Changes</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
							<th> Start_at</th>
							<th> User_id</th>
							<th> Url</th>
							<th> Rating</th>
							<th> Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($mettings as $metting)
                            <tr data-metting-id="{{ $metting->id }}">
							<td class=" metting-start_at">{{ $metting->start_at }}</td>
							<td class=" metting-user_id">{{ $metting->user_id }}</td>
							<td class=" metting-url">{{ $metting->url }}</td>
							<td class=" metting-rating">{{ $metting->rating }}</td>
							<td class=" metting-status">{{ $metting->status }}</td>
                                <td class="d-flex">
                                    <button type="button" class="btn btn-warning btn-edit" data-toggle="modal"
                                        data-target="#editModal">
                                        Edit
                                    </button>
                                    <form action="{{ route('mettings.destroy', ['metting' => $metting->id]) }}"
                                        method="post">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class=" ml-3 btn btn-danger">Delete</button>
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
				var MettingStart_at = $(this).closest("tr").find(".metting-start_at").text();
				$('#editModal input[name="start_at"]').val(MettingStart_at);
				var MettingUser_id = $(this).closest("tr").find(".metting-user_id").text();
				$('#editModal input[name="user_id"]').val(MettingUser_id);
				var MettingUrl = $(this).closest("tr").find(".metting-url").text();
				$('#editModal input[name="url"]').val(MettingUrl);
				var MettingRating = $(this).closest("tr").find(".metting-rating").text();
				$('#editModal input[name="rating"]').val(MettingRating);
				var MettingStatus = $(this).closest("tr").find(".metting-status").text();
				$('#editModal input[name="status"]').val(MettingStatus);
                var MettingId = $(this).closest('tr').data('metting-id');
                $('#editForm').attr('action', '/mettings/' + MettingId);
                $('#editModal').modal('show');
            });
            $('#saveChangesBtn').on('click', function() {
                $('#editForm').submit();
            });
        });
    </script>
@endsection
