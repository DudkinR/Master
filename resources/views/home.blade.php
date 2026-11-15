@extends('layouts.app')
@section('content')
<div class="container my-5">
    <!-- Слайдер -->
    <div id="projectCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner rounded shadow">
            <div class="carousel-item active">
                <img src="https://dostup.org.ua/system/publications/images/000/001/889/original/c038496-budivnyctvo-dijital.jpg?1626021192" class="d-block w-100" alt="Слайд 1">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                    <h5>{{ __('Centralized Project Management') }}</h5>
                    <p>{{ __('Manage all objects in one window.') }}</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://www.softcom.ua/upload/iblock/375/84_main.jpeg" class="d-block w-100" alt="Слайд 2">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                    <h5>{{ __('Flexible Task Allocation') }}</h5>
                    <p>{{ __('Assign masters, team leaders, resources and deadlines.') }}</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://up-study.ua/storage/media/262/conversions/budivnictvo-ogljad-i-rejting-specialnosti-default.jpg" class="d-block w-100" alt="Слайд 3">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                    <h5>{{ __('Real-time Execution Control') }}</h5>
                    <p>{{ __('Monitor the progress of each stage.') }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ __('About the System') }}</h5>
                    <p class="card-text">{{ __('This system is designed for centralized project management in construction. It allows you to efficiently allocate tasks, control execution, and ensure transparency of processes.') }}</p>
                    <a href="" class="btn btn-primary">{{ __('Learn More') }}</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">    
                <div class="card-body">
                    <h5 class="card-title">{{ __('Get Started') }}</h5>
                    <p class="card-text">{{ __('To get started with the system, you need to register or log in to your account. After that, you will be able to create new projects, add objects, and assign tasks.') }}</p>
                    <a href="{{ route('register') }}" class="btn btn-success">{{ __('Register') }}</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary">{{ __('Login') }}</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">{{ __('Latest News') }}</h5>
                    <p class="card-text">{{ __('Follow the latest news and updates about the system. We regularly add new features and improvements to make your work even more efficient.') }}</p>
                    <a href="" class="btn btn-info">{{ __('View News') }}</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

