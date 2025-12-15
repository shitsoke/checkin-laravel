@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="main-content">
  <style>
    :root { --primary-color: #dc3545; --primary-hover: #c82333; --primary-light: rgba(220, 53, 69, 0.1); }
    .main-content { margin-left: 90px; padding: 20px; transition: all 0.3s ease; min-height: 100vh; }
    .container { max-width: 1000px; margin: 0 auto; padding: 0 15px; }
    .page-title { color: var(--primary-color); font-weight: 700; margin-bottom: 5px; font-size: 2rem; }
    .page-subtitle { color: #666; font-size: 1.1rem; margin-bottom: 30px; }
    h3, h4 { color: var(--primary-color); margin-bottom: 1.5rem; font-weight: 600; }
    .btn-primary { background: var(--primary-color); border: none; transition: all 0.3s ease; font-weight: 600; padding: 10px 20px; }
    .btn-primary:hover { background: var(--primary-hover); transform: translateY(-2px); box-shadow: 0 5px 15px rgba(220, 53, 69, 0.3); }
    .review-rating { color: #ffc107; font-weight: bold; }
    .review-item { background: white; border-radius: 10px; padding: 20px; margin-bottom: 15px; box-shadow: 0 2px 8px rgba(0,0,0,0.06); border-left: 4px solid var(--primary-color); }
    .card-header { background-color: var(--primary-color); color: white; border-radius: 12px 12px 0 0 !important; padding: 15px 20px; font-weight: 600; }
    .mobile-card { background: white; border-radius: 10px; box-shadow: 0 3px 10px rgba(0,0,0,0.08); padding: 18px; margin-bottom: 15px; border-left: 4px solid var(--primary-color); }
    .mobile-review-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 12px; }
    .mobile-review-type { background: var(--primary-color); color: white; padding: 5px 10px; border-radius: 6px; font-size: 0.8rem; font-weight: 600; }
    .mobile-review-comment { margin: 12px 0; line-height: 1.5; color: #444; }
    .mobile-review-footer { display: flex; justify-content: space-between; align-items: center; font-size: 0.85rem; color: #6c757d; margin-top: 10px; }
    .form-control:focus, .form-select:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25); }
    .alert { border-radius: 10px; padding: 15px; }
    .alert-success { background-color: rgba(40, 167, 69, 0.1); border-left: 4px solid #28a745; color: #155724; }
    .alert-danger { background-color: rgba(220, 53, 69, 0.1); border-left: 4px solid var(--primary-color); color: #721c24; }
    @media (max-width: 768px) { .main-content { margin-left: 0; padding: 15px; padding-top: 70px; } .container { padding: 0 10px; } .page-title { font-size: 1.6rem; text-align: center;} }
  </style>

    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-4">
            <div class="flex-grow-1">
                <h1 class="page-title"><i class="fas fa-star me-2"></i>Reviews</h1>
                <p class="page-subtitle">Share your experience and view your past reviews</p>
            </div>
        </div>

        {{-- Leave a Review --}}
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-edit me-2"></i>Leave a Review</div>
            <div class="card-body">
                @if(request('msg') === 'review_submitted')
                    <div class="alert alert-success">Review submitted successfully!</div>
                @endif
                @if(request('err') === 'cannot_submit_review')
                    <div class="alert alert-danger">You cannot submit a review at this time.</div>
                @endif
                @if(session('info'))<div class="alert alert-info">{{ session('info') }}</div>@endif
                @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
                @if(session('error'))<div class="alert alert-danger">{{ session('error') }}</div>@endif

                <form method="post" action="{{ route('reviews.store') }}">
                    @csrf
                    <div class="row">
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label fw-bold">What are you reviewing?</label>
                            <select name="room_id" class="form-select" required>
                                <option value="">Choose...</option>
                                <option value="hotel" {{ old('room_id') === 'hotel' ? 'selected' : '' }}>Overall Hotel Experience</option>
                                @foreach($roomsDone as $rd)
                                    <option value="{{ $rd->id }}" {{ (string)$rd->id === old('room_id') ? 'selected' : '' }}>
                                        Room {{ $rd->room_number }} — {{ $rd->room_type }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12 col-md-6 mb-3">
                            <label class="form-label fw-bold">Rating</label>
                            <select name="rating" class="form-select" required>
                                <option value="">Select rating...</option>
                                @for($i=5;$i>=1;$i--)
                                    <option value="{{ $i }}" {{ (string)$i === old('rating') ? 'selected' : '' }}>{{ str_repeat('★',$i) }}{{ str_repeat('☆', 5-$i) }} - {{ $i }} Star{{ $i>1?'s':'' }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Your Review</label>
                        <textarea name="comment" class="form-control" rows="4" placeholder="Share your experience...">{{ old('comment') }}</textarea>
                    </div>
                    <input type="hidden" name="return_to" value="{{ url()->current() }}">
                    <button class="btn btn-primary px-4 w-100 w-md-auto"><i class="fas fa-paper-plane me-2"></i>Submit Review</button>
                </form>
            </div>
        </div>

        {{-- Recent Overall Hotel Reviews --}}
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-hotel me-2"></i>Recent Overall Hotel Reviews</div>
            <div class="card-body">
                @if($hotelReviews->count())
                    <div class="d-none d-md-block">
                        @foreach($hotelReviews as $hr)
                            <div class="review-item mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold text-danger">{{ $hr->user->profile->display_name ?? ($hr->user->first_name . ' ' . $hr->user->last_name ?? $hr->user->email) }}</span>
                                    <span class="review-rating">{{ intval($hr->rating) }}/5 ★</span>
                                </div>
                                <div class="review-text">{!! nl2br(e($hr->comment)) !!}</div>
                                <small class="text-muted">{{ $hr->created_at->format('M j, Y') }}</small>
                            </div>
                        @endforeach
                    </div>
                    <div class="d-md-none">
                        @foreach($hotelReviews as $hr)
                            <div class="mobile-card mb-3">
                                <div class="mobile-review-header">
                                    <span class="fw-semibold text-danger">{{ $hr->user->profile->display_name ?? ($hr->user->first_name . ' ' . $hr->user->last_name ?? $hr->user->email) }}</span>
                                    <span class="review-rating">{{ intval($hr->rating) }}/5 ★</span>
                                </div>
                                <div class="mobile-review-comment">{!! nl2br(e($hr->comment)) !!}</div>
                                <div class="mobile-review-footer"><small class="text-muted">{{ $hr->created_at->format('M j, Y') }}</small></div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-muted text-center py-4"><i class="fas fa-comment-slash fa-2x mb-3"></i><p>No overall hotel reviews yet.</p></div>
                @endif
            </div>
        </div>

        {{-- My Reviews --}}
        <div class="card mb-4">
            <div class="card-header"><i class="fas fa-list me-2"></i>My Reviews</div>
            <div class="card-body">
                <div class="d-none d-md-block table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Type</th>
                                <th>Rating</th>
                                <th>Comment</th>
                                @if($isAdmin)<th>Visible</th>@endif
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myReviews as $r)
                                <tr>
                                    <td><span class="badge bg-danger">{{ $r->room_type ?? 'Hotel' }}</span></td>
                                    <td><span class="review-rating">{{ intval($r->rating) }}/5 ★</span></td>
                                    <td>{!! nl2br(e($r->comment)) !!}</td>
                                    @if($isAdmin)
                                        <td><span class="badge {{ $r->is_visible ? 'bg-success' : 'bg-secondary' }}">{{ $r->is_visible ? 'Yes' : 'No' }}</span></td>
                                    @endif
                                    <td><small class="text-muted">{{ $r->created_at->format('M j, Y') }}</small></td>
                                </tr>
                            @empty
                                <tr><td colspan="5">You haven't submitted any reviews yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile view --}}
                <div class="d-md-none">
                    @forelse($myReviews as $r)
                        <div class="mobile-card mb-3">
                            <div class="mobile-review-header">
                                <span class="mobile-review-type">{{ $r->room_type ?? 'Hotel' }}</span>
                                <span class="review-rating">{{ intval($r->rating) }}/5 ★</span>
                            </div>
                            <div class="mobile-review-comment">{!! nl2br(e($r->comment)) !!}</div>
                            <div class="mobile-review-footer">
                                <small class="text-muted">{{ $r->created_at->format('M j, Y') }}</small>
                                @if($isAdmin)
                                    <span class="badge {{ $r->is_visible ? 'bg-success' : 'bg-secondary' }}">{{ $r->is_visible ? 'Visible' : 'Hidden' }}</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-muted text-center py-4"><i class="fas fa-star fa-2x mb-3"></i><p>You haven't submitted any reviews yet.</p></div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
