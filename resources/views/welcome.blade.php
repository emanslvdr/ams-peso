<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
        <link rel="stylesheet" href="{{ asset('home/style/style.css') }}">
        <title>{{ config('app.name', 'PESO') }}</title>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <div class="mobile-overlay"></div>
        <header>
            <nav class="navbar section-content">
                <a href="#" class="nav-logo">
                    <h2 class="logo-text">AMS</h2>
                </a>
                <ul class="nav-menu">
                    <button id="menu-close-button" class="fas fa-times"></button>
                    <li class="nav-item">
                        <a href="#hero" class="nav-link">Home</a>
                    </li>
                    <li class="nav-item">
                        <a href="#about" class="nav-link">About</a>
                    </li>
                    <li class="nav-item">
                        <a href="#contact" class="nav-link">Contact</a>
                    </li>
                    @if (Route::has('login'))
        @auth
            <li class="nav-item">
                <a href="{{ url('/dashboard') }}" class="nav-link">Dashboard</a>
            </li>
        @else
            <li class="nav-item">
                <a href="{{ route('login') }}" class="nav-link">Login</a>
            </li>

            @if (Route::has('register'))
                {{-- <li class="nav-item">
                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                </li> --}}
                <li class="nav-item">
                    <a href="{{ route('register') }}" class="nav-link">Register</a>
                </li>
            @endif
        @endauth
    @endif
                </ul>
                <button id="menu-open-button" class="fas fa-bars"></button>
            </nav>
        </header>
    
        <main>
            <section class="hero-section gradient-background" id="hero">
                <div class="section-content">
                    <div class="hero-details">
                        <h2 class="title"><span>A</span>pplicant <span>M</span>anagement <span>S</span>ystem</h2>
                        <h3 class="subtitle">Smart Hiring, Faster Matches!
                        </h3>
                        <p class="description">Revolutionizing the way PESO connects job seekers and
                            employers through
                            intelligent, AI-powered
                            recruitment solutions — making hiring faster, smarter, and more efficient than ever before.</p>
                        <a href="#" class="button">Submit application</a>
                    </div>
                    <div class="hero-image-wrapper">
                        <img src="{{ asset('home/images/front2.png') }}" alt="Hero" class="hero-image">
                    </div>
                </div>
            </section>
    
            <section class="about-section" id="about">
                <div class="section-content">
                    <div class="about-image-wrapper">
                        <img src="{{ asset('home/images/aboutImage.jpg') }}" alt="about" class="about-image">
                    </div>
                    <div class="about-details">
                        <h2 class="section-title">About Us</h2>
                        <p class="text">The Applicant Management System (AMS) with Artificial Intelligence (AI) is designed
                            for the Public Employment Service Office (PESO) in Tarlac City to streamline and enhance their
                            recruitment process.This system utilizes AI technologies to efficiently classify and rank
                            job
                            applicants based on various criteria such as qualifications, skills, and job relevance. The goal
                            of this AMS is to provide PESO with an intelligent, automated tool that simplifies applicant
                            screening, improves job matching accuracy, and accelerates the employment process for both job
                            seekers and employers in the local community.</p>
                        <div class="social-link-list">
                            <a href="#" class="social-link"><i class="fa-brands fa-facebook"></i></a>
                        </div>
                    </div>
                </div>
            </section>
    
            <section class="contact-section gradient-background" id="contact">
                <h2 class="section-title">Contact Us</h2>
                <div class="section-content">
                    <ul class="contact-info-list">
                        <li class="contact-info">
                            <i class="fa-solid fa-location-crosshairs"></i>
                            <p>Barangay 123, Tarlac City, Tarlac</p>
                        </li>
                        <li class="contact-info">
                            <i class="fa-solid fa-envelope"></i>
                            <p>pesoTarlacCity@example.com</p>
                        </li>
                        <li class="contact-info">
                            <i class="fa-solid fa-phone"></i>
                            <p>+123 456 789</p>
                        </li>
                        <li class="contact-info">
                            <i class="fa-solid fa-clock"></i>
                            <p>Monday - Friday: 9:00 AM - 5:00 PM</p>
                        </li>
                        <li class="contact-info">
                            <i class="fa-solid fa-clock"></i>
                            <p>Saturday: 10:00 AM - 3:00 PM</p>
                        </li>
                        <li class="contact-info">
                            <i class="fa-solid fa-clock"></i>
                            <p>Sunday: Closed</p>
                        </li>
                        <li class="contact-info">
                            <i class="fa-solid fa-globe"></i>
                            <p>www.pesoTarlacCityExample.com</p>
                        </li>
                    </ul>
    
                    <form action="#" class="contact-form">
                        <input type="text" placeholder="Your name" class="form-input" required>
                        <input type="email" placeholder="Your email" class="form-input" required>
                        <textarea placeholder="Your message" class="form-input" required></textarea>
                        <button class="submit-button">Submit</button>
                    </form>
                </div>
            </section>
    
            <footer class="footer-section">
                <div class="section-content">
                    <p class="copyright-text">© 2025 Tarlac City PESO</p>
                    <div class="social-link-list">
                        <a href="#" class="social-link"><i class="fa-brands fa-facebook"></i></a>
                    </div>
                    <p class="policy-text">
                        <a href="#" class="policy-link">Privacy Policy</a>
                        <span class="separator">•</span>
                        <a href="#" class="policy-link">Service Policy</a>
                    </p>
                </div>
            </footer>
        </main>
    
        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
        <script src="{{ asset('home/js/script.js') }}"></script>
    </body>
    
</html>
