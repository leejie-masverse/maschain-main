<div class="modal center-align hide modal-image" id="{{ $modalId }}" tabindex="-1" role="dialog" aria-labelledby="Label">
    <div class="modal-dialog center-align" role="document">
        <div class="modal-content text-center center-align">
            <div class="modal-body align-content-center p-5">
                <h2 class="mb-5">{{ $title }}</h2>
                @if(isset($imageUrl))
                    <img class="mw-100 mb-5" src="{{ $imageUrl }}">
                @endif
            </div>
        </div>
    </div>
</div>

