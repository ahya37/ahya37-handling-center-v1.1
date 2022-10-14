@extends('layouts.app')
@push('styles')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css"
        integrity="sha512-mSYUmp1HYZDFaVKK//63EcZq4iFWFjxSL+Z3T/aCt4IO9Cejm03q3NKKYN6pFQzY0SBOr8h+eCIAZHPXcpZaNw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="{{ asset('assets/plugins/highcharts/css/highcharts.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/plugins/select2/css/select2-bootstrap4.css') }}" rel="stylesheet" />
@endpush
@section('content')
    <div class="page-wrapper">
        <div class="page-content">
            <h5>DEVELOPMENT MODE</h6>
                <ul style="list-style-type:circle">
                    <li>
                        {{-- Grafik yang ditampilkan adalah data penilaian dari semua penugasan pembimbing --}}
                </ul>
                </li>
                <hr />
                {{-- <div id="load"></div>
                <div id="container"></div>
                <div class="col-md-12 d-flex">
                    <div class="col">
                        <div class="card radius-10">
                            <div class="card-body">
                                <div id="chart1"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 d-flex">
                    <div class="card radius-10 w-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <h5 class="mb-0 font-weight-bold">SOP Nilai 0</h5>
                                <p class="mb-0 ms-auto">
                                    <i class="bx bx-dots-horizontal-rounded float-right font-22"></i>
                                </p>
                            </div>
                            <div class="d-flex mt-2 mb-4">
                                <h2 class="mb-0 font-weight-bold">2</h2>
                                <p class="mb-0 ms-1 font-14 align-self-end text-secondary">
                                    Total Tourcode
                                </p>
                            </div>
                            <div class="progress radius-10" style="height: 10px">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 100%"
                                    aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                            <div class="table-responsive mt-4">
                                <table class="table mb-0">
                                    <tbody>
                                        <tr>
                                            <td class="px-0">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <i class="bx bxs-checkbox me-2 font-22 text-danger"></i>
                                                    </div>
                                                    <div>MK/MDN/20/01/2022</div>
                                                </div>
                                            </td>
                                            <td>12</td>
                                            <td class="px-0 text-right">20%</td>
                                        </tr>
                                        <tr>
                                            <td class="px-0">
                                                <div class="d-flex align-items-center">
                                                    <div>
                                                        <i class="bx bxs-checkbox me-2 font-22 text-danger"></i>
                                                    </div>
                                                    <div>MK/MDN/20/01/2022</div>
                                                </div>
                                            </td>
                                            <td>12</td>
                                            <td class="px-0 text-right">20%</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div> --}}
        </div>
    </div>
    <!--start switcher-->
    {{-- <div class="switcher-wrapper">
        <div class="switcher-btn"><i class="bx bx-cog bx-spin"></i></div>
        <div class="switcher-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-uppercase">Filter</h5>
                <button type="button" class="btn-close ms-auto close-switcher" aria-label="Close"></button>
            </div>
            <hr />
            <div class="d-flex align-items-center justify-content-between">
                <div class="col-md-12">
                    <div class="input-group">
                        <input type="text" id="from" class="form-control datepicker filter" placeholder="From"
                            aria-label="Username">
                        <span class="input-group-text">-</span>
                        <input type="text" id="to" class="form-control datepicker filter" placeholder="To"
                            aria-label="Server">
                    </div>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <div class="col-md-12">
                    <select id="pembimbing" class="single-select pembimbing @error('pembimbing') is-invalid @enderror"
                        name="pembimbing"></select>
                </div>
            </div>
            <div class="d-flex align-items-center justify-content-between mt-2">
                <div class="col-md-12">
                    <select id="tourcode" class="single-select tourcode @error('tourcode') is-invalid @enderror"
                        name="tourcode"></select>
                </div>
            </div>
            <div class="mt-2">
                <button onclick="onSubmit()" class="btn btn-sm btn-primary col-md-12" title="Bulan">Filter</button>
            </div>
            <div class="mt-2">
                <button onclick="clearAllForm()" class="btn btn-sm btn-outline-secondary col-md-12" title="Bulan">Tampilkan
                    Semua</button>
            </div>
        </div>
    </div> --}}
    <!--end switcher-->
@endsection

@push('scripts')
    <script src="{{ asset('assets/plugins/highcharts/js/highcharts.js') }}"></script>
    <script src="{{ asset('assets/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/apexcharts-bundle/js/apexcharts.min.js') }}"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"
        integrity="sha512-T/tUfKSV1bihCnd+MxKD0Hm1uBBroVYBOYSk1knyvQ9VyZJpc/ALb4P0r6ubwVPSGB2GvjeoMAJJImBG12TiaQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="{{ asset('js/dashboard-analytics.js') }}"></script>
@endpush
