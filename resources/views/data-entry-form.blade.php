@extends('layouts.master')
@section('title','Data Entry')

@section('content')
<style>
    .data-entry-form {
        max-width: 600px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .data-entry-form h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 24px;
        color: #333;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-size: 14px;
        color: #555;
        margin-bottom: 5px;
        text-transform: capitalize;
    }

    .form-input {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        color: #333;
        background-color: #fff;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .form-input:focus {
        border-color: #007bff;
        outline: none;
    }

    .submit-btn {
        width: 100%;
        padding: 10px;
        font-size: 18px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .submit-btn:hover {
        background-color: #0056b3;
    }

    .form-footer {
        text-align: center;
        margin-top: 15px;
    }

    /* Make the value inside the input uppercase */
    .uppercase-input {
        text-transform: uppercase;
        cursor: pointer;
    }

    /* If you want a placeholder-like effect (when no date is selected), you can use the following trick */
    .uppercase-input::-webkit-input-placeholder {
        text-transform: uppercase;
    }
</style>

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
                    location.reload();
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
    <h2>Data Entry Form</h2>

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
            // Trigger the click event on the input field
            $(this)[0].showPicker();
        });

        const today = new Date().toISOString().split('T')[0];
        // Set the 'min' attribute of the lease_expiration input field to today's date
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
