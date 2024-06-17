@extends('layouts.admin')
@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center mt-5">
            <div class="col-md-11">
                <h1>Withdrawal Requests</h1>
                <div class="modal fade" id="editModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
                    aria-labelledby="editModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Withdrawal Request</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="editForm" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <select class="form-control" name="status" id="status">
                                            @foreach ($status as $oneStatus)
                                                @if ($oneStatus == 'canceled')
                                                @else
                                                    <option value="{{ $oneStatus }}">{{ $oneStatus }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn rounded btn-sm btn-light" id="saveChangesBtn">Save
                                    Changes</button>
                                <button type="button" class="btn rounded btn-sm btn-dark"
                                    data-dismiss="modal">Close</button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>

                <table class="table">
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Details</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($withdrawalRequests as $withdrawalRequest)
                            <tr data-withdrawal-request-id="{{ $withdrawalRequest->id }}">
                                <td class="withdrawal-request-user_id">{{ $withdrawalRequest->user_id }}</td>
                                <td class="withdrawal-request-amount">{{ $withdrawalRequest->amount }}</td>
                                <td class="withdrawal-request-method">{{ $withdrawalRequest->method }}</td>
                                <td class="withdrawal-request-details">{{ $withdrawalRequest->details }}</td>
                                <td class="withdrawal-request-status">{{ $withdrawalRequest->status }}</td>
                                <td class="d-flex">
                                    <button type="button" class="btn rounded btn-sm btn-light btn-edit" data-toggle="modal"
                                        data-target="#editModal">
                                        Edit
                                    </button>
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
                var withdrawalRequestId = $(this).closest('tr').data('withdrawal-request-id');
                var withdrawalRequestUserId = $(this).closest("tr").find(".withdrawal-request-user_id")
                    .text();
                var withdrawalRequestAmount = $(this).closest("tr").find(".withdrawal-request-amount")
                    .text();
                var withdrawalRequestMethod = $(this).closest("tr").find(".withdrawal-request-method")
                    .text();
                var withdrawalRequestDetails = $(this).closest("tr").find(".withdrawal-request-details")
                    .text();
                var withdrawalRequestStatus = $(this).closest("tr").find(".withdrawal-request-status")
                    .text();

                $('#editForm').attr('action', '/withdrawal-requests/' + withdrawalRequestId);
                $('#editModal select[name="user_id"]').val(withdrawalRequestUserId);
                $('#editModal input[name="amount"]').val(withdrawalRequestAmount);
                $('#editModal input[name="method"]').val(withdrawalRequestMethod);
                $('#editModal input[name="details"]').val(withdrawalRequestDetails);
                $('#editModal input[name="status"]').val(withdrawalRequestStatus);

                $('#editModal').modal('show');
            });
            $('#saveChangesBtn').on('click', function() {
                $('#editForm').submit();
            });
        });
    </script>
@endsection
