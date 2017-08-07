@extends('layouts.dashboard')

@section('title')
    Add {{ isset($parent) ? 'child' : 'new' }} category
@endsection

@section('content')
    <article class="content">
        <section class="section">
            <div class="card">
                <div class="card-block">
                    <div class="card-title-block">
                        <h3 class="title">
                            Add {{ isset($parent) ? 'child' : 'new' }} category
                        </h3>
                    </div>
                    <section class="example">
                        <form action="{{ isset($parent) ? route('categories.addChild', ['parentId' => $parent->id]) : route('categories.add') }}"
                              id="category-form" method="POST" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @isset($parent)
                                <div class="form-group">
                                    <label for="parent_id">Parent category name</label>
                                    <input type="text" class="form-control boxed" id="parent_id"
                                           value="{{ $parent->name }}" disabled>
                                </div>
                            @endisset
                            <div class="form-group{{ $errors->has('country_id')  ? ' has-error' : '' }}">
                                <label for="country_id">Country</label>
                                <select name="country_id" id="country_id"
                                        class="form-control boxed"{{ isset($parent) ? ' disabled' : '' }}>
                                    @if (isset($parent))
                                        <option value="{{ $parent->country->id }}">{{ $parent->country->name }}</option>
                                    @else
                                        @foreach ($countries as $country)
                                            <option value="{{ $country->id }}"{{ old('country_id') == $country->id ? ' selected' : '' }}>{{ $country->name }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @if ($errors->has('country_id'))
                                    <span class="has-error">{{ $errors->first('country_id') }}</span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                <label for="name">Category name</label>
                                <input type="text" class="form-control boxed" name="name"
                                       placeholder="Category name" value="{{ old('name') }}">
                                @if ($errors->has('name'))
                                    <span class="has-error">{{ $errors->first('name') }}</span>
                                @endif
                            </div>
                            <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }}">
                                <label for="type">Category type</label>
                                <select name="type" id="type" class="form-control boxed">
                                    @foreach (\App\Models\Category::getCategories(true) as $type => $name)
                                        <option value="{{ $type }}"{{ old('type') == $type ? ' selected' : '' }}>{{ $name }}</option>
                                    @endforeach
                                </select>
                                @if ($errors->has('type'))
                                    <span class="has-error">{{ $errors->first('type') }}</span>
                                @endif
                            </div>
                            <textarea class="hidden" name="data" id="data">{!! old('data') !!}</textarea>
                            <div class="form-group hidden{{ $errors->has('data') ? ' has-error' : '' }}">
                                <label for="data_url">Link url address</label>
                                <input type="text" id="data_url" class="form-control boxed"
                                       placeholder="http://example.com">
                                @if ($errors->has('data'))
                                    <span class="has-error">{{ $errors->first('data') }}</span>
                                @endif
                            </div>
                            <div class="form-group hidden{{ $errors->has('data') ? ' has-error' : '' }}">
                                <label for="data_content">Content</label>
                                <textarea id="data_content" class="form-control boxed"></textarea>
                                @if ($errors->has('data'))
                                    <span class="has-error">{{ $errors->first('data') }}</span>
                                @endif
                            </div>
                            <div class="form-group hidden{{ $errors->has('data_image') ? ' has-error' : '' }}">
                                <label for="data_image">Image file</label>
                                <input type="file" id="data_image" name="data_image" class="form-control boxed"/>
                                @if ($errors->has('data_image'))
                                    <span class="has-error">{{ $errors->first('data_image') }}</span>
                                @endif
                            </div>
                            <div class="form-group text-right">
                                <a href="{{ isset($parent) ? route('categories.view', ['id' => $parent->id]) : route('categories.index')  }}"
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
    var urlInput = $('#data_url');
    var contentInput = $('#data_content');
    var imageInput = $('#data_image');

    contentInput.summernote({
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

    $('#data_content').summernote('code', $('#content').val());

    $('#type').change(function () {
        var $this = $(this);
        if ($this.val() == '{{ \App\Models\Category::TYPE_EXTERNAL_LINK }}' || $this.val() == '{{ \App\Models\Category::TYPE_INTERNAL_LINK }}') {
            urlInput.val($('#data').val());
            urlInput.closest('.form-group').removeClass('hidden');
            contentInput.val('');
            contentInput.closest('.form-group').addClass('hidden');
            imageInput.val('');
            imageInput.closest('.form-group').addClass('hidden');
        } else if ($this.val() == '{{ \App\Models\Category::TYPE_WEB_CONTENT }}') {
            contentInput.summernote('code', $('#data').val());
            contentInput.closest('.form-group').removeClass('hidden');
            urlInput.val('');
            urlInput.closest('.form-group').addClass('hidden');
            imageInput.val('');
            imageInput.closest('.form-group').addClass('hidden');
        } else if ($this.val() == '{{ \App\Models\Category::TYPE_WEB_IMAGE }}') {
            urlInput.val('');
            contentInput.val('');
            urlInput.closest('.form-group').addClass('hidden');
            contentInput.closest('.form-group').addClass('hidden');
            imageInput.closest('.form-group').removeClass('hidden');
        } else {
            urlInput.val('');
            contentInput.val('');
            imageInput.val('');
            urlInput.closest('.form-group').addClass('hidden');
            contentInput.closest('.form-group').addClass('hidden');
            imageInput.closest('.form-group').addClass('hidden');
        }
    });

    $('#type').trigger('change');

    $('#category-form').submit(function () {
        var typeInput = $('#type');
        if (typeInput.val() == '{{ \App\Models\Category::TYPE_EXTERNAL_LINK }}' || typeInput.val() == '{{ \App\Models\Category::TYPE_INTERNAL_LINK }}') {
            $('#data').val(urlInput.val());
        } else if (typeInput.val() == '{{ \App\Models\Category::TYPE_WEB_CONTENT }}') {
            var code = $('#data_content').summernote('code');
            $('#data').val(code);
        } else {
            $('#data').val('')
        }

        return true;
    });
</script>
@endpush