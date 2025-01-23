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
</style>

<form method="POST" action="{{ route('data.entry') }}" class="data-entry-form">
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="error">{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show">
            <ul>
                @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
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
        <input type="number" name="suit" id="suit" class="form-input" >
    </div>

    <div class="form-group">
        <label for="rent">In-Place Rent (NNN)</label>
        <input type="text" name="rent" id="rent" class="form-input" >
    </div>

    <div class="form-group">
        <label for="square_feet">Square Feet (SF)</label>
        <input type="number" name="square_feet" id="square_feet" class="form-input" >
    </div>

    <div class="form-group">
        <label for="percentage_of_total">Percentage of Total</label>
        <input type="number" name="percentage_of_total" id="percentage_of_total" class="form-input" step="0.01" >
    </div>

    <div class="form-group">
        <label for="lease_expiration">Lease Expiration</label>
        <input type="date" name="lease_expiration" id="lease_expiration" class="form-input" >
    </div>

    <button type="submit" class="submit-btn">Submit</button>
</form>
<script>
    $(document).ready(function() {
        if ($('.alert-success').length) {
            setTimeout(function() {
                $('.alert-success').fadeOut();
            }, 6000);
        }
        $('input').on('blur', function() {
            const value = $(this).val();
            const name = $(this).attr('name').replace(/_/g, ' ');
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
                    $field.after(`<div class="error" style="color:red; margin-top:5px">${fieldName.replace("_", " ")} is required.</div>`);
                }
            });
            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
