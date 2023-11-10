@php
    $submitButton = $submitButton ?? 'Submit';
    $formAction = $formAction ?? false;
    $formMethod = $formMethod ?? 'post';
@endphp
@if ($formAction)
    <div class="modal fade" id="{{ $id  }}" tabindex="-1" role="dialog" aria-labelledby=" {{$id}}Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form
                    id="create-sale-source-form"
                    class="m-form d-none"
                    method="post"
                    action="{{ route('manage.order.orders.create.sale.source') }}"
                    data-confirm="true"
                    data-confirm-type="create"
                    data-confirm-title="Create a new sale source"
                    data-confirm-text="This procedure is irreversible."
                >
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{$title}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        {{ $slot }}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">{{$submitButton}}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@else
    <div class="modal fade" id="{{ $id  }}" tabindex="-1" role="dialog" aria-labelledby=" {{$id}}Label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{$title}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ $slot }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">{{$submitButton}}</button>
                </div>
            </div>
        </div>
    </div>
@endif
