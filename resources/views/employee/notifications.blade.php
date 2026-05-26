@extends('layouts.app')
@section('title', 'Notifications')
@section('page-title', 'Notifications')

@section('content')
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-end mb-3">
                <form action="{{ route('employee.notifications.read-all') }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-outline-primary"><i class="bi bi-check2-all me-1"></i>Mark All Read</button>
                </form>
            </div>

            @forelse($notifications as $notification)
                <div class="card border-0 shadow-sm mb-2 {{ $notification->is_read ? '' : 'border-start border-primary border-3' }}">
                    <div class="card-body d-flex justify-content-between align-items-start py-2">
                        <div>
                            <div class="fw-semibold small">
                                @if(!$notification->is_read)
                                    <span class="badge bg-primary me-1">New</span>
                                @endif
                                {{ $notification->title }}
                            </div>
                            <div class="text-muted small">{{ $notification->message }}</div>
                            <div class="text-muted" style="font-size:.7rem">{{ $notification->created_at->diffForHumans() }}</div>
                        </div>
                        @if(!$notification->is_read)
                            <form action="{{ route('employee.notifications.read', $notification) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-outline-secondary" title="Mark as read"><i class="bi bi-check2"></i></button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center text-muted py-5">
                    <i class="bi bi-bell-slash fs-1"></i>
                    <p class="mt-2">No notifications yet.</p>
                </div>
            @endforelse

            <div class="mt-3">{{ $notifications->links() }}</div>
        </div>
    </div>
@endsection
