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
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th colspan="4">Total Occupation:</th>
                <th></th>
                <th></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>

<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function() {
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
});
</script>

@endsection
