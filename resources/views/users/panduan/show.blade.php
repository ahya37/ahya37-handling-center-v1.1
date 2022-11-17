@extends('layouts.app')
@push('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
@endpush
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            
            <div class="row">
                <div class="col-xl-12 mx-auto">
                    <div class="card border-top border-0 border-4 border-primary">
                        <div class="card-body">
                            <h5 class="card-title">{{$panduan->judul}}</h5>
                            <p class="card-text">
                                {!! $panduan->desc !!}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
