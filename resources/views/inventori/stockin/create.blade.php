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
                                    <h5 class="mb-1">Item</h5>
                                </div>

                            </div>
                        </div>
                        <div class="product-list p-3 mb-3">
                            @foreach ($items as $item)
                                <div class="row border mx-0 mb-3 py-2 radius-10 cursor-pointer" id="{{ $item->it_idx }}" onclick="onSelect(this)" data-image="{{ $item->it_image }}" data-stok="{{ $item->ic_count }}" data-name="{{ $item->it_name }}">
                                    <div class="col-sm-6">
                                        <div class="d-flex align-items-center">
                                            <div class="product-img">
                                                <img src="{{ asset('/storage/' . $item->it_image) }}" alt="" />
                                            </div>
                                            <div class="ms-2">
                                                <h6 class="mb-1">{{ $item->it_name }}</h6>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm">
                                        <p class="mb-0">{{ $item->ic_count }} Stok</p>
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
                                <h5 class="mb-1">Item Terpilih</h5>
                            </div>
                        </div>
                        <form action="{{ route('stockin-store') }}" method="POST">
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
                                        <p class="mb-0" id="it_stok"></p>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <label>Qty</label>
                                        <input type="hidden" id="item" name="iditem">
                                        <input type="text" class="form-control number" id="stok" name="stok" autocomplete="off" >
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
    <script src="{{ asset('/js/stokmasuk.js') }}"></script>
<script src="{{ asset('js/number-only.js') }}"></script>   

@endpush
