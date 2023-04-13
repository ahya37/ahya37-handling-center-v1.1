@extends('layouts.app')
@push('styles')
@endpush
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            @include('layouts.message')
            <h6 class="mb-0 ">Laporan</h6>
            <hr />
            <div class="row row-cols-8 row-cols-xl-8">

                <div class="col d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <form action="{{ route('inv-report-store') }}" method="GET" enctype="multipart/form-data">
                                @csrf
                                <div class="d-flex align-items-center">
                                    <div class="col-1">
                                        <h6 class="mb-1">Opname</h6>
                                    </div>
                                    <div class="col-2">
                                        <input type="date" name="date" class="form-control ml-2" />
                                        <input type="hidden" name="type" value="opname" class="form-control ml-2" />
                                    </div>
                                    <div class="col-1">
                                    </div>

                                    <div class="col-2 mr-2">
                                        <button type="submit" class="btn btn-sm btn-primary">Download PDF</button>
                                    </div>
                                </div>
                            </form>
                        </div>
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
    <script src="{{ asset('/js/loadbutton.js') }}"></script>
    <script src="{{ asset('js/number-only.js') }}"></script>
@endpush
