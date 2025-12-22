<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sopa de Letras - El Pionero de Valparaíso</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    
    {{-- En producción usa tus assets compilados. Para dev rápido dejo el CDN de Tailwind comentado si lo necesitas --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Estilos base para la grilla */
        .word-search-grid {
            display: grid;
            grid-template-columns: repeat(12, 1fr);
            gap: 2px;
            background-color: #1e3a8a; /* Azul marino porteño */
            padding: 4px;
            max-width: 100%;
            width: 100%;
            margin: 0 auto;
            border-radius: 4px;
            user-select: none;
            touch-action: none;
        }

        @media (min-width: 640px) {
            .word-search-grid {
                max-width: 600px;
            }
        }

        .letter-cell {
            aspect-ratio: 1/1;
            background-color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: clamp(12px, 3.5vw, 22px);
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.1s;
            color: #1f2937;
            text-transform: uppercase;
        }

        /* Feedback Visual */
        .letter-cell.selected {
            background-color: #fde047; /* Amarillo resaltador */
            color: #000;
            transform: scale(0.95);
        }

        .letter-cell.found {
            background-color: #22c55e; /* Verde éxito */
            color: white;
            animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }
        
        .letter-cell.revealed {
            background-color: #93c5fd; /* Azul suave para solución */
            color: #1e3a8a;
        }

        /* Animaciones */
        @keyframes popIn {
            0% { transform: scale(0.5); opacity: 0; }
            100% { transform: scale(1); opacity: 1; }
        }

        /* Botones de Dificultad */
        .difficulty-btn {
            transition: all 0.2s;
            border-width: 2px;
        }
        .difficulty-btn:hover { transform: translateY(-1px); }
        .difficulty-btn.active {
            border-color: #1e3a8a;
            background-color: #eff6ff;
            color: #1e3a8a;
            font-weight: bold;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }
        .difficulty-btn.active-chorizo {
            border-color: #dc2626;
            background-color: #fef2f2;
            color: #991b1b;
        }

        .pionero-header {
            border-bottom: 4px double #1f2937;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
        }
    </style>
</head>

<body class="bg-gray-100 font-[Instrument Sans] text-gray-800 flex flex-col min-h-screen">
        <div class="w-full  mx-auto md:p-4">

    
            <x-header />

    {{-- Navbar Component --}}
    <x-navbar />

    <main class="flex-grow max-w-6xl mx-auto py-10 px-4 w-full">
        
        <div class="pionero-header text-center">
            <h1 class="text-4xl md:text-6xl font-extrabold uppercase tracking-tighter text-gray-900 leading-none">El Pionero</h1>
            <p class="text-xs md:text-sm font-bold text-gray-500 uppercase tracking-[0.3em] mt-2">
                Edición Especial: Pasatiempos de Valparaíso
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
            
            <div class="lg:col-span-4 space-y-6 order-2 lg:order-1">
                
                <div class="bg-white p-5 shadow-lg rounded-lg border border-gray-200">
                    <h3 class="text-xs font-bold uppercase text-gray-400 mb-4 tracking-wider">Configuración del Reto</h3>
                    <div class="space-y-2">
                        <button onclick="setDifficulty('normal')" id="btn-normal" class="difficulty-btn w-full py-3 px-4 rounded border-transparent bg-gray-50 text-left flex justify-between items-center group">
                            <span class="text-sm">🙂 Normal</span>
                            <span class="text-[10px] text-gray-400 uppercase">Horiz/Vert</span>
                        </button>
                        <button onclick="setDifficulty('hard')" id="btn-hard" class="difficulty-btn w-full py-3 px-4 rounded border-transparent bg-gray-50 text-left flex justify-between items-center group">
                            <span class="text-sm">🤔 Difícil</span>
                            <span class="text-[10px] text-gray-400 uppercase">Mixto</span>
                        </button>
                        <button onclick="setDifficulty('chorizo')" id="btn-chorizo" class="difficulty-btn w-full py-3 px-4 rounded border-transparent bg-red-50 text-red-900 text-left flex justify-between items-center hover:bg-red-100">
                            <span class="text-sm font-bold">🌶️ Nivel Chorizo</span>
                            <span class="text-[10px] text-red-400 uppercase font-bold">¡Caos Total!</span>
                        </button>
                    </div>
                </div>

                <div class="bg-white p-5 shadow-lg rounded-lg border border-gray-200">
                    <div class="flex justify-between items-center mb-4 border-b pb-2 border-gray-100">
                        <h3 class="text-xs font-bold uppercase text-gray-400 tracking-wider">Palabras a buscar</h3>
                        <span id="counter" class="text-xs font-bold bg-gray-100 px-2 py-0.5 rounded text-gray-600">0/10</span>
                    </div>
                    
                    <ul id="word-list" class="grid grid-cols-2 gap-2 text-sm font-medium text-gray-600">
                        </ul>
                    
                    <div class="mt-6 flex flex-col gap-2">
                         <button onclick="initGame()" class="w-full bg-gray-900 text-white font-bold py-3 rounded hover:bg-gray-700 transition text-xs tracking-widest uppercase">
                            Reiniciar Tablero
                        </button>
                        <button id="btn-solution" class="w-full text-red-500 hover:text-red-700 font-bold py-2 text-xs transition uppercase border border-transparent hover:border-red-100 rounded">
                            Mostrar Solución
                        </button>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 bg-white p-4 md:p-8 shadow-2xl rounded-lg border border-gray-200 order-1 lg:order-2">
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-2">
                    <div>
                        <h2 class="text-2xl font-bold text-blue-900">La Joya del Pacífico</h2>
                        <p id="current-mode-text" class="text-sm text-gray-400">Modo: Normal</p>
                    </div>
                    <span id="game-status" class="text-xs font-bold uppercase bg-blue-100 text-blue-800 px-4 py-2 rounded-full animate-pulse">
                        Buscando...
                    </span>
                </div>
                
                <div id="grid" class="word-search-grid"></div>
                
                <p class="mt-4 text-center text-xs text-gray-400 italic">
                    Tip: En móviles puedes arrastrar el dedo sobre las letras.
                </p>
            </div>
        </div>
        </div>
    </main>

    {{-- Footer Component --}}
    @if (View::exists('components.footer'))
        <x-footer />
    @else
        <footer class="bg-gray-900 text-white py-8 mt-10">
            <div class="max-w-6xl mx-auto px-4 text-center">
                <p class="font-bold uppercase tracking-widest mb-2">El Pionero de Valparaíso</p>
                <p class="text-xs text-gray-500">&copy; {{ date('Y') }} Todos los derechos reservados.</p>
            </div>
        </footer>
    @endif

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // --- CONSTANTES ---
        const WORDS_POOL = ['ASCENSORES', 'CERROS', 'PUERTO', 'TROLEBUS', 'ESCALERAS', 'GRAFFITI', 'BOHEMIA', 'CHORRILLANA', 'MIRADOR', 'PLAYA'];
        const SIZE = 12;
        
        // Vectores de Dirección (Matemática Pura para movernos en la matriz)
        const VECTORS = {
            H:  { r: 0, c: 1 },   // Horizontal
            V:  { r: 1, c: 0 },   // Vertical
            D1: { r: 1, c: 1 },   // Diagonal \
            D2: { r: -1, c: 1 },  // Diagonal /
            // Inversos (Multiplicamos por -1)
            HR: { r: 0, c: -1 },
            VR: { r: -1, c: 0 },
            D1R:{ r: -1, c: -1 },
            D2R:{ r: 1, c: -1 }
        };

        // --- ESTADO ---
        let state = {
            grid: [],
            solutions: {}, // Mapa palabra -> array de coords 'r-c'
            difficulty: 'normal',
            foundCount: 0,
            gameActive: true,
            selection: {
                active: false,
                start: null, // {r, c}
                currentIds: []
            }
        };

        // --- DOM Elements ---
        const ui = {
            grid: document.getElementById('grid'),
            list: document.getElementById('word-list'),
            status: document.getElementById('game-status'),
            counter: document.getElementById('counter'),
            modeText: document.getElementById('current-mode-text'),
            btnSolution: document.getElementById('btn-solution')
        };

        // --- INICIO ---
        window.setDifficulty = (level) => {
            state.difficulty = level;
            updateDifficultyUI(level);
            initGame();
        };

        window.initGame = () => {
            state.gameActive = true;
            state.foundCount = 0;
            state.selection = { active: false, start: null, currentIds: [] };
            state.solutions = {};

            ui.status.textContent = "¡A JUGAR!";
            ui.status.className = "text-xs font-bold uppercase bg-blue-100 text-blue-800 px-4 py-2 rounded-full";
            ui.btnSolution.disabled = false;
            ui.btnSolution.textContent = "Mostrar Solución";
            ui.counter.textContent = `0/${WORDS_POOL.length}`;

            renderWordList();
            generateGrid();
        };

        function updateDifficultyUI(level) {
            document.querySelectorAll('.difficulty-btn').forEach(b => {
                b.classList.remove('active', 'active-chorizo');
            });
            const btn = document.getElementById(`btn-${level}`);
            if(level === 'chorizo') btn.classList.add('active-chorizo');
            else btn.classList.add('active');

            const labels = { normal: 'Normal', hard: 'Difícil', chorizo: 'Nivel Chorizo 🌶️' };
            ui.modeText.textContent = `Modo: ${labels[level]}`;
        }

        // --- LÓGICA DEL ALGORITMO (EL CEREBRO) ---
        function generateGrid() {
            state.grid = Array(SIZE).fill().map(() => Array(SIZE).fill(''));
            
            // Ordenamos por longitud para poner las difíciles primero
            const sortedWords = [...WORDS_POOL].sort((a, b) => b.length - a.length);

            sortedWords.forEach((word, index) => {
                let placed = false;
                let attempts = 0;
                
                // Obtener direcciones permitidas según dificultad
                const dirs = getAllowedVectors(state.difficulty, index);

                while (!placed && attempts < 300) {
                    const vecName = dirs[Math.floor(Math.random() * dirs.length)];
                    const vec = VECTORS[vecName];
                    
                    // Posición inicial aleatoria
                    const r = Math.floor(Math.random() * SIZE);
                    const c = Math.floor(Math.random() * SIZE);

                    if (canPlace(word, r, c, vec)) {
                        place(word, r, c, vec);
                        placed = true;
                    }
                    attempts++;
                }
                
                if(!placed) console.warn("No cupo:", word);
            });

            renderGridHTML();
        }

        function getAllowedVectors(diff, idx) {
            if (diff === 'normal') return ['H', 'V'];
            
            if (diff === 'hard') {
                // 5 palabras normales, 5 locas
                if (idx < 5) return ['H', 'V']; 
                return ['D1', 'D2', 'HR', 'VR', 'D1R', 'D2R']; 
            }
            
            if (diff === 'chorizo') {
                // TODO vale, excepto lo fácil (Horizontal derecha)
                return ['D1', 'D2', 'D1R', 'D2R', 'HR', 'VR', 'D1', 'D2']; 
            }
            return ['H'];
        }

        function canPlace(word, startR, startC, vec) {
            for (let i = 0; i < word.length; i++) {
                const r = startR + (i * vec.r);
                const c = startC + (i * vec.c);

                // Fuera de limites
                if (r < 0 || r >= SIZE || c < 0 || c >= SIZE) return false;
                
                // Colisión (celda ocupada y letra diferente)
                if (state.grid[r][c] !== '' && state.grid[r][c] !== word[i]) return false;
            }
            return true;
        }

        function place(word, startR, startC, vec) {
            const coords = [];
            for (let i = 0; i < word.length; i++) {
                const r = startR + (i * vec.r);
                const c = startC + (i * vec.c);
                state.grid[r][c] = word[i];
                coords.push(`${r}-${c}`);
            }
            state.solutions[word] = coords;
        }

        // --- RENDERIZADO Y EVENTOS ---
        function renderWordList() {
            ui.list.innerHTML = '';
            WORDS_POOL.forEach(word => {
                const li = document.createElement('li');
                li.id = `list-${word}`;
                li.className = 'flex items-center p-1 transition-all rounded';
                li.innerHTML = `<span class="w-4 h-4 mr-2 border border-gray-300 rounded-sm flex items-center justify-center text-[10px]"></span>${word}`;
                ui.list.appendChild(li);
            });
        }

        function renderGridHTML() {
            ui.grid.innerHTML = '';
            for (let r = 0; r < SIZE; r++) {
                for (let c = 0; c < SIZE; c++) {
                    if (state.grid[r][c] === '') {
                        state.grid[r][c] = String.fromCharCode(65 + Math.floor(Math.random() * 26));
                    }
                    
                    const cell = document.createElement('div');
                    cell.className = 'letter-cell';
                    cell.textContent = state.grid[r][c];
                    cell.dataset.id = `${r}-${c}`;
                    cell.dataset.r = r;
                    cell.dataset.c = c;

                    // Mouse Events
                    cell.addEventListener('mousedown', (e) => startSelect(r, c, e));
                    cell.addEventListener('mouseenter', () => updateSelect(r, c));
                    
                    // Touch Events
                    cell.addEventListener('touchstart', (e) => { e.preventDefault(); startSelect(r, c); });
                    
                    ui.grid.appendChild(cell);
                }
            }

            // Global Events
            window.addEventListener('mouseup', endSelect);
            window.addEventListener('touchend', endSelect);
            
            ui.grid.addEventListener('touchmove', (e) => {
                e.preventDefault();
                const touch = e.touches[0];
                const el = document.elementFromPoint(touch.clientX, touch.clientY);
                if (el && el.classList.contains('letter-cell')) {
                    updateSelect(parseInt(el.dataset.r), parseInt(el.dataset.c));
                }
            }, { passive: false });
        }

        // --- MANEJO DE SELECCIÓN VECTORIAL ---
        function startSelect(r, c, e) {
            if (!state.gameActive) return;
            if(e) e.preventDefault();
            state.selection.active = true;
            state.selection.start = { r, c };
            paintSelection([`${r}-${c}`]);
        }

        function updateSelect(r, c) {
            if (!state.selection.active) return;

            const start = state.selection.start;
            const dx = r - start.r;
            const dy = c - start.c;

            // Detección de línea recta (Horizontal, Vertical o Diagonal Perfecta)
            // Una diagonal perfecta tiene |dx| == |dy|
            const isLine = (dx === 0) || (dy === 0) || (Math.abs(dx) === Math.abs(dy));

            if (isLine) {
                const steps = Math.max(Math.abs(dx), Math.abs(dy));
                const stepR = dx === 0 ? 0 : dx / steps;
                const stepC = dy === 0 ? 0 : dy / steps;

                const path = [];
                for (let i = 0; i <= steps; i++) {
                    const curR = start.r + (i * stepR);
                    const curC = start.c + (i * stepC);
                    path.push(`${curR}-${curC}`);
                }
                paintSelection(path);
            }
        }

        function paintSelection(ids) {
            // Limpiar selección previa visual (pero no las encontradas)
            document.querySelectorAll('.letter-cell.selected').forEach(el => el.classList.remove('selected'));
            
            state.selection.currentIds = ids;
            ids.forEach(id => {
                const el = document.querySelector(`[data-id="${id}"]`);
                if (el && !el.classList.contains('found')) {
                    el.classList.add('selected');
                }
            });
        }

        function endSelect() {
            if (!state.selection.active) return;
            state.selection.active = false;
            
            // Validar palabra
            const wordStr = state.selection.currentIds.map(id => {
                const [r, c] = id.split('-');
                return state.grid[r][c];
            }).join('');

            const reversed = wordStr.split('').reverse().join('');
            
            // Chequear si existe en pool
            let found = null;
            if (WORDS_POOL.includes(wordStr)) found = wordStr;
            else if (WORDS_POOL.includes(reversed)) found = reversed;

            if (found) {
                // Validar si ya fue encontrada antes
                const listEl = document.getElementById(`list-${found}`);
                if (!listEl.classList.contains('text-green-600')) {
                    triggerFound(found);
                } else {
                    paintSelection([]); // Limpiar si ya estaba
                }
            } else {
                paintSelection([]); // Borrar selección errónea
            }
        }

        function triggerFound(word) {
            // Marcar Grid
            state.selection.currentIds.forEach(id => {
                const el = document.querySelector(`[data-id="${id}"]`);
                el.classList.remove('selected');
                el.classList.add('found');
            });

            // Marcar Lista
            const listEl = document.getElementById(`list-${word}`);
            listEl.classList.add('text-green-600', 'font-bold', 'bg-green-50');
            listEl.querySelector('span').textContent = '✓';
            listEl.querySelector('span').className = 'w-4 h-4 mr-2 bg-green-500 text-white rounded-sm flex items-center justify-center text-[10px]';

            state.foundCount++;
            ui.counter.textContent = `${state.foundCount}/${WORDS_POOL.length}`;

            if (state.foundCount === WORDS_POOL.length) {
                state.gameActive = false;
                ui.status.textContent = "¡COMPLETADO!";
                ui.status.className = "text-xs font-bold uppercase bg-green-500 text-white px-6 py-2 rounded-full shadow-lg transform scale-110 transition";
                setTimeout(() => alert("¡Excelente! Has completado el desafío."), 300);
            }
        }

        // --- SOLUCIÓN ---
        ui.btnSolution.addEventListener('click', () => {
            if(!confirm('¿Seguro? Se acabará el juego.')) return;
            state.gameActive = false;
            paintSelection([]); // Limpiar selección usuario
            
            Object.entries(state.solutions).forEach(([word, coords]) => {
                // Marcar Grid
                coords.forEach(id => {
                    const el = document.querySelector(`[data-id="${id}"]`);
                    if (!el.classList.contains('found')) el.classList.add('revealed');
                });
                // Marcar Lista
                const listEl = document.getElementById(`list-${word}`);
                if (!listEl.classList.contains('text-green-600')) {
                    listEl.classList.add('text-blue-500', 'line-through', 'opacity-50');
                }
            });
            ui.btnSolution.textContent = "Solución Revelada";
            ui.btnSolution.disabled = true;
        });

        // Init inicial
        setDifficulty('normal');
    });
</script>
</body>
</html>