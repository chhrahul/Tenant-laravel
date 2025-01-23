@extends('layouts.master')
@section('title','Login')
@section('content')
<style>
    .login-form {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .login-form h2 {
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

    .form-footer a {
        color: #007bff;
        text-decoration: none;
    }

    .form-footer a:hover {
        text-decoration: underline;
    }
</style>

<form method="POST" action="{{ route('login') }}" class="login-form">
    @csrf
    <h2>Login</h2>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" name="email" id="email" class="form-input" value="{{ old('email') }}">
        @error('email')
            <div class="error" style="color:red; margin-top:5px">{{ $message }}</div>
        @enderror
    </div>

    <!-- Password Field -->
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" id="password" class="form-input">
        @error('password')
            <div class="error" style="color:red; margin-top:5px">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="submit-btn">Login</button>

    <div class="form-footer">
        <p>Don't have an account? <a href="{{ route('register.user') }}">Register here</a></p>
    </div>
</form>
<script>
    $(document).ready(function() {
        // Form submit handler
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

                if (fieldName === 'email' && fieldValue !== '') {
                    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    if (!emailRegex.test(fieldValue)) {
                        isValid = false;
                        $field.after(`<div class="error" style="color:red; margin-top:5px">Please enter a valid email address.</div>`);
                    }
                }
            });

            if (!isValid) {
                event.preventDefault();
            }
        });
    });
</script>
@endsection
