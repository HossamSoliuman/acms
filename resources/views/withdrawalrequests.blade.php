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
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form id="editForm" method="post" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="verified">Verify</option>
                                            <option value="failed">Failed</option>
                                            <option value="succeeded">Succeeded</option>
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

                @php
                    $statusClasses = [
                        'pending' => 'bg-secondary',
                        'verified' => 'bg-primary',
                        'canceled' => 'bg-danger',
                        'failed' => 'bg-warning text-dark',
                        'succeeded' => 'bg-success',
                    ];

                    $statusLabels = [
                        'pending' => 'Pending',
                        'verified' => 'Verified',
                        'canceled' => 'Canceled',
                        'failed' => 'Failed',
                        'succeeded' => 'Succeeded',
                    ];
                @endphp

                @foreach ($paginatedRequests as $status => $requests)
                    <h1>
                        <span class="badge {{ $statusClasses[$status] }} text-white">
                            {{ $statusLabels[$status] }}
                        </span>
                    </h1>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>User ID</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Details</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($requests as $request)
                                <tr data-withdrawal-request-id="{{ $request->id }}">
                                    <td class="">{{ $request->id }}</td>
                                    <td class="withdrawal-request-user_id">{{ $request->user_id }}</td>
                                    <td class="withdrawal-request-amount">{{ $request->amount }}</td>
                                    <td class="withdrawal-request-method">{{ $request->method }}</td>
                                    <td class="withdrawal-request-details">{{ $request->details }}</td>
                                    <td class="withdrawal-request-status">
                                        <span class="badge {{ $statusClasses[$request->status] }} text-white">
                                            {{ $statusLabels[$request->status] }}
                                        </span>
                                    </td>
                                    <td class="d-flex">
                                        <button type="button" class="btn rounded btn-sm btn-light btn-edit"
                                            data-toggle="modal" data-target="#editModal">
                                            Edit
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{ $requests->appends([$status . '_page' => $requests->currentPage()])->links() }}
                @endforeach
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
                $('#editModal select[name="status"]').val(withdrawalRequestStatus);

                $('#editModal').modal('show');
            });
            $('#saveChangesBtn').on('click', function() {
                $('#editForm').submit();
            });
        });
    </script>
@endsection
