@props(['text' => 'Delete', 'route'])

<form action="{{ $route }}" method="POST" style="display:inline;">
    @csrf
    @method('DELETE')
    <button type="submit"
        class="btn btn-outline-danger btn-sm p-1
                                            mdi mdi-close">&nbsp
        {{ $text }}</button>
</form>
