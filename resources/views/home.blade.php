@extends('layouts.app')

@section('content')
<div class="container my-5">

    <!-- Слайдер -->
    <div id="projectCarousel" class="carousel slide mb-5" data-bs-ride="carousel">
        <div class="carousel-inner rounded shadow">
            <div class="carousel-item active">
                <img src="https://dostup.org.ua/system/publications/images/000/001/889/original/c038496-budivnyctvo-dijital.jpg?1626021192" class="d-block w-100" alt="Слайд 1">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                    <h5>Централізоване управління проектами</h5>
                    <p>Керуйте всіма об'єктами в одному вікні.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://www.softcom.ua/upload/iblock/375/84_main.jpeg" class="d-block w-100" alt="Слайд 2">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                    <h5>Гнучкий розподіл завдань</h5>
                    <p>Призначайте майстрів, бригадирів, ресурси та строки.</p>
                </div>
            </div>
            <div class="carousel-item">
                <img src="https://up-study.ua/storage/media/262/conversions/budivnictvo-ogljad-i-rejting-specialnosti-default.jpg" class="d-block w-100" alt="Слайд 3">
                <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-2">
                    <h5>Контроль виконання в реальному часі</h5>
                    <p>Слідкуйте за ходом виконання на кожному етапі.</p>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

