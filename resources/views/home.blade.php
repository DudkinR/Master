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
<div class="container">
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Про систему</h5>
                    <p class="card-text">Ця система призначена для централізованого управління проектами в будівництві. Вона дозволяє ефективно
                        розподіляти завдання, контролювати виконання та забезпечувати прозорість процесів.</p>
                    <a href="" class="btn btn-primary">Дізнатися більше</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">    
                <div class="card-body">
                    <h5 class="card-title">Почати роботу</h5>
                    <p class="card-text">Щоб почати роботу з системою, вам потрібно зареєструватися або увійти в свій обліковий запис.
                        Після цього ви зможете створювати нові проекти, додавати об'єкти та призначати завдання.</p>
                    <a href="{{ route('register') }}" class="btn btn-success">Зареєструватися</a>
                    <a href="{{ route('login') }}" class="btn btn-secondary">Увійти</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Останні новини</h5>
                    <p class="card-text">Слідкуйте за останніми новинами та оновленнями системи. Ми регулярно додаємо нові функції та покращення,
                        щоб зробити вашу роботу ще ефективнішою.</p>
                    <a href="" class="btn btn-info">Перегля  нути новини</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

