@foreach (session('flash_notification', collect())->toArray() as $message)
    @if ($message['overlay'])
        @include('flash::modal', [
            'modalClass' => 'flash-modal',
            'title'      => $message['title'],
            'body'       => $message['message']
        ])
    @else
        <div
            class="m-alert m-alert--air m-alert--outline m-alert--outline-2x alert alert-{{ $message['level'] }} {{ $message['important'] ? 'alert-dismissible' : '' }} fade show"
            role="alert"
        >
            @if ($message['important'])
                <button
                    type="button"
                    class="close"
                    data-dismiss="alert"
                    aria-label="Close"
                >
                    <span class="sr-only">Close</span>
                </button>
            @endif
            {!! $message['message'] !!}
        </div>
    @endif
@endforeach

{{ session()->forget('flash_notification') }}
