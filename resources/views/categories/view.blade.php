@extends('layouts.dashboard')

@section('title', 'View category')

@section('content')
    <article class="content">
        <section class="section">
            <div class="card">
                <div class="card-block">
                    <div class="card-title-block">
                        <h3 class="title">
                            View category
                        </h3>
                    </div>
                    <section class="section">
                        <h5>Category name: <span class="text-success">{{ $category->name }}</span></h5>
                        <br>
                        @if ($category->type == \App\Models\Category::TYPE_CATEGORY)
                            @include('categories.include.monuments', ['category' => $category])
                        @elseif ($category->type == \App\Models\Category::TYPE_PARENT_CATEGORY)
                            @include('categories.include.children', ['category' => $category])
                        @elseif ($category->type == \App\Models\Category::TYPE_WEB_CONTENT)
                            @include('categories.include.content', ['category' => $category])
                        @elseif ($category->type == \App\Models\Category::TYPE_WEB_IMAGE)
                            <h5>Web image</h5>
                            <div class="img-responsive">
                                <img src="{{ asset($category->data) }}" alt="" class="img-thumbnail">
                            </div>
                        @else
                            <h5>
                                Link url address:
                                <span class="text-success">
                                    <a href="{{ $category->data  }}" target="_blank">{{ $category->data }}</a>
                                </span>
                            </h5>
                        @endif
                        <br>
                        <div class="form-group">
                            <form action="{{ route('categories.delete') }}" method="POST">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="DELETE">
                                <input type="hidden" name="category_id" value="{{ $category->id }}">
                                <a href="{{ $category->parent != null ? route('categories.view', ['id' => $category->parent->id]) : route('categories.index') }}"
                                   class="btn btn-secondary">
                                    <i class="fa fa-angle-left"></i> Back
                                </a>
                                @if ($category->children()->count() == 0 && $category->monuments()->count() == 0)
                                    <button class="btn btn-danger" type="submit">
                                        <i class="fa fa-trash"></i> Delete
                                    </button>
                                @endif
                                <a href="{{ route('categories.editForm', ['id' => $category->id]) }}"
                                   class="btn btn-info">
                                    <i class="fa fa-edit"></i> Edit
                                </a>
                            </form>
                        </div>
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