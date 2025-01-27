@extends('layouts.master')
@section('title','Data Entry')

@section('content')
<link rel="stylesheet" href="{{ asset('css/data-entry-form.css') }}">

<form method="POST" action="{{ route('data.entry') }}" class="data-entry-form">
    @if(session('success'))
        <script>
            Swal.fire({
                title: @json(session('success')),
                icon: 'success',
                confirmButtonColor: '#6259ca',
                confirmButtonText: 'Ok'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Reload the page after confirmation
                    window.location.href = '{{ route('showReport') }}';
                }
            });
        </script>
    @endif

    <!-- @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif -->

    @if (session('error'))
        <script>
            Swal.fire({
                title: '{{ session('error') }}',
                icon: 'error',
                confirmButtonColor: '#e74c3c',
                confirmButtonText: 'Ok'
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            // Collect all error messages into an array
            const errorMessages = @json($errors->all());

            // Trigger SweetAlert2 with error messages
            Swal.fire({
                title: 'Validation Errors',
                icon: 'error',
                html: '<ul>' + errorMessages.map(error => '<li>' + error + '</li>').join('') + '</ul>',
                confirmButtonColor: '#e74c3c', // Red button for errors
                confirmButtonText: 'Ok'
            });
        </script>
    @endif
    @csrf
    <h2>Tenant Information</h2>

    <div class="form-group">
        <label for="building_name">Building Name</label>
        <input type="text" name="building_name" id="building_name" class="form-input" >
    </div>

    <div class="form-group">
        <label for="address">Address</label>
        <input type="text" name="address" id="address" class="form-input" autocomplete>
    </div>

    <div class="form-group">
        <label for="tower">Tower</label>
        <input type="text" name="tower" id="tower" class="form-input" >
    </div>

    <div class="form-group">
        <label for="tenant_name">Tenant Name</label>
        <input type="text" name="tenant_name" id="tenant_name" class="form-input" >
    </div>

    <div class="form-group">
        <label for="suit">Suit</label>
        <input type="number" name="suit" id="suit" class="form-input" min="1">
    </div>

    <div class="form-group">
        <label for="rent">In-Place Rent (NNN)</label>
        <input type="text" name="rent" id="rent" min="1" class="form-input" >
    </div>

    <div class="form-group">
        <label for="square_feet">Square Feet (SF)</label>
        <input type="number" name="square_feet" id="square_feet" class="form-input" min="1" step="1">
    </div>


    <div class="form-group">
        <label for="percentage_of_total">% of Total </label>
        <input type="number" name="percentage_of_total" id="percentage_of_total" class="form-input" step="0.01" min="0.1">
    </div>

    <div class="form-group">
        <label for="lease_expiration" class="uppercase-label">Lease Expiration</label>
        <input type="date" name="lease_expiration" id="lease_expiration" class="form-input uppercase-input">
    </div>

    <button type="submit" class="submit-btn">Submit</button>
</form>
<script>
    $(document).ready(function() {

        $('#lease_expiration').click(function() {
            $(this)[0].showPicker();
        });

        const today = new Date().toISOString().split('T')[0];
        $('#lease_expiration').attr('min', today);

        if ($('.alert-success').length) {
            setTimeout(function() {
                $('.alert-success').fadeOut();
            }, 6000);
        }
        $('input').on('blur', function() {
            const value = $(this).val();
            const name = $(this).attr('name').replace(/_/g, ' ').replace(/^./, str => str.toUpperCase());
            console.log(name);
            $(this).next('.error').remove();

            if (!value) {
                $(this).after(`<div class="error" style="color:red; margin-top:5px">${name} is required.</div>`);
                $(this).css('border', '1px solid red');
            } else {
                $(this).css('border', '1px solid #ccc');
            }
        });
        $("form").submit(function(event) {
            let isValid = true;
            $('.error').remove();
            let formData = $(this).serializeArray();
            $.each(formData, function(index, FieldData) {
                let fieldName = FieldData.name;
                let fieldValue = FieldData.value;
                let $field = $('#' + fieldName);

                $field.next('.error').remove();
                if (fieldValue === '') {
                    isValid = false;
                    $field.after(`<div class="error" style="color:red; margin-top:5px">${fieldName.replace(/_/g, ' ').replace(/^./, str => str.toUpperCase())} is required.</div>`);
                }
            });
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
