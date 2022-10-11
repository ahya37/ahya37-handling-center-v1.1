@extends('layouts.app')
@section('content')
<div class="page-wrapper">
        <div class="page-content">
            @include('layouts.message')
         <div class="row">
                        <div class="col-xl-12 mx-auto">
                            <div class="card border-top border-0 border-4 border-primary">
                                <div class="card-body p-5">
                                    <div class="card-title d-flex align-items-center">
                                        <h5 class="mb-0 text-primary"> Edit Panduan
                                    </div>
                                    <hr>
                                    <form class="row g-3" action="{{route('panduan.update', $panduan->id)}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="col-md-12">
                                            <label class="form-label">Judul</label>
                                            <input type="text" name="judul" value="{{$panduan->judul}}" class="form-control @error ('tourcode') is-invalid @enderror" required>
                                            @error('count')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-12">
                                            <label class="form-label">Deskripsi</label>
                                            <textarea name="desc" id="my-editor" class="my-editor form-control @error ('tourcode') is-invalid @enderror">{!! $panduan->desc !!}</textarea>
                                            @error('count')
                                                <span class="invalid-feedback">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="col-12">
                                            <button type="submit" class="btn btn-sm btn-primary px-5">Simpan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
    </div>
</div>

@endsection
@push('scripts')
<script src="{{asset('assets/plugins/ckeditor/ckeditor.js')}}"></script>   
<script>
    var options = {
      filebrowserImageBrowseUrl: '/laravel-filemanager?type=Images',
      filebrowserImageUploadUrl: '/laravel-filemanager/upload?type=Images&_token=',
      filebrowserBrowseUrl: '/laravel-filemanager?type=Files',
      filebrowserUploadUrl: '/laravel-filemanager/upload?type=Files&_token='
    };
  </script>
<script>
     CKEDITOR.replace('my-editor', options);
</script>
@endpush