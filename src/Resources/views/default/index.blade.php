@extends('admin::layouts.master')

@section('page_title')
    {{ __('Printful') }}
@stop

@section('css')
    <link
        rel="stylesheet"
        href="{{ asset('vendor/samixsous/printful-bagisto/public/css/printfulDashboard.css') }} "
    />
@stop

@section('content-wrapper')


    @if ($printfulKey != null)
        <div class="content full-page dashboard">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('Printful Dashboard') }}</h1>
                </div>

                <div class="page-action">
                    <a class="export-import" href="{{ route('admin.printful.sync') }}">
                        <i class="fal fa-sync-alt fa-lg"></i>
                        <span>{{ __('Sync') }}</span>
                    </a>
                </div>

            </div>

            <div class="page-content">
                <div class="dashboard-stats">
                    <div class="dashboard-card">
                        <div class="title">
                            {{ __('admin::app.dashboard.total-customers') }}
                        </div>

                        <div class="data">
                            {{--                        {{ $statistics['total_customers']['current'] }}--}}0

                            <span class="progress">
{{--                            @if ($statistics['total_customers']['progress'] < 0)--}}
                                {{--                                <span class="icon graph-down-icon"></span>--}}
                                {{--                                {{ __('admin::app.dashboard.decreased', [--}}
                                {{--                                        'progress' => -number_format($statistics['total_customers']['progress'], 1)--}}
                                {{--                                    ])--}}
                                {{--                                }}--}}
                                {{--                            @else--}}
                                <span class="icon graph-up-icon"></span>
                                {{ __('admin::app.dashboard.increased', [
                                        'progress' => number_format(0, 1)
                                    ])
                                }}
                                {{--                            @endif--}}
                        </span>
                        </div>
                    </div>

                    <div class="dashboard-card">
                        <div class="title">
                            {{ __('admin::app.dashboard.total-orders') }}
                        </div>

                        <div class="data">
                            {{ 0 }}

                            <span class="progress">

                                <span class="icon graph-up-icon"></span>
                                {{ __('admin::app.dashboard.increased', [
                                        'progress' => number_format(count($storeOrders), 1)
                                    ])
                                }}
                        </span>
                        </div>
                    </div>
                </div>
                <div class="dashboard-action">
                    <div class="row">
                        <div class="col-sm">
                            <a href="https://www.printful.com/dashboard/default/orders">
                                <div class="card">
                                    <i class="fad fa-shopping-cart fa-2x"></i>
                                    <p>Orders</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm">
                            <a href="https://www.printful.com/dashboard/library" target="_blank">
                                <div class="card">
                                    <i class="fad fa-folder-open fa-2x"></i>
                                    <p>File Library</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm">
                            <a href="https://www.printful.com/dashboard/store" target="_blank">
                                <div class="card">
                                    <i class="fad fa-store fa-2x"></i>
                                    <p>Stores</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm">
                            <a href="https://www.printful.com/dashboard/reports" target="_blank">
                                <div class="card">
                                    <i class="fad fa-file-chart-pie fa-2x"></i>
                                    <p>Reports</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm">
                            <a href="https://www.printful.com/dashboard/settings/account-settings" target="_blank">
                                <div class="card">
                                    <i class="fad fa-user fa-2x"></i>
                                    <p>My Account</p>
                                </div>
                            </a>
                        </div>
                        <div class="col-sm">
                            <a href="https://www.printful.com/dashboard/billing" target="_blank">
                                <div class="card">
                                    <i class="fad fa-cash-register fa-2x"></i>
                                    <p>Billing</p>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            {{-- ORDERS TABLE --}}
            <div class="page-footer">
                <div class="content">
                    <div class="page-header">
                        <div class="page-title">
                            <h1>{{ __('admin::app.sales.orders.title') }}</h1>
                        </div>

                        <div class="page-action">
                            <div class="export-import" @click="showModal('downloadDataGrid')">
                                <i class="export-icon"></i>
                                <span>{{ __('admin::app.export.export') }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="page-content">
                        @inject('orderGrid', 'Webkul\Admin\DataGrids\OrderDataGrid')
                        {!! $orderGrid->render() !!}
                    </div>
                </div>

                <modal id="downloadDataGrid" :is-open="modalIds.downloadDataGrid">
                    <h3 slot="header">{{ __('admin::app.export.download') }}</h3>
                    <div slot="body">
                        <export-form></export-form>
                    </div>
                </modal>

            </div>
        </div>
    @else
        <div class="content full-page newAPI">
            <div class="page-header">
                <div class="page-title">
                    <h1>{{ __('Printful API') }}</h1>
                </div>

                <div class="page-action">
                    <div class="export-import" @click="showModal('downloadDataGrid')">
                        <i class="fal fa-sync-alt fa-lg"></i>
                        <span>{{ __('Back') }}</span>
                    </div>
                </div>

            </div>

            <div class="page-content">
                <h2>Welcome to Printful extension for Bagisto!</h2>
                <p>We have noticed that you have not entered your Printful API key</p>
                <p>To get an API key please <a href="https://www.printful.com/dashboard/settings/store-api" target="_blank">click here</a></p>
                <form action="{{ route('admin.printful.new')  }}" method="post">
                    @csrf
                    <div class="control-group" >
                        <label for="type" class="required">{{ __('Channel') }}</label>
                        <select class="control" id="channel" name="channel">

                            @foreach($channels as $channel)
                                <option value="{{ $channel->id }}" >
                                    {{ $channel->name }}
                                </option>
                            @endforeach

                        </select>
                    </div>
                    <div class="control-group" :class="[errors.has('API') ? 'has-error' : '']">
                        <label for="API" class="required">{{ __('Printful API Key') }}</label>
                        <input type="text" class="control" id="API" name="API" value="{{ request()->input('API') ?: old('API') }}" data-vv-as="&quot;{{ __('Printful API Key') }}&quot;"/>
                        <span class="control-error" v-if="errors.has('API')">@{{ errors.first('API') }}</span>
                    </div>
                    <button type="submit" class="btn btn-lg btn-primary">
                        {{ __('Add API Key') }}
                    </button>
                </form>
            </div>
        </div>
    @endif

@stop

@push('scripts')
    <script src="https://kit.fontawesome.com/0cb9c2cd27.js" crossorigin="anonymous"></script>

@endpush