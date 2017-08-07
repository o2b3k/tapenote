@extends('layouts.dashboard')

@section('title', 'Categories')

@section('content')
    <article class="content">
        <section class="section">
            <div class="card">
                <div class="card-block">
                    <div class="card-title-block">
                        <h3 class="title">
                            Categories list
                            <a href="{{ route('categories.addForm') }}" class="btn btn-primary pull-right">
                                <i class="fa fa-plus"></i> Add category
                            </a>
                        </h3>
                    </div>
                    <section class="example">
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
                            @forelse($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>
                                        <a href="{{ route('categories.view', ['categoryId' => $category->id]) }}">{{ $category->name }}</a>
                                    </td>
                                    <td>{{ \App\Models\Category::getCategories(true)[$category->type] }}</td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('categories.editForm', ['id' => $category->id]) }}"
                                               class="btn btn-secondary">
                                                <i class="fa fa-edit"></i>
                                            </a>
                                            <button type="button" data-id="{{ $category->id }}"
                                                    class="btn btn-danger btn-delete-category">
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
                                            <h3 class="text-center">There is not any category yet</h3>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </section>
                </div>
            </div>
        </section>
    </article>
    <form id="delete-category-form" action="{{ route('categories.delete') }}" method="POST" class="hidden">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" id="category_id" name="category_id">
    </form>
@endsection

@push('scripts')
<script type="text/javascript" src="{{ asset('js/snippets/delete-category.js') }}"></script>
@endpush