@extends('layouts.base')
@section('title', 'Reset Password User')

@section('toolbar')
    @include('components.toolbar', ['title' => 'Reset Password User', 'subtitle' => 'Reset-Password'])
@endsection

@section('content')
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <div class="row mb-3">
            <label for="current_password" class="col-md-4 col-form-label text-md-end">{{ __('Current Password') }}</label>
            <div class="col-md-6">
                <input id="current_password" type="password" name="current_password" required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="new_password" class="col-md-4 col-form-label text-md-end">{{ __('New Password') }}</label>
            <div class="col-md-6">
                <input id="new_password" type="password" name="new_password" required>
            </div>
        </div>

        <div class="row mb-3">
            <label for="password_confirmation" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>
            <div class="col-md-6">
                <input id="password_confirmation" type="password" name="new_password_confirmation" required>
            </div>
        </div>

        <div class="row mb-0">
            <div class="col-md-6 offset-md-4">
            </div>
        </div>
    </form>
@endsection