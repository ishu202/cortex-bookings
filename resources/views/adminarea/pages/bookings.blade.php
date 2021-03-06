{{-- Master Layout --}}
@extends('cortex/foundation::adminarea.layouts.default')

{{-- Page Title --}}
@section('title')
    {{ extract_title(Breadcrumbs::render()) }}
@endsection

@push('styles')
    <link href="{{ mix('css/fullcalendar.css') }}" rel="stylesheet">
@endpush

@push('vendor-scripts')
    <script src="{{ mix('js/fullcalendar.js') }}" defer></script>
@endpush

@push('inline-scripts')
    @include('cortex/bookings::adminarea.partials.service-booking-scripts')
@endpush

{{-- Main Content --}}
@section('content')

    <div class="content-wrapper">
        <section class="content-header">
            <h1>{{ Breadcrumbs::render() }}</h1>
        </section>

        {{-- Main content --}}
        <section class="content">

            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-body no-padding">
                            <div data-calendar="bookings"></div>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>

@endsection
