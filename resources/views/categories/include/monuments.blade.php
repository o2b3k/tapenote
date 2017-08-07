<?php // TODO ?>
<div class="title-block">
    <h3 class="title">
        Monuments
        <a href="{{ route('monuments.addForm', ['categoryId' => $category->id]) }}"
           class="btn btn-primary pull-right">
            <i class="fa fa-plus"></i>
            Add monument
        </a>
    </h3>
</div>
<table class="table">
    <thead>
    <tr>
        <td>#</td>
        <td>Name</td>
        <td>Actions</td>
    </tr>
    </thead>
    <tbody>
    @forelse ($category->monuments as $monument)
    <tr>
        <td>{{ $monument->id }}</td>
        <td><a href="{{ route('monuments.view', ['id' => $monument->id]) }}">{{ $monument->name }}</a></td>
        <td>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('monuments.editForm', ['id' => $monument->id]) }}"
                   class="btn btn-secondary">
                    <i class="fa fa-edit"></i>
                </a>
                <button data-id="{{ $monument->id }}" class="btn btn-danger btn-delete-monument">
                    <i class="fa fa-trash"></i>
                </button>
            </div>
        </td>
    </tr>
    @empty
    <tr>
        <td colspan="3">
            <div class="text-success">
                <h1 class="text-center"><i class="fa fa-info-circle"></i></h1>
                <h3 class="text-center">There is not any monument yet</h3>
            </div>
        </td>
    </tr>
    @endforelse
    </tbody>
</table>
<form id="delete-monument-form" class="hidden" action="{{ route('monuments.delete') }}" method="POST">
    {{ csrf_field() }}
    <input type="hidden" name="_method" value="DELETE">
    <input type="hidden" id="monument_id" name="monument_id">
</form>

@push('scripts')
<script type="application/javascript" src="{{ asset('js/snippets/delete-monument.js') }}"></script>
@endpush