@extends('admin.layouts.master')

@section('content')
<div class="card">
    <div class="card-header">
        <h4>Create Notification</h4>
    </div>
    <div class="card-body">
        <form action="{{ route('notifications.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Message</label>
                <textarea name="message" rows="4" class="form-control" required></textarea>
            </div>

            <!-- <div class="mb-3">
                <label class="form-label">Type</label>
                <select name="type" class="form-select" required>
                    <option value="info">Info</option>
                    <option value="success">Success</option>
                    <option value="warning">Warning</option>
                    <option value="error">Error</option>
                </select>
            </div> -->

            {{-- Optional user selection --}}
            <!-- <div class="mb-3">
                <label class="form-label">Send To (Optional)</label>
                <select name="user_id" class="form-select form-control">
                    <option value="">All Admins</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                    @endforeach
                </select>
            </div> -->

            <button class="btn btn-dark">Save </button>
        </form>
    </div>
</div>
@endsection
