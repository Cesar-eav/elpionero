<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $revista->titulo }} - {{ now()->format('d de F de Y') }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            line-height: 1.3;
            font-size: 11pt;
            background-color: #f8f8f2;
            color: #333;
            margin: 20px;
        }
        .container {
            max-width: 1024px;
            margin: 20px auto;
            padding: 30px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 0;
            display: grid;
            grid-template-columns: 1fr 3fr 1fr;
            gap: 20px;
        }
        .header {
            grid-column: 1 / -1;
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #ccc;
            margin-bottom: 20px;
        }
        .header-image {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .titulo-periodico {
            font-family: 'Baskerville', serif;
            font-size: 36pt;
            font-weight: bold;
            color: #000;
            margin-bottom: 5px;
            letter-spacing: 0.02em;
        }
        .bajada {
            font-style: italic;
            font-size: 14pt;
            color: #555;
            margin-bottom: 10px;
        }
        .fecha {
            font-size: 10pt;
            color: #777;
        }
        .main-content {
            grid-column: 2 / 3;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
        }
        .sidebar-left, .sidebar-right {
            padding: 15px;
            background-color: #f0f0f0;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 9pt;
            line-height: 1.4;
        }
        .sidebar-left h2, .sidebar-right h2 {
            font-family: 'Baskerville', serif;
            font-size: 16pt;
            color: #222;
            margin-top: 0;
            margin-bottom: 10px;
            border-bottom: 1px solid #aaa;
            padding-bottom: 5px;
        }
        .sidebar-news {
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            background-color: #fff;
        }
        .sidebar-news h4 {
            font-size: 10pt;
            font-weight: bold;
            margin-top: 0;
            margin-bottom: 5px;
            color: #2f4f4f;
        }
        .sidebar-news p {
            font-size: 8pt;
            line-height: 1.2;
            margin-bottom: 0;
        }
        .sidebar-ad {
            margin-bottom: 15px;
            text-align: center;
        }
        .sidebar-ad img {
            max-width: 100%;
            height: auto;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        .articulo {
            margin-bottom: 25px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .articulo:last-child {
            border-bottom: none;
        }
        .articulo-titulo {
            font-family: 'Baskerville', serif;
            font-size: 20pt;
            font-weight: bold;
            color: #000;
            margin-top: 0;
            margin-bottom: 8px;
            line-height: 1.1;
        }
        .articulo-autor {
            font-size: 9pt;
            color: #666;
            font-style: italic;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
        }
        .autor-imagen {
            width: 40px;
            height: 40px;
            margin-right: 10px;
        }
        .autor-imagen img {
            max-width: 100%;
            height: auto;
            border-radius: 50%;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        }
        .articulo-imagen {
            max-width: 20%;
            height: auto;
            margin-bottom: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.15);
        }
        .publicidad {
            grid-column: 1 / -1;
            background-color: #e9e9e9;
            border: 1px solid #ccc;
            padding: 15px;
            text-align: center;
            font-size: 9pt;
            color: #666;
            margin-top: 30px;
            border-radius: 0;
        }
        .footer {
            grid-column: 1 / -1;
            text-align: center;
            font-size: 10pt;
            color: #777;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #ccc;
        }
        .ver-revista-link {
            grid-column: 1 / -1;
            display: block;
            margin-top: 20px;
            text-align: center;
            font-size: 11pt;
        }
        .ver-revista-button {
            background-color: #444;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s ease;
        }
        .ver-revista-button:hover {
            background-color: #222;
        }
        .page-number {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 8pt;
            color: #999;
        }
        /* Estilos para la tipografía */
        @font-face {
            font-family: 'Times New Roman';
            src: local('Times New Roman'), url('path/to/times-new-roman.woff2') format('woff2'), url('path/to/times-new-roman.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }
        @font-face {
            font-family: 'Baskerville';
            src: local('Baskerville'), url('path/to/baskerville.woff2') format('woff2'), url('path/to/baskerville.woff') format('woff');
            font-weight: normal;
            font-style: normal;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="header">
            <img src="{{ asset('storage/portada.png') }}" alt="Portada de Periódico" class="header-image">
            <h1 class="titulo-periodico">El futuro de los ascensores</h1>
            <p class="bajada">El futuro de los ascensores</p>
            <p class="fecha">{{ now()->format('d de F de Y') }}</p>
        </header>

        <div class="sidebar-left">
            <h2>Noticias de Última Hora</h2>
            <div class="sidebar-news">
                <h4>Nuevo Proyecto Portuario</h4>
                <p>Se anuncia una inversión millonaria para modernizar las instalaciones del puerto de Valparaíso.</p>
            </div>
            <div class="sidebar-news">
                <h4>Debate sobre el Transporte Público</h4>
                <p>Concejales discuten propuestas para mejorar la red de transporte en la ciudad.</p>
            </div>
            <h2 style="margin-top: 20px;">Publicidad</h2>
            <div ><img src="{{ asset('/storage/publicidad/manosalarte.jpeg') }}" alt="Anuncio Pequeño"></div>
            {{-- <div class="sidebar-ad"><img src="https://picsum.photos/120/90?random=3" alt="Otro Anuncio Pequeño"></div> --}}
        </div>

        <div class="main-content">
            @if ($articulos->isNotEmpty())
                @foreach ($articulos->chunk(ceil($articulos->count() / 2)) as $columnArticulos)
                    <div>
                        @foreach ($columnArticulos as $articulo)
                            <div class="articulo">
                                <h3 class="articulo-titulo">{{ $articulo->titulo }}</h3>
                                @if ($articulo->autor)
                                    <div class="articulo-autor">
                                        @if ($articulo->imagen_autor)
                                            <div class="autor-imagen">
                                                <img src="{{ asset($articulo->imagen_autor) }}"  alt="{{ $articulo->autor ?? 'Autor' }}">
                                            </div>
                                        @endif
                                        Por: {{ $articulo->autor }}
                                    </div>
                                @endif

                                <p class="text-gray-800">{!! nl2br(e($articulo->contenido)) !!}</p>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <p>No hay artículos en esta revista.</p>
            @endif
        </div>

        <div class="sidebar-right">
            <h2>Columnas de Opinión</h2>


            <div class="sidebar-news">
 @if ($articulos->isNotEmpty())
                @foreach ($articulos->chunk(ceil($articulos->count() / 2)) as $columnArticulos)
                    <div>
                        @foreach ($columnArticulos as $articulo)
                            <div class="articulo">
                                <h4 class="articulo-titulo">{{ $articulo->titulo }}</h4>
                                @if ($articulo->autor)
                                    <div class="articulo-autor">
                                        @if ($articulo->imagen_autor)
                                            <div class="autor-imagen">
                                                <img src="{{ asset($articulo->imagen_autor) }}"  alt="{{ $articulo->autor ?? 'Autor' }}">
                                            </div>
                                        @endif
                                        Por: {{ $articulo->autor }}
                                    </div>
                                @endif

                               
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @else
                <p>No hay artículos en esta revista.</p>
            @endif


            </div>

            <h2 style="margin-top: 20px;">Publicidad</h2>
            <div class="sidebar-ad"><img src="https://picsum.photos/160/160?random=7" alt="Anuncio Mediano"></div>
        </div>

        <div class="publicidad">
            <h4 style="color: #222; margin-bottom: 5px;">Aviso Importante</h4>
            <p style="font-size: 10pt;">Información relevante para nuestros lectores.</p>
            <img src="https://picsum.photos/728/90?random=8" alt="Banner Principal" style="max-width: 100%; height: auto; margin-top: 10px; border-radius: 4px;">
        </div>

        <div class="footer">
            <p>{{ __('© 2025 El Pionero de Valparaíso') }} - {{ $ubicacion }}</p>
            <p>Especial Ascensores - Página 1</p>
        </div>

        <div class="ver-revista-link">
            <a href="{{ route('revistas.show', $revista) }}" class="ver-revista-button">{{ __('Leer la Edición Completa') }}</a>
        </div>

        <div class="page-number">1</div>
    </div>
</body>
</html>