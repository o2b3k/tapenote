<?php // TODO ?>
<div class="title-block">
    <h3 class="title">
        Child categories
        <a href="{{ route('categories.addChildForm', ['parentId' => $category->id]) }}"
           class="btn btn-primary pull-right">
            <i class="fa fa-plus"></i>
            Add child category
        </a>
    </h3>
</div>
<table class="table">
    <thead>
    <tr>
        <th>#</th>
        <th>Name</th>
        <th>Type</th>
        <th>Actions</th>
    </tr>
    </thead>
    <tbody>
    @forelse ($category->children as $child)
    <tr>
        <td>{{ $child->id }}</td>
        <td><a href="{{ route('categories.view', ['id' => $child->id]) }}">{{ $child->name }}</a></td>
        <td>{{ \App\Models\Category::getCategories(true)[$child->type] }}</td>
        <td>
            <div class="btn-group btn-group-sm">
                <a href="{{ route('categories.editForm', ['id' => $child->id]) }}"
                   class="btn btn-secondary">
                    <i class="fa fa-edit"></i>
                </a>
                <button data-id="{{ $child->id }}" class="btn btn-danger btn-delete-category">
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
                <h3 class="text-center">There is not any child category yet</h3>
            </div>
        </td>
    </tr>
    @endforelse
    </tbody>
</table>