@extends('layouts.master')
@section('title','Report')
@section('content')

<link rel="stylesheet" href="{{ asset('css/data-entry-form.css') }}">


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
    #data-table_filter{
        margin-bottom: 10px;
    }
    .loader {
        border: 4px solid #f3f3f3;
        border-top: 4px solid #3498db;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        animation: spin 1s linear infinite;
        margin: 0 auto;
    }

    @keyframes spin {
        0% {
            transform: rotate(0deg);
        }
        100% {
            transform: rotate(360deg);
        }
    }
</style>

<div class="container-fluid">
    <h1 style="text-align:center">Tenant Summary</h1>
    <table id="data-table" class="display">
        <thead>
            <tr>
                <th>BUILDING NAME</th>
                <th>TOWER</th>
                <th>SUITE</th>
                <th>IN-PLACE RENT (NNN)</th>
                <th>SQUARE FEET</th>
                <th>% OF TOTAL</th>
                <th>LEASE EXPIRATION </th>
                <th>EDIT</th>
                <!-- <th>DELETE</th> -->
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th colspan="4">Total Occupation:</th>
                <th></th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

<div class="modal fade" id="edit-data-modal" tabindex="-1" aria-labelledby="exampleModalLabel">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Tenant Information</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="edit-data-form">
            @csrf
            <input type="hidden" id="edit-id" name="id">

            <div class="mb-3">
                <label for="edit-building_name" class="form-label">Building Name</label>
                <input type="text" name="building_name" id="edit-building_name" class="form-input" required>
            </div>

            <div class="mb-3">
                <label for="edit-address" class="form-label">Address</label>
                <input type="text" name="address" id="edit-address" class="form-input" required>
            </div>

            <div class="mb-3">
                <label for="edit-tower" class="form-label">Tower</label>
                <input type="text" name="tower" id="edit-tower" class="form-input" required>
            </div>

            <div class="mb-3">
                <label for="edit-tenant_name" class="form-label">Tenant Name</label>
                <input type="text" name="tenant_name" id="edit-tenant_name" class="form-input" required>
            </div>

            <div class="mb-3">
                <label for="edit-suit" class="form-label">Suit</label>
                <input type="number" name="suit" id="edit-suit" class="form-input" min="1" required>
            </div>

            <div class="mb-3">
                <label for="edit-rent" class="form-label">In-Place Rent (NNN)</label>
                <input type="text" name="rent" id="edit-rent" class="form-input" required>
            </div>

            <div class="mb-3">
                <label for="edit-square_feet" class="form-label">Square Feet (SF)</label>
                <input type="number" name="square_feet" id="edit-square_feet" class="form-input" min="1" required>
            </div>

            <div class="mb-3">
                <label for="edit-percentage_of_total" class="form-label">% of Total</label>
                <input type="number" name="percentage_of_total" id="edit-percentage_of_total" class="form-input" step="0.01" min="0.1" required>
            </div>

            <div class="mb-3">
                <label for="edit-lease_expiration" class="form-label">Lease Expiration</label>
                <input type="date" name="lease_expiration" id="edit-lease_expiration" class="form-input" required>
            </div>
        </form>
    </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-data-changes">Update changes</button>
      </div>
    </div>
  </div>
</div>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {

    $('#edit-lease_expiration').click(function() {
            $(this)[0].showPicker();
        });

    const today = new Date().toISOString().split('T')[0];
    $('#edit-lease_expiration').attr('min', today);

    $('#data-table').DataTable({
        processing: true,
        serverSide: true,
        language: {
            processing: '<div class="loader"></div>' // Add your custom loader HTML
        },
        ajax: '{{ route('data-entry.data') }}', // The route that returns data
        columns: [
            { data: 'building_name', name: 'building_name' },
            { data: 'tower', name: 'tower' },
            { data: 'suit', name: 'suit' },
            { data: 'rent', name: 'rent' },
            { data: 'square_feet', name: 'square_feet' },
            { data: 'percentage_of_total', name: 'percentage_of_total' },
            {
                data: 'lease_expiration',
                name: 'lease_expiration',
                render: function(data) {
                    if (data) {
                        const date = new Date(data);
                        const day = String(date.getDate()).padStart(2, '0');
                        const month = String(date.getMonth() + 1).padStart(2, '0');
                        const year = date.getFullYear();
                        return `${day}-${month}-${year}`;
                    }
                    return '';
                }
            },
            {
                data: null,
                orderable: false,
                searchable: false,
                render: function(data, type, row) {
                    return `<button class="btn btn-primary btn-data-edit" data-bs-toggle="modal" data-bs-target="#edit-data-modal" data-id="${row.id}">Edit</button>`;
                }
            }
        ],
        columnDefs: [
            {
                targets: '_all', // Apply to all columns
                createdCell: function (td) {
                    $(td).css('padding', '20px'); // Add 5px padding to all columns
                }
            }
        ],
        footerCallback: function(row, data, start, end, display) {
            let api = this.api();

            let intVal = function(i) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '') * 1 :
                    typeof i === 'number' ? i : 0;
            };

            let totalSquareFeet = api
                .column(4, { page: 'current' })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            let totalPercentage = api
                .column(5, { page: 'current' })
                .data()
                .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                }, 0);

            $(api.column(4).footer()).html(totalSquareFeet.toLocaleString());
            $(api.column(5).footer()).html(totalPercentage.toFixed(2) + '%');
        }
    });

    $(document).on('click', '.btn-data-edit', function() {
        const dataId = $(this).data('id');
        console.log(dataId);
        $('#edit-data-modal').data('id', dataId);
        $.ajax({
            url: `{{ route('data.by.id', ['id' => ':id']) }}`.replace(':id', dataId),
            method: 'GET',
            success: function(response) {
                $('#edit-id').val(response.data.id);
                $('#edit-building_name').val(response.data.building_name);
                $('#edit-address').val(response.data.address);
                $('#edit-tower').val(response.data.tower);
                $('#edit-tenant_name').val(response.data.tenant_name);
                $('#edit-suit').val(response.data.suit);
                $('#edit-rent').val(response.data.rent);
                $('#edit-square_feet').val(response.data.square_feet);
                $('#edit-percentage_of_total').val(response.data.percentage_of_total);
                var leaseExpiration = new Date(response.data.lease_expiration);
                var formattedDate = leaseExpiration.toISOString().split('T')[0]; // This gives '2025-02-05'
                $('#edit-lease_expiration').val(formattedDate);
                $('#edit-user-modal').modal('show');
            },
            error: function() {
                alert('Failed to fetch user data. Please try again.');
            }
        });
    })

    $('#save-data-changes').click(function(e) {
        e.preventDefault(); // Prevent default form submission

        var formData = $('#edit-data-form').serialize(); // Serialize form data
        const dataId = $('#edit-data-modal').data('id');

        $.ajax({
            url: `{{ route('update-data-changes' ,parameters: ['id' => ':id']) }}`.replace(':id', dataId),
            method: 'PUT',
            data: formData,
            success: function (response) {
                $('#edit-data-modal').modal('hide');
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
});
</script>

@endsection
