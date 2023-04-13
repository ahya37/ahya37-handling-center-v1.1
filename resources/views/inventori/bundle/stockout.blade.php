@extends('layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            @include('layouts.message')
            <!--end row-->
            <div class="row row-cols-1 row-cols-xl-2">

                <div class="col d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div>
                                    <h5 class="mb-1">Bundel</h5>
                                </div>

                            </div>
                        </div>
                        <div class="product-list p-3 mb-3">
                            @foreach ($bundles as $item)
                                <div class="row border mx-0 mb-3 py-2 radius-10 cursor-pointer" id="{{ $item->ib_idx }}" onclick="onSelect(this)" data-count="{{ $item->count_item }}"  data-name="{{ $item->ib_name }}" >
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="product-img">
                                                <img src="{{ asset('assets/images/icons/layer.png') }}" alt="" />
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-1">{{ $item->ib_name }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm mt-3">
                                        <p class="mb-0">{{ $item->count_item }} Item</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="col d-flex">
                    <div class="card radius-10 w-100">
                        <div class="d-flex p-3 align-items-center">
                            <div>
                                <h5 class="mb-1">Bundel Terpilih</h5>
                            </div>
                        </div>
                        <form action="{{ route('bundle-stockout-store') }}" method="POST">
                            @csrf
                            <div class="p-3 mb-3">
                                <div class="row border alert alert-secondary mx-0 mb-3 py-2 radius-10" >
                                    <div class="col-sm-6 ">
                                        <div class="d-flex align-items-center">
                                            <div class="product-img" id="product-img-prev">
                                                
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-1" id="it_name"></h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm mt-3">
                                        <p class="mb-0" id="it_count"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Qty</label>
                                        <input type="hidden" id="item" name="idbundle">
                                        <input type="text" placeholder="Qty, default 1" class="form-control number"  name="qty" autocomplete="off" >
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <label>Keterangan (optional)</label>
                                        <textarea class="form-control" id="note" name="note" autocomplete="off"></textarea>
                                    </div>
                                </div>
                                <div class="row mt-3">
                                    <div class="col-md-12">
                                        <button type="submit" class="btn btn-sm btn-primary float-right">Simpan</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!--end row-->
        </div>
    </div>
@endsection
@push('scripts')
    <script src="{{ asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatable/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('/js/stockout-bundle.js') }}"></script>
<script src="{{ asset('js/number-only.js') }}"></script>   

@endpush
