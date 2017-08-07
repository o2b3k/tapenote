@extends('layouts.dashboard')

@section('title')
    {{ isset($monument) ? 'Edit' : 'Add' }} monument
@endsection

@section('content')
    <article class="content">
        <section class="section">
            <div class="card">
                <div class="card-block">
                    <div class="card-title-block">
                        <h3 class="title">{{ isset($monument) ? 'Edit' : 'Add' }} monument</h3>
                    </div>
                    <section class="example">
                        <form action="{{ isset($monument) ? route('monuments.edit', ['id' => $monument->id]) : route('monuments.add', ['categoryId' => $category->id]) }}"
                              id="category-form" method="POST">
                            {{ csrf_field() }}
                            @isset($monument)
                                <input type="hidden" name="_method" value="PUT">
                            @endisset
                            <div class="form-group">
                                <label for="parent_id">Parent category name</label>
                                <input type="text" class="form-control boxed" id="parent_id"
                                       value="{{ $category->name }}" disabled>
                            </div>
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Category name</label>
                                <input type="text" class="form-control boxed" id="name" name="name"
                                       placeholder="Monument name"
                                       value="{{ old('name', isset($monument) ? $monument->name : '') }}">
                                @if ($errors->has('name'))
                                    <span class="has-error">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('area') ? ' has-error' : '' }}">
                                <label for="area">Area</label>
                                <input type="text" class="form-control boxed" id="area" name="area" placeholder="Area"
                                       value="{{ old('area', isset($monument) ? $monument->area : '') }}">
                                @if ($errors->has('area'))
                                    <span class="has-error">{{ $errors->first('area') }}</span>
                                @endif
                            </div>
                            <textarea class="hidden" id="data"
                                      name="data">{!! old('data', isset($monument) ? $monument->data : '') !!}</textarea>
                            <div class="form-group {{ $errors->has('data') ? ' has-error' : '' }}">
                                <label for="data_content">Content</label>
                                <textarea id="data_content" class="form-control boxed"></textarea>
                                @if ($errors->has('data', isset($monument) ? $monument->data : ''))
                                    <span class="has-error">{{ $errors->first('data') }}</span>
                                @endif
                            </div>
                            <div class="form-group text-right">
                                <a href="{{ route('categories.view', ['id' => $category->id]) }}"
                                   class="btn btn-secondary">
                                    <i class="fa fa-angle-left"></i> Back
                                </a>
                                <button type="submit" class="btn btn-primary" name="submit">
                                    <i class="fa fa-save"></i> Save
                                </button>
                            </div>
                        </form>
                    </section>
                </div>
            </div>
        </section>
    </article>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('/plugins/summernote/summernote.css') }}"/>
@endpush

@push('scripts')
<script src="{{ asset('plugins/summernote/summernote.min.js') }}"></script>
<script>
    $('#data_content').summernote({
        minHeight: '250px',
        popover: {
            image: [],
            link: [],
            air: []
        },
        toolbar: [
            ['style', ['style']],
            ['font', ['bold', 'italic', 'underline', 'clear']],
            ['fontname', ['fontname']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link', 'picture', 'hr']],
            ['view', ['fullscreen', 'codeview']],
            ['help', ['help']]
        ]
    });

    $('#data_content').summernote('code', $('#data').val());

    $('#category-form').submit(function () {
        var code = $('#data_content').summernote('code');
        $('#data').val(code);
        return true;
    });
</script>
@endpush