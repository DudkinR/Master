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
        <button class="carousel-control-prev" type="button" data-bs-target="#projectCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#projectCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- Опис функціоналу -->
    <div class="row text-center">
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">📋 Планування завдань</h5>
                    <p class="card-text">Керівник створює завдання, визначає обсяг, строки та потрібні ресурси. Усе — в кілька кліків.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">👷 Призначення виконавців</h5>
                    <p class="card-text">Майстер обирає бригаду, формує склад, уточнює графік та техніку для виконання.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm border-0">
                <div class="card-body">
                    <h5 class="card-title">⏱️ Облік і контроль</h5>
                    <p class="card-text">Робітники фіксують час початку/завершення, наявність ТМЦ та техніки, вказують проблеми.</p>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

