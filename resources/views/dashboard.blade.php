@extends('layouts.app')

@section('content')
    <div class="container text-end">
        <h2 class="mb-4">لوحة التحكم</h2>

        <div class="row text-center">

            <div class="col-md-3 mb-3">
                <div class="card border-primary">
                    <div class="card-body">
                        <h5 class="card-title text-nowrap">إجمالي المنتجات</h5>
                        <p class="card-text fs-4">{{ $totalProducts }}
                        </p>
                    </div>
                </div>
            </div>


            <div class="col-md-3 mb-3">
                <div class="card border-secondary">
                    <div class="card-body">
                        <h5 class="card-title text-nowrap">مبيعات اليوم</h5>
                        <p class="card-text fs-4">{{ $dailySales }} ج.م</p>
                    </div>
                </div>
            </div>


            <div class="col-md-3 mb-3">
                <div class="card border-info">
                    <div class="card-body">
                        <h5 class="card-title text-nowrap">مبيعات الاسبوع</h5>
                        <p class="card-text fs-4">{{ $weeklySales }} ج.م</p>
                    </div>
                </div>
            </div>

            <div class="col-md-3 mb-3">
                <div class="card border-success">
                    <div class="card-body">
                        <h5 class="card-title text-nowrap"> مبيعات الشهر</h5>
                        <p class="card-text fs-4">{{ $monthlySales }} ج.م</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-info">
                    <div class="card-body">
                        <h5 class="card-title text-nowrap">نفقات اليوم</h5>
                        <p class="card-text fs-4">{{ $dailyExpenses }} ج.م</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <div class="card border-danger">
                    <div class="card-body">
                        <h5 class="card-title text-nowrap">نفقات الاسبوع</h5>
                        <p class="card-text fs-4">{{ $weeklyExpenses }} ج.م</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="card border-warning">
                    <div class="card-body">
                        <h5 class="card-title text-nowrap">نفقات الشهر</h5>
                        <p class="card-text fs-4">{{ $monthlyExpenses }} ج.م</p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="col-12 bg-light p-3 text-end border-start">
        <h5>لوحة التحكم</h5>
        <hr>
        <div class="row d-flex justify-content-around gap-4">
            <div class="col-7 border row d-flex  align-items-center p-3 ">
                <div class="col-4 d-flex flex-column justify-content-center align-items-center">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                            class="bi bi-diagram-3" viewBox="0 0 16 16">
                            <path fill-rule="evenodd"
                                d="M6 3.5A1.5 1.5 0 0 1 7.5 2h1A1.5 1.5 0 0 1 10 3.5v1A1.5 1.5 0 0 1 8.5 6v1H14a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0V8h-5v.5a.5.5 0 0 1-1 0v-1A.5.5 0 0 1 2 7h5.5V6A1.5 1.5 0 0 1 6 4.5zM8.5 5a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5zM0 11.5A1.5 1.5 0 0 1 1.5 10h1A1.5 1.5 0 0 1 4 11.5v1A1.5 1.5 0 0 1 2.5 14h-1A1.5 1.5 0 0 1 0 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5A1.5 1.5 0 0 1 7.5 10h1a1.5 1.5 0 0 1 1.5 1.5v1A1.5 1.5 0 0 1 8.5 14h-1A1.5 1.5 0 0 1 6 12.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5zm4.5.5a1.5 1.5 0 0 1 1.5-1.5h1a1.5 1.5 0 0 1 1.5 1.5v1a1.5 1.5 0 0 1-1.5 1.5h-1a1.5 1.5 0 0 1-1.5-1.5zm1.5-.5a.5.5 0 0 0-.5.5v1a.5.5 0 0 0 .5.5h1a.5.5 0 0 0 .5-.5v-1a.5.5 0 0 0-.5-.5z" />
                        </svg>
                    </div>
                    <div>
                        <a class="text-dark text-decoration-none" href="{{ route('products.create') }}"> أضافة منتج</a>
                    </div>
                </div>
                <div class="col-4 d-flex flex-column justify-content-center align-items-center">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                            class="bi bi-tags" viewBox="0 0 16 16">
                            <path
                                d="M3 2v4.586l7 7L14.586 9l-7-7zM2 2a1 1 0 0 1 1-1h4.586a1 1 0 0 1 .707.293l7 7a1 1 0 0 1 0 1.414l-4.586 4.586a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 2 6.586z" />
                            <path
                                d="M5.5 5a.5.5 0 1 1 0-1 .5.5 0 0 1 0 1m0 1a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3M1 7.086a1 1 0 0 0 .293.707L8.75 15.25l-.043.043a1 1 0 0 1-1.414 0l-7-7A1 1 0 0 1 0 7.586V3a1 1 0 0 1 1-1z" />
                        </svg>
                    </div>
                    <div>
                        <a class="text-dark text-decoration-none" href="{{ route('categories.create') }}">
                            اضافة قسم
                        </a>

                    </div>
                </div>
                <div class="col-4 d-flex flex-column justify-content-center align-items-center">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                            class="bi bi-unity" viewBox="0 0 16 16">
                            <path
                                d="M15 11.2V3.733L8.61 0v2.867l2.503 1.466c.099.067.099.2 0 .234L8.148 6.3c-.099.067-.197.033-.263 0L4.92 4.567c-.099-.034-.099-.2 0-.234l2.504-1.466V0L1 3.733V11.2v-.033.033l2.438-1.433V6.833c0-.1.131-.166.197-.133L6.6 8.433c.099.067.132.134.132.234v3.466c0 .1-.132.167-.198.134L4.031 10.8l-2.438 1.433L7.983 16l6.391-3.733-2.438-1.434L9.434 12.3c-.099.067-.198 0-.198-.133V8.7c0-.1.066-.2.132-.233l2.965-1.734c.099-.066.197 0 .197.134V9.8z" />
                        </svg>
                    </div>
                    <div>
                        <a class="text-dark text-decoration-none" href="{{ route('brands.create') }}">
                            اضافة شركة
                        </a>



                    </div>
                </div>
            </div>
            <div class="col-5 row d-flex justify-content-around border p-3">
                <div class="col-4 d-flex flex-column justify-content-center align-items-center">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                            class="bi bi-dropbox" viewBox="0 0 16 16">
                            <path
                                d="M8.01 4.555 4.005 7.11 8.01 9.665 4.005 12.22 0 9.651l4.005-2.555L0 4.555 4.005 2zm-4.026 8.487 4.006-2.555 4.005 2.555-4.005 2.555zm4.026-3.39 4.005-2.556L8.01 4.555 11.995 2 16 4.555 11.995 7.11 16 9.665l-4.005 2.555z" />
                        </svg>
                    </div>
                    <div>
                        <a class="text-dark text-decoration-none" href="{{ route('stock.index') }}">
                            حركه المخزون
                        </a>

                    </div>
                </div>
                <div class="col-4 d-flex flex-column  justify-content-center align-items-center">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                            class="bi bi-cash-stack" viewBox="0 0 16 16">
                            <path d="M1 3a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1zm7 8a2 2 0 1 0 0-4 2 2 0 0 0 0 4" />
                            <path
                                d="M0 5a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v8a1 1 0 0 1-1 1H1a1 1 0 0 1-1-1zm3 0a2 2 0 0 1-2 2v4a2 2 0 0 1 2 2h10a2 2 0 0 1 2-2V7a2 2 0 0 1-2-2z" />
                        </svg>
                    </div>
                    <div>
                        <a class="text-dark text-decoration-none" href="{{ route('expenses.index') }}">
                            اضفة صرف
                        </a>

                    </div>
                </div>
            </div>
            <div class="col-12 row d-flex justify-content-around border p-3">
                <div class="col-6 p-3 d-flex  flex-column justify-content-center align-items-center">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                            class="bi bi-exposure" viewBox="0 0 16 16">
                            <path
                                d="M8.5 4a.5.5 0 0 0-1 0v2h-2a.5.5 0 0 0 0 1h2v2a.5.5 0 0 0 1 0V7h2a.5.5 0 0 0 0-1h-2zm-3 7a.5.5 0 0 0 0 1h5a.5.5 0 0 0 0-1z" />
                            <path d="M8 0a8 8 0 1 0 0 16A8 8 0 0 0 8 0M1 8a7 7 0 1 1 14 0A7 7 0 0 1 1 8" />
                        </svg>
                    </div>
                    <div>
                        <a class="text-dark text-decoration-none" href="{{ route('pos.index') }}">
                            نقطه البيع
                        </a>

                    </div>
                </div>
                <div class="col-6 d-flex  flex-column justify-content-center align-items-center">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" fill="currentColor"
                            class="bi bi-receipt" viewBox="0 0 16 16">
                            <path
                                d="M1.92.506a.5.5 0 0 1 .434.14L3 1.293l.646-.647a.5.5 0 0 1 .708 0L5 1.293l.646-.647a.5.5 0 0 1 .708 0L7 1.293l.646-.647a.5.5 0 0 1 .708 0L9 1.293l.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .708 0l.646.647.646-.647a.5.5 0 0 1 .801.13l.5 1A.5.5 0 0 1 15 2v12a.5.5 0 0 1-.053.224l-.5 1a.5.5 0 0 1-.8.13L13 14.707l-.646.647a.5.5 0 0 1-.708 0L11 14.707l-.646.647a.5.5 0 0 1-.708 0L9 14.707l-.646.647a.5.5 0 0 1-.708 0L7 14.707l-.646.647a.5.5 0 0 1-.708 0L5 14.707l-.646.647a.5.5 0 0 1-.708 0L3 14.707l-.646.647a.5.5 0 0 1-.801-.13l-.5-1A.5.5 0 0 1 1 14V2a.5.5 0 0 1 .053-.224l.5-1a.5.5 0 0 1 .367-.27m.217 1.338L2 2.118v11.764l.137.274.51-.51a.5.5 0 0 1 .707 0l.646.647.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.646.646.646-.646a.5.5 0 0 1 .708 0l.509.509.137-.274V2.118l-.137-.274-.51.51a.5.5 0 0 1-.707 0L12 1.707l-.646.647a.5.5 0 0 1-.708 0L10 1.707l-.646.647a.5.5 0 0 1-.708 0L8 1.707l-.646.647a.5.5 0 0 1-.708 0L6 1.707l-.646.647a.5.5 0 0 1-.708 0L4 1.707l-.646.647a.5.5 0 0 1-.708 0z" />
                            <path
                                d="M3 4.5a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 1 1 0 1h-6a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h6a.5.5 0 0 1 0 1h-6a.5.5 0 0 1-.5-.5m8-6a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5m0 2a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 0 1h-1a.5.5 0 0 1-.5-.5" />
                        </svg>
                    </div>
                    <div>
                        <a class="text-dark text-decoration-none" href="{{ route('seals.index') }}">
                            االجرد
                        </a>

                    </div>
                </div>


            </div>
        </div>

    </div>
@endsection
