<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSO Mitra - Login</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.min.css">
</head>
<body>
<main class="container">
    <article>
        <h1>SSO Mitra</h1>
        <p>Gunakan akun SSO Anda untuk melanjutkan.</p>
        <form method="post" action="{{ url('/login') }}">
            @csrf
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required autofocus>

            <label for="password">Password</label>
            <div style="display:flex; gap:.5rem; align-items:center;">
                <input type="password" id="password" name="password" required style="flex:1;">
                <button type="button" id="togglePassword" data-visible="false">Intip</button>
            </div>

            <button type="submit">Masuk</button>
        </form>
    </article>
</main>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const toggle = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');

        if (!toggle || !passwordInput) {
            return;
        }

        toggle.addEventListener('click', function () {
            const isVisible = toggle.getAttribute('data-visible') === 'true';
            passwordInput.type = isVisible ? 'password' : 'text';
            toggle.setAttribute('data-visible', (!isVisible).toString());
            toggle.textContent = isVisible ? 'Intip' : 'Sembunyikan';
        });
    });
</script>
</body>
</html>

