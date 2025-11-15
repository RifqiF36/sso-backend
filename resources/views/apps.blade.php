<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pilih Aplikasi</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
    <style>
        .app-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 1.5rem;
        }
        .app-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
        }
        .app-card h2 {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
<main class="container">
    <h1>Aplikasi yang Tersedia</h1>
    <section class="app-grid">
        @foreach($apps as $app)
            <article class="app-card">
                <h2>{{ $app['name'] }}</h2>
                <p>{{ $app['code'] }}</p>
                <a href="{{ $app['url'] }}" class="contrast">Masuk</a>
            </article>
        @endforeach
    </section>
</main>
</body>
</html>

