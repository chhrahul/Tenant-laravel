@extends('layouts.master')
@section('title','Register')
@section('content')

<style>
    /* Basic Form Styling */
    .registration-form {
        max-width: 400px;
        margin: 0 auto;
        padding: 20px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .registration-form h2 {
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
<form action="{{ route('register.user') }}" method="POST" class="registration-form">
    @csrf
    <h2>Registratin Form</h2>

    <div class="form-group">
        <label for="name">Name</label>
        <input type="text" id="name" name="name"  class="form-input">
        @error('name')
            <div class="error" style="color:red; margin-top:5px">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="username">Username</label>
        <input type="text" id="username" name="username"  class="form-input">
        @error('username')
            <div class="error" style="color:red; margin-top:5px">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email"  class="form-input">
        @error('email')
            <div class="error" style="color:red; margin-top:5px">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password"  class="form-input">
        @error('password')
            <div class="error" style="color:red; margin-top:5px">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation"  class="form-input">
    </div>

    <div class="form-group">
        <label for="role">Role</label>
        <select id="role" name="role" class="form-input" required>
            <option value="admin">Admin</option>
            <option value="user">User</option>
        </select>
    </div>

    <button type="submit" class="submit-btn">Register</button>
    <div class="form-footer">
        <p>Already Registered? <a href="{{ route('login.form') }}">Login here</a></p>
    </div>
</form>

<script>
$(document).ready(function() {
    $('form').on('submit', function(event) {
        let isValid = true;
        $('.error').remove();
        // event.preventDefault();
        let formData = $(this).serializeArray();
        // console.log(formData);
        // return;
        $.each(formData, function(index, FieldData) {
            let fieldName = FieldData.name;
            let fieldValue = FieldData.value;
            let $field = $('#' + fieldName);

            $field.next('.error').remove();
            if (fieldValue === '') {
                isValid = false;
                $field.after(`<div class="error" style="color:red; margin-top:5px">${fieldName.replace(/_/g, ' ').replace(/^./, str => str.toUpperCase())} is required.</div>`);
            }

            if (fieldName === 'email' && fieldValue !== '') {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(fieldValue)) {
                    isValid = false;
                    $field.after(`<div class="error" style="color:red; margin-top:5px">Please enter a valid email address.</div>`);
                }
            }
            if (fieldName === 'confirm_password') {
                const passwordValue = $('#password').val();
                if (fieldValue !== passwordValue) {
                    isValid = false;
                    $field.after(`<div class="error" style="color:red; margin-top:5px">Passwords do not match.</div>`);
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
