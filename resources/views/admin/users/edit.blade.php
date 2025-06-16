@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
    <h1>Edit User</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('admin.users.edit.action', $user->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $user->name }}" required>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ $user->email }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Password (Leave blank to keep current password)</label>
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                </div>

                <div class="form-group">
                    <label for="usertype">Role</label>
                    <select name="usertype" id="usertype" class="form-control">
                        <option value="admin" {{ $user->usertype === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="editor" {{ $user->usertype === 'editor' ? 'selected' : '' }}>Editor</option>
                        <option value="attorney" {{ $user->usertype === 'attorney' ? 'selected' : '' }}>Attorney</option>
                        <option value="user" {{ $user->usertype === 'user' ? 'selected' : '' }}>User</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Save Changes</button>
            </form>
        </div>
    </div>
@stop
@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>
    <strong>
        Developed By: <a href="{{ config('app.company_url', 'mailto:sayahsamson@gmail.com') }}">
            {{ config('app.company_name', 'Samson Saya') }}
        </a>
    </strong>
@stop