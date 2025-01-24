@extends('layouts.master')
@section('title','Report')
@section('content')

<style>
    .container {
        margin-top: 20px;
    }
    table {
        width: 100%;
        border-collapse: collapse;
    }
    th, td {
        padding: 10px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }
    th {
        background-color: #f2f2f2;
    }
    tfoot th {
        background-color: #f9f9f9;
    }
</style>

<div class="container-fluid">
    <h1>User management</h1>
    <table id="data-table" class="display">
        <thead>
            <tr>
                <th>ID</th>
                <th>NAME</th>
                <th>EMAIL</th>
                <th>ROLE</th>
                <th>CREATED AT</th>
                <th>EDIT</th>
                <th>DELETE</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Modal -->
<div class="modal fade" id="edit-user-modal" tabindex="-1" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="edit-user-form">
          <div class="mb-3">
            <label for="edit-name" class="form-label">Name</label>
            <input type="text" class="form-control" id="edit-name" placeholder="Enter user name" />
          </div>
          <div class="mb-3">
            <label for="edit-role" class="form-label">Role</label>
            <select class="form-select" id="edit-role">
              <option value="" disabled selected>Select a role</option>
              <option value="admin">Admin</option>
              <option value="user">User</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="edit-password" class="form-label">Password</label>
            <input type="password" class="form-control" id="edit-password" placeholder="Enter new password" />
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-changes-btn">Save changes</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
    $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('user.management.data') }}',
        columns: [
            {
                data: 'id',
                name: 'id',
                render: function(data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: 'name', name: 'townameer' },
            { data: 'email', name: 'email' },
            { data: 'role', name: 'role' },
            {
                data: 'created_at',
                name: 'square_feet',
                render: function(data) {
                    if (data) {
                        const date = new Date(data);
                        return date.toISOString().split('T')[0];
                    }
                    return '';
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<button class="btn btn-primary btn-edit" data-bs-toggle="modal" data-bs-target="#edit-user-modal" data-id="${row.id}">Edit</button>`;
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<button class="btn btn-danger btn-delete" data-id="${row.id}">Delete</button>`;
                }
            }
        ],
        columnDefs: [
            {
                targets: '_all', // Apply to all columns
                createdCell: function (td) {
                    $(td).css('padding-left', '20px'); // Add 5px padding to all columns
                }
            }
        ]
    });

    $(document).on('click', '.btn-edit', function() {
        const userId = $(this).data('id');
        $('#edit-user-modal').data('id', userId);
        $.ajax({
            url: `{{ route('get-user-data-by-id', ['id' => ':id']) }}`.replace(':id', userId),
            method: 'GET',
            success: function(user) {
                $('#edit-name').val(user.data.name);
                $('#edit-role').val(user.data.role).change()
                $('#edit-password').val('');
                $('#edit-user-modal').modal('show');
            },
            error: function() {
                alert('Failed to fetch user data. Please try again.');
            }
        });
    })

    $('#save-changes-btn').click(function () {
        const userId = $('#edit-user-modal').data('id');
        const updatedName = $('#edit-name').val();
        const updatedRole = $('#edit-role').val();
        const updatedPassword = $('#edit-password').val();

        $.ajax({
            url: `{{ route('update-user-data', ['id' => ':id']) }}`.replace(':id', userId), // Replace :id with userId
            method: 'PUT',
            data: {
                _token: "{{ csrf_token() }}",
                name: updatedName,
                role: updatedRole,
                password: updatedPassword
            },
            success: function (response) {
                $('#edit-user-modal').modal('hide');
                if (response.success) {
                    Swal.fire({
                        title: response.message,
                        icon: 'success',
                        confirmButtonColor: '#6259ca',
                        confirmButtonText: 'Ok'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else {
                    Swal.fire({
                        title: response.message,
                        icon: 'error',
                        confirmButtonColor: '#e74c3c',
                        confirmButtonText: 'Ok'
                    });
                }
            },
            error: function (xhr) {
                console.error('Error:', xhr.responseJSON);
                alert('An error occurred while updating the user.');
            }
        });
    });

    $(document).on('click', '.btn-delete', function() {
        const userId = $(this).data('id');
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to delete the User",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#6259ca',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, cancel!'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ route('delete-user', ['id' => ':id']) }}`.replace(':id', userId),
                    type: 'DELETE',
                    data: {
                        _token: "{{ csrf_token() }}",
                    },
                    success: function(response) {
                        if (response.success) {
                            Swal.fire({
                                title: 'Success!',
                                text: response.message,
                                icon: 'success',
                                confirmButtonColor: '#6259ca',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    location.reload();
                                }
                            });
                        } else {
                            $('#from-date').val('');
                            $('#to-date').val('');
                            Swal.fire({
                                title: 'Error!',
                                text: response.message,
                                icon: 'error',
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'Ok'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            title: 'Error!',
                            text: 'An error occurred: ' + error,
                            icon: 'error',
                            confirmButtonColor: '#d33',
                            confirmButtonText: 'Ok'
                        });
                    }
                });
            }
        });
    })



});
</script>

@endsection
