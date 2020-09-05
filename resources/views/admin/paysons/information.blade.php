@extends('layouts.admin')

@section('content')

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashborad')}}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{route('admin.listePaysons')}}"> الفلاحون </a>
                                </li>
                                <li class="breadcrumb-item active"> معطيات الفلاح
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>

            </div>
            <input name="id" value="{{$user -> id}}" type="hidden">

            <div class="form-group">
                <div class="text-center">
                    <img
                        @if(isset($user->photo) and (!$user->photo==''))
                        src="{{asset('assets/'.$user->photo)}}"
                        @else
                        src="{{asset('assets/images/users/default_user.png')}}"
                        @endif
                        class="rounded-circle  height-150" alt="صورة القسم  ">
                </div>
            </div>
            <div class="content-body">
                <!-- Basic form layout section start -->
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-form"> السيد {{$user->name}} {{ $user->prenom}} </h4>
                                    <a class="heading-elements-toggle"><i
                                            class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                @include('admin.includes.alerts.success')
                                @include('admin.includes.alerts.errors')
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <h4 class="card-title" id="basic-layout-form"> رقم الهاتف: {{$user->phone}} </h4>
                                        <h4 class="card-title" id="basic-layout-form"> نقطة الري:
                                            @foreach ($vannes as $vanne)
                                                {{ $vanne->numero_van}}
                                            @endforeach
                                        </h4>
                                            <h4 class="card-title" id="basic-layout-form">   البريد الالكتروني: {{$user->email}}</h4>
                                        @foreach ($vannes as $vanne)
                                            <h4 class="card-title" id="basic-layout-form"> <a href="{{route('admin.facture',['idUser'=>$user->id])}}" style="display: block">   كشف استهلاك نقطة الري:

                                                {{ $vanne->numero_van}}

                                      </a>
                                            </h4>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- // Basic form layout section end -->
            </div>
        </div>
    </div>

@endsection
