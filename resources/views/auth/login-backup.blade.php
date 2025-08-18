<!DOCTYPE html>
<html lang="pt-BR" class="dark">
<head>
    <meta charset="UTF-8">
    <title>Área Restrita - Prefeitura Municipal de Marumbi-PR</title>
    <meta http-equiv="x-ua-compatible" content="ie=edge"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    
    <!-- Favicons -->
    <link rel="apple-touch-icon" sizes="57x57" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/apple-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/apple-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/apple-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/apple-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/apple-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/apple-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/apple-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/apple-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/apple-icon-180x180.png">
    <link rel="icon" type="image/png" sizes="192x192" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/android-icon-192x192.png">
    <link rel="icon" type="image/png" sizes="32x32" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="96x96" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/favicon-96x96.png">
    <link rel="icon" type="image/png" sizes="16x16" href="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/favicon-16x16.png">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="msapplication-TileImage" content="https://diario.marumbi.pr.gov.br/themes/diario-twd/assets/css/images/favicons/ms-icon-144x144.png">

    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .bounce-loader {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .bounce-loader > div {
            width: 18px;
            height: 18px;
            background-color: #3B82F6;
            border-radius: 100%;
            display: inline-block;
            animation: sk-bouncedelay 1.4s infinite ease-in-out both;
        }
        .bounce-loader .bounce1 {
            animation-delay: -0.32s;
        }
        .bounce-loader .bounce2 {
            animation-delay: -0.16s;
        }
        @keyframes sk-bouncedelay {
            0%, 80%, 100% {
                transform: scale(0);
            } 40% {
                transform: scale(1.0);
            }
        }
        
        .ajax_load, .ajax_preload {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.8);
            z-index: 9999;
            justify-content: center;
            align-items: center;
        }
        
        .message {
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
            font-size: .875em;
        }
        .message.error {
            background: #d15562;
            color: #fff;
        }
        .message.success {
            background: #90e38d;
            color: #333;
        }
        .header__logo {
            margin-top: -6px;
        }
    </style>
</head>
<body class="bg-gradient-to-r from-slate-300 to-slate-500">

<div class="ajax_load">
    <div class="bounce-loader">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>
<div class="ajax_preload">
    <div class="bounce-loader">
        <div class="bounce1"></div>
        <div class="bounce2"></div>
        <div class="bounce3"></div>
    </div>
</div>
<div class='ajax_response'></div>
<div class="ajax_modal"></div>

<div class="w-[70%] sm:w-full xsm:w-full sm:p-4 xsm:p-4 mx-auto grid h-screen place-items-center">
    <div>
        @if ($errors->any() || session('status'))
        <div class="bg-slate-700 text-center text-amber-300/80 font-semibold p-2">
            @if ($errors->any())
                Por favor, verifique os dados informados!
            @endif
            @if (session('status'))
                {{ session('status') }}
            @endif
        </div>
        @endif

        <div>
            <form class="bg-white shadow-xl mb-4 flex flex-wrap justify-center" method="POST" action="{{ route('login') }}" enctype="multipart/form-data">
                @csrf
                
                <div class="w-2/4 md:w-full sm:w-full xsm:w-full p-6 flex flex-col justify-between">
                    <div class="mx-auto flex items-center justify-center area_logo rounded p-1">
                        <a href="{{ route('home') }}" title="Página inicial" class="mx-auto">
                           
                        </a>
                    </div>

                    <div class="relative my-1">
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center py-2">
                            <span class="px-2 text-sm bg-gradient-to-r from-blue-300 to-amber-500 text-center text-blue-900/50 uppercase font-medium rounded">Prefeitura Municipal de Marumbi-PR</span>
                        </div>
                    </div>

                    <div class="flex flex-wrap justify-center">
                        <p class="font-semibold text-gray-500">Acesso público ao portal</p>
                        <div class="w-full">
                            <a href="{{ route('home') }}">
                                <div class="w-full bg-red-600/70 hover:bg-red-600/90 text-white font-bold py-2 px-4 focus:outline-none focus:shadow-outline shadow-lg rounded text-center hover:cursor-pointer flex items-center justify-center gap-2" type="button">
                                    <span>Voltar ao Portal</span> 
                                    <span aria-hidden="true">&rarr;</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
                
                <div class="w-2/4 md:w-full sm:w-full xsm:w-full bg-blue-600 p-6 text-white">
                    <p class="mb-8 text-3xl flex items-center">
                        <svg width="32" height="32" viewBox="0 0 512 512" class="inline-block fill-current h-8 w-8 mr-2">
                            <path d="m64 496l0-256 48 0 0-80c0-71 57-128 128-128l16 0c71 0 128 57 128 128l0 80 48 0 0 256z m172-131l-12 83 48 0-12-83c12-5 20-17 20-30 0-18-14-32-32-32-18 0-32 14-32 32 0 13 8 25 20 30z m100-197c0-49-39-88-88-88-49 0-88 39-88 88l0 72 176 0z"/>
                        </svg>
                        Fazer Login
                    </p>

                    <div class="ajax_response w-full">
                        @if ($errors->any())
                        <div class="message error">
                            @foreach ($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <div class="ajax_login_response w-full"></div>

                    <div class="mb-4">
                        <input class="appearance-none rounded border w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline @error('email') border-red-500 @enderror" 
                               name="email" 
                               type="email" 
                               value="{{ old('email') }}" 
                               placeholder="Informe seu e-mail"
                               required 
                               autofocus 
                               autocomplete="username"/>
                    </div>
                    
                    <div class="">
                        <input class="appearance-none rounded border w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline @error('password') border-red-500 @enderror" 
                               name="password" 
                               type="password" 
                               placeholder="Informe sua senha"
                               required 
                               autocomplete="current-password"/>
                    </div>

                    <!-- Remember Me -->
                    <div class="mb-4">
                        <label for="remember" class="inline-flex items-center text-white">
                            <input id="remember" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <span class="ms-2 text-sm">Lembrar-me</span>
                        </label>
                    </div>

                    <button class="block rounded shadow-lg text-lg w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-4 focus:outline-none focus:shadow-outline uppercase tracking-wider duration-200" type="submit">
                        Entrar
                    </button>

                    @if (Route::has('password.request'))
                    <a class="block w-full text-sm text-right text-white hover:text-gray-300 mt-2" href="{{ route('password.request') }}">
                        Recuperar Senha
                    </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form');
    const ajaxLoad = document.querySelector('.ajax_load');
    
    form.addEventListener('submit', function() {
        ajaxLoad.style.display = 'flex';
    });
    
    // Hide preloader after page load
    window.addEventListener('load', function() {
        const preloader = document.querySelector('.ajax_preload');
        if (preloader) {
            preloader.style.display = 'none';
        }
    });
});
</script>

</body>
</html>
