@extends('partials.deleteModal', ['loop' => $loop])

@section('modalText' . $loop->index)
    Are you sure you wanna delete a permission?
@endsection
@section('modalForm' . $loop->index)
    <form action="{{ route('permissionsDelete', $permission->id) }}" method="POST">
        @csrf
        <div class="d-flex justify-content-between">
            <button type="submit" class="btn btn-danger">
                Yes
            </button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
        </div>

    </form>
@endsection
