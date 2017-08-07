@extends('layouts.dashboard')

@section('title', 'View monument information')

@section('content')
    <article class="content">
        <section class="section">
            <div class="card">
                <div class="card-block">
                    <div class="card-title-block">
                        <h3 class="title">
                            View monument
                        </h3>
                    </div>
                    <section class="section">
                        <h5>Monument category: <span class="text-success">{{ $monument->category->name }}</span></h5>
                        <h5>Monument name: <span class="text-success">{{ $monument->name }}</span></h5>
                        <h5>Monument area: <span class="text-success">{{ $monument->area }}</span></h5>
                        <p>
                            <a href="{{ route('categories.view', ['id' => $monument->category->id]) }}"
                               class="btn btn-secondary">
                                <i class="fa fa-angle-left"></i> Back to category
                            </a>
                        </p>
                        <div class="title-block">
                            <h3 class="title">Web content</h3>
                        </div>
                        <div class="form-group">
                            <div class="form-control boxed">
                                {!! $monument->data !!}
                            </div>
                        </div>
                        <div class="title-block">
                            <h3 class="title">
                                Monument photos
                                <button type="button" id="upload_photo" class="btn btn-primary pull-right">
                                    <i class="fa fa-upload"></i>
                                    Upload new photo
                                </button>
                            </h3>
                        </div>
                        @forelse ($monument->photos as $photo)
                            @if (file_exists($photo->path))
                                <div class="photo-container col-sm-6 img-responsive">
                                    <div class="col-xs-4">
                                        <a href="{{ asset($photo->path) }}">
                                            <img src="{{ asset($photo->path) }}" class="img img-thumbnail" alt="">
                                        </a>
                                        <button data-id="{{ $photo->id }}"
                                                class="btn btn-danger btn-block btn-delete-image">
                                            <i class="fa fa-trash"></i> Delete
                                        </button>
                                    </div>
                                    <div class="col-xs-8">
                                        <strong>Dimensions</strong>
                                        <p>{{ $photo->width . ' x ' . $photo->height }}</p>
                                        <strong>File size</strong>
                                        <p>{{ round(filesize($photo->path) / 1024, 2) . ' kb' }}</p>
                                        <strong>Description</strong>
                                        @if ($photo->description)
                                            <p>{{ $photo->description }}</p>
                                        @else
                                            <p>No description available</p>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <?php
                                    if ($photo->big_image_path && file_exists($photo->big_image_path)) {
                                        unlink($photo->big_image_path);
                                    }
                                    $photo->delete();
                                ?>
                            @endif
                        @empty
                            <div class="text-success">
                                <h1 class="text-center"><i class="fa fa-info-circle"></i></h1>
                                <h3 class="text-center">There is not any uploaded photo yet</h3>
                            </div>
                        @endforelse
                    </section>
                </div>
            </div>
        </section>
    </article>
    <div class="modal fade in" id="upload-image-modal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title"><i class="fa fa-upload"></i> Upload photo</h4>
                </div>
                <div class="modal-body">
                    <form action="{{ route('monuments.uploadImage', ['id' => $monument->id]) }}" id="upload_form"
                          method="post" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class="form-group">
                            <label for="image">Image file</label>
                            <input id="file" type="file" name="image" class="form-control boxed" required>
                        </div>
                        <div class="form-group">
                            <label for="big_image">Large image</label>
                            <input id="file" type="file" name="big_image" class="form-control boxed" required>
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea id="description" class="form-control boxed" name="description"
                                      rows="3"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" id="upload_button" class="btn btn-primary" data-dismiss="modal">Upload
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
    <form id="delete-image-form" action="{{ route('monuments.deleteImage') }}" method="POST">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" id="image_id" name="image_id">
    </form>
    <form id="delete-category-form" action="{{ route('categories.delete') }}" method="POST" class="hidden">
        {{ csrf_field() }}
        <input type="hidden" name="_method" value="DELETE">
        <input type="hidden" id="category_id" name="category_id">
    </form>
@endsection

@push('scripts')
<script type="application/javascript" src="{{ asset('js/snippets/view-monument.js') }}"></script>
@endpush

