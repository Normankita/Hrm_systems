@props(['title' => '', 'id', 'form' => '', 'size' => 'modal-md', 'inside' => false])

<div class="modal fade" id="{{ $id }}"
    tabindex="-1" role="dialog" aria-labelledby="exampleModalFormTitle"
    aria-hidden="true">
    <div class="modal-dialog {{ $size }}" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalFormTitle">{{$title}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
               <div class="row">
                    <div class="col-md-12">
                        {{ $slot }} 
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if (!($inside))
                    <button type="button" class="btn btn-danger btn-pill"
                data-dismiss="modal">Close</button>
                @endif
               @if ($form && !($inside))
               <button form="{{ $form }}" type="submit"
                class="btn btn-primary btn-pill">Save Changes</button>
               @endif
            </div>
        </div>
    </div>
</div>
