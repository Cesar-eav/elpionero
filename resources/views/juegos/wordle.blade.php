<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portle - El Pionero de Valparaíso</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* --- ESTILOS DEL TABLERO --- */
        .board-row {
            display: grid;
            gap: 4px;
            margin-bottom: 4px;
            width: 100%;
            /* El contenedor sigue siendo ancho, pero... */
            max-width: 500px; 
            /* ...centramos el contenido si las celdas son pequeñas */
            justify-content: center;
        }

        .tile {
            aspect-ratio: 1 / 1;
            border: 2px solid #d1d5db;
            display: flex;
            justify-content: center;
            align-items: center;
            font-weight: 800; /* Un poco más negrita */
            text-transform: uppercase;
            user-select: none;
            transition: all 0.2s ease;
            /* El tamaño final se define en el JS con grid-template-columns */
        }

        .tile.filled {
            border-color: #6b7280;
            animation: pop 0.1s;
        }

        /* Colores de estado */
        .tile.correct {
            background-color: #22c55e; border-color: #22c55e; color: white;
            animation: flip 0.6s ease;
        }
        .tile.present {
            background-color: #eab308; border-color: #eab308; color: white;
            animation: flip 0.6s ease;
        }
        .tile.absent {
            background-color: #6b7280; border-color: #6b7280; color: white;
            animation: flip 0.6s ease;
        }

        /* --- ESTILOS DEL TECLADO --- */
        .key {
            height: 54px; /* Un poco más bajo para móviles */
            border-radius: 4px;
            border: none;
            background-color: #d1d5db;
            color: #1f2937;
            font-weight: bold;
            cursor: pointer;
            user-select: none;
            transition: all 0.1s;
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.85rem;
            touch-action: manipulation;
        }

        .key:active { transform: scale(0.95); background-color: #9ca3af; }
        /* Hover solo en desktop para evitar sticky hover en móvil */
        @media (hover: hover) {
            .key:hover { background-color: #9ca3af; }
        }

        .key.correct { background-color: #22c55e; color: white; border-color: #22c55e; }
        .key.present { background-color: #eab308; color: white; border-color: #eab308; }
        .key.absent { background-color: #6d806b; color: white; border-color: #6b7280; }

        .key.large { flex: 1.5; font-size: 0.75rem; }

        /* Botón ENVIAR en verde */
        .key[data-key="ENTER"] {
            background-color: #22c55e;
            color: white;
        }

        .key[data-key="ENTER"]:hover {
            background-color: #16a34a;
        }

        /* --- ANIMACIONES --- */
        @keyframes pop { 50% { transform: scale(1.1); } }
        @keyframes flip { 
            0% { transform: rotateX(0); } 
            50% { transform: rotateX(90deg); } 
            100% { transform: rotateX(0); } 
        }
        @keyframes shake {
            10%, 90% { transform: translateX(-1px); }
            20%, 80% { transform: translateX(2px); }
            30%, 50%, 70% { transform: translateX(-4px); }
            40%, 60% { transform: translateX(4px); }
        }
        .shake { animation: shake 0.4s linear; }
        
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-15px); }
        }
        .bounce { animation: bounce 0.5s ease-in-out; }

        /* --- ESTILOS DEL SELECTOR DE LONGITUD --- */
        .length-selector {
            padding: 0.375rem 0.875rem;
            font-size: 0.75rem;
            font-weight: 600;
            border-radius: 9999px;
            border: 2px solid #e5e7eb;
            background-color: white;
            color: #6b7280;
            cursor: pointer;
            transition: all 0.2s ease;
            user-select: none;
        }
        .length-selector:hover {
            border-color: #3b82f6;
            color: #3b82f6;
            transform: translateY(-1px);
        }
        .length-selector.active {
            background-color: #3b82f6;
            border-color: #3b82f6;
            color: white;
            box-shadow: 0 4px 6px -1px rgba(59, 130, 246, 0.3);
        }
        .length-selector:active {
            transform: scale(0.95);
        }
    </style>
</head>

<body class="bg-slate-50 min-h-screen flex flex-col font-sans text-gray-800">
    
    <div class="w-full flex-grow flex flex-col">
        <x-header />
        <x-navbar />

        <main class="flex-grow w-full max-w-lg mx-auto p-2 md:p-4 flex flex-col items-center justify-start sm:justify-center">
            
            <div class="text-center mb-4 md:mb-6 w-full mt-2 md:mt-0">
                <h1 class="text-3xl md:text-4xl font-black text-gray-900 tracking-tighter mb-1">
                    PORTLE
                </h1>
                <div class="mt-2 flex justify-center items-center gap-3">
                    <button onclick="showInstructions()" class="text-xs bg-gray-200 hover:bg-gray-300 px-3 py-1 rounded-full transition flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Ayuda
                    </button>
                    <span id="word-length-badge" class="text-xs bg-blue-100 text-blue-800 px-3 py-1 rounded-full font-bold border border-blue-200">
                        Todas
                    </span>
                </div>

                <!-- Selector de Longitud de Palabra -->
                <div class="mt-3 w-full px-4">
                    <p class="text-xs text-gray-600 text-center mb-2 font-semibold">Longitud de palabra:</p>
                    <div class="flex justify-center gap-1.5 flex-wrap">
                        <button onclick="selectWordLength(null)" class="length-selector active" data-length="all">
                            Todas
                        </button>
                        <button onclick="selectWordLength(5)" class="length-selector" data-length="5">
                            5
                        </button>
                        <button onclick="selectWordLength(6)" class="length-selector" data-length="6">
                            6
                        </button>
                        <button onclick="selectWordLength(7)" class="length-selector" data-length="7">
                            7
                        </button>
                        <button onclick="selectWordLength(8)" class="length-selector" data-length="8">
                            8
                        </button>
                        <button onclick="selectWordLength(9)" class="length-selector" data-length="9">
                            9
                        </button>
                        <button onclick="selectWordLength(10)" class="length-selector" data-length="10">
                            10+
                        </button>
                    </div>
                </div>
            </div>

            <div id="message-container" class="fixed top-20 left-0 right-0 flex justify-center pointer-events-none z-50">
                </div>

            <div id="board-container" class="w-full flex flex-col items-center mb-4 md:mb-8 flex-grow justify-center max-h-[420px]">
                </div>

            <div class="w-full max-w-md bg-white p-1.5 md:p-2 rounded-xl shadow-sm border border-gray-200 select-none select-none mb-4 md:mb-0">
                <div id="keyboard" class="flex flex-col gap-1.5">
                    <div class="flex gap-1">
                        <button class="key" data-key="Q">Q</button><button class="key" data-key="W">W</button><button class="key" data-key="E">E</button><button class="key" data-key="R">R</button><button class="key" data-key="T">T</button><button class="key" data-key="Y">Y</button><button class="key" data-key="U">U</button><button class="key" data-key="I">I</button><button class="key" data-key="O">O</button><button class="key" data-key="P">P</button>
                    </div>
                    <div class="flex gap-1 px-2 md:px-4">
                        <button class="key" data-key="A">A</button><button class="key" data-key="S">S</button><button class="key" data-key="D">D</button><button class="key" data-key="F">F</button><button class="key" data-key="G">G</button><button class="key" data-key="H">H</button><button class="key" data-key="J">J</button><button class="key" data-key="K">K</button><button class="key" data-key="L">L</button><button class="key" data-key="Ñ">Ñ</button>
                    </div>
                    <div class="flex gap-1">
                        <button class="key large font-black text-xs" data-key="ENTER">ENVIAR</button>
                        <button class="key" data-key="Z">Z</button><button class="key" data-key="X">X</button><button class="key" data-key="C">C</button><button class="key" data-key="V">V</button><button class="key" data-key="B">B</button><button class="key" data-key="N">N</button><button class="key" data-key="M">M</button>
                        <button class="key large" data-key="BACKSPACE">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2M3 12l6.414 6.414a2 2 0 001.414.586H19a2 2 0 002-2V7a2 2 0 00-2-2h-8.172a2 2 0 00-1.414.586L3 12z" /></svg>
                        </button>
                    </div>
                </div>
            </div>

            <div id="new-game-container" class="hidden w-full flex justify-center mt-4 pb-4 md:pb-0">
                <button onclick="newGame()" class="bg-slate-900 text-white font-bold py-3 px-10 rounded-full hover:scale-105 transition-all shadow-xl animate-bounce flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" /></svg>
                    Jugar otra vez
                </button>
            </div>

        </main>
        
        <x-footer />
    </div>

    <div id="instructionsModal" class="fixed inset-0 bg-slate-900/50 hidden items-start md:items-center justify-center z-50 backdrop-blur-sm p-4 overflow-y-auto" onclick="hideInstructions()">
        <div class="bg-white rounded-2xl p-6 max-w-sm w-full shadow-2xl mt-8 md:mt-0 relative animate-popIn" onclick="event.stopPropagation()">
            <button onclick="hideInstructions()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
            <h2 class="text-2xl font-black mb-2 uppercase text-blue-900 text-center">Reglas Porteñas</h2>
            <p class="mb-6 text-center text-gray-600">Adivina la palabra oculta en 6 intentos.</p>
            
            <div class="space-y-4 mb-8">
                <div class="flex items-center gap-4 p-2 rounded-lg bg-green-50 border border-green-100">
                    <div class="w-10 h-10 bg-green-500 text-white font-bold flex items-center justify-center rounded text-xl shadow-sm">C</div>
                    <p class="text-sm text-green-800 font-medium leading-tight">Letra correcta en la posición correcta.</p>
                </div>
                <div class="flex items-center gap-4 p-2 rounded-lg bg-yellow-50 border border-yellow-100">
                    <div class="w-10 h-10 bg-yellow-500 text-white font-bold flex items-center justify-center rounded text-xl shadow-sm">E</div>
                    <p class="text-sm text-yellow-800 font-medium leading-tight">Letra correcta pero en otra posición.</p>
                </div>
                <div class="flex items-center gap-4 p-2 rounded-lg bg-gray-100 border border-gray-200">
                    <div class="w-10 h-10 bg-gray-500 text-white font-bold flex items-center justify-center rounded text-xl shadow-sm">R</div>
                    <p class="text-sm text-gray-600 font-medium leading-tight">La letra no está en la palabra.</p>
                </div>
            </div>
            
            <button onclick="hideInstructions()" class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition shadow-md text-lg">
                ¡VAMOS A JUGAR!
            </button>
        </div>
    </div>

    <script>
const PALABRAS_VALPARAISO = [
    'ALEGRE', 'CONCEPCION', 'ARTILLERIA', 'PLAYANCHA', 'BARON', 'POLANCO', 'CORDILLERA', 'PANTEON', 'CARCEL', 'MARIPOSAS',
    'MONJAS', 'LECHEROS', 'LARRAIN', 'RECREO', 'ESPERANZA', 'PLACERES', 'OHIGGINS', 'ROCUANT', 'YUNGAY', 'BELLAVISTA',
    'POLANCO', 'PERAL', 'REINA', 'VICTORIA', 'FLORIDA', 'MARIPOSA', 'VILLASECA', 'MONJAS', 'ESPIRITU', 'TROLE',
    'TROLEBUS', 'MICRO', 'COLECTIVO', 'METRO', 'MERVAL', 'ESTACION', 'RIEL', 'CABINA', 'FUNICULAR', 'PLANO',
    'PUERTO', 'MUELLE', 'DIQUE', 'GRUA', 'ESTIBADOR', 'CHORERO', 'LANCHA', 'BOTE', 'REMOLCADOR', 'TRANSATLANTICO',
    'CONTAINER', 'ADUANA', 'FARO', 'ROMPEOLAS', 'BAHIA', 'COSTANERA', 'PESCADOR', 'JAIVA', 'MERLUZA', 'GAVIOTA',
    'WANDERERS', 'CATURRO', 'LUKAS', 'NERUDA', 'SEBASTIANA', 'GITANO', 'PAYASO', 'MIMOS', 'CARNAVAL', 'TAMBORES',
    'MILCHAS', 'CHORRILLANA', 'CUECA', 'BOHEMIA', 'CINZANO', 'PAJARITO', 'JOYA', 'PATRIMONIO', 'UNESCO', 'GRAFFITI',
    'PRAT', 'SOTOMAYOR', 'ANCHA', 'SERRANO', 'ESMERALDA', 'CONDELL', 'PEDRO', 'MONTT', 'VICTORIA', 'FRANCIA',
    'BRASIL', 'ITALIA', 'ECUADOR', 'CUMMING', 'DINAMARCA', 'URRIOLA', 'ALMIRANTE', 'SUBIDA', 'ESCALERA', 'MIRADOR'
];

        const ROWS = 6;
        let currentWordCols = 5;
        let selectedLength = null; // null = todas las longitudes

        let targetWord = '';
        let displayWord = '';
        let currentRow = 0;
        let currentCol = 0;
        let gameOver = false;
        let boardState = [];
        let keyboardState = {};

        function init() {
            attachEventListeners();
            newGame();
        }

        function normalizeWord(word) {
            return word.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toUpperCase();
        }

        function selectWordLength(length) {
            selectedLength = length;

            // Actualizar UI de los botones
            document.querySelectorAll('.length-selector').forEach(btn => {
                btn.classList.remove('active');
            });
            const activeBtn = document.querySelector(`.length-selector[data-length="${length === null ? 'all' : length}"]`);
            if (activeBtn) activeBtn.classList.add('active');

            // Iniciar nuevo juego con la longitud seleccionada
            newGame();
        }

        function getFilteredWords() {
            if (selectedLength === null) {
                return PALABRAS_VALPARAISO;
            }

            return PALABRAS_VALPARAISO.filter(word => {
                const len = word.length;
                if (selectedLength === 10) {
                    return len >= 10; // 10+ incluye 10, 11, 12, 13, etc.
                }
                return len === selectedLength;
            });
        }

        function newGame() {
            // Obtener palabras filtradas según longitud seleccionada
            const availableWords = getFilteredWords();

            if (availableWords.length === 0) {
                showMessage('No hay palabras de ese largo', 'red');
                return;
            }

            // Selección de palabra (evitar repetir la misma inmediatamente si es posible)
            let newWord;
            do {
                newWord = availableWords[Math.floor(Math.random() * availableWords.length)];
            } while (newWord.toUpperCase() === displayWord && availableWords.length > 1);

            displayWord = newWord.toUpperCase();
            targetWord = normalizeWord(newWord);
            currentWordCols = targetWord.length;

            currentRow = 0;
            currentCol = 0;
            gameOver = false;
            boardState = Array(ROWS).fill().map(() => Array(currentWordCols).fill(''));
            keyboardState = {};

            document.getElementById('new-game-container').classList.add('hidden');
            document.getElementById('message-container').innerHTML = '';

            // Actualizar badge
            if (selectedLength === null) {
                document.getElementById('word-length-badge').textContent = `${currentWordCols} Letras`;
            } else if (selectedLength === 10) {
                document.getElementById('word-length-badge').textContent = `${currentWordCols} Letras`;
            } else {
                document.getElementById('word-length-badge').textContent = `${currentWordCols} Letras`;
            }
            
            // Resetear teclado visual
            document.querySelectorAll('.key').forEach(key => {
                key.classList.remove('correct', 'present', 'absent');
            });

            createBoardDOM();
            console.log('Dev Hint:', targetWord); 
        }

        function createBoardDOM() {
            const boardContainer = document.getElementById('board-container');
            boardContainer.innerHTML = '';

            // Ajustar tamaño de fuente según longitud
            let fontSizeClass = 'text-2xl';
            if (currentWordCols > 6) fontSizeClass = 'text-xl';
            if (currentWordCols > 8) fontSizeClass = 'text-lg';
            if (currentWordCols >= 10) fontSizeClass = 'text-base';

            for (let i = 0; i < ROWS; i++) {
                const row = document.createElement('div');
                row.className = 'board-row';
                row.id = `row-${i}`;
                
                // FIX: Usar minmax para limitar el ancho máximo de las celdas
                // 54px es un buen estándar. Si la palabra es larga, se achicarán automáticamente.
                row.style.gridTemplateColumns = `repeat(${currentWordCols}, minmax(auto, 54px))`;

                for (let j = 0; j < currentWordCols; j++) {
                    const tile = document.createElement('div');
                    tile.className = `tile ${fontSizeClass}`;
                    tile.id = `tile-${i}-${j}`;
                    row.appendChild(tile);
                }
                boardContainer.appendChild(row);
            }
        }

        function attachEventListeners() {
            document.getElementById('keyboard').addEventListener('click', (e) => {
                const target = e.target.closest('.key');
                if (!target) return;
                // Pequeño feedback háptico visual al tocar en móvil
                target.style.transform = 'scale(0.95)';
                setTimeout(() => target.style.transform = '', 100);
                
                handleInput(target.dataset.key);
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') handleInput('ENTER');
                else if (e.key === 'Backspace') handleInput('BACKSPACE');
                else if (e.key.length === 1 && e.key.match(/[a-zñ]/i)) handleInput(e.key.toUpperCase());
            });
        }

        function handleInput(key) {
            if (gameOver) return;
            if (key === 'ENTER') submitGuess();
            else if (key === 'BACKSPACE') deleteLetter();
            else addLetter(key);
        }

        function addLetter(letter) {
            if (currentCol < currentWordCols) {
                boardState[currentRow][currentCol] = letter;
                const tile = document.getElementById(`tile-${currentRow}-${currentCol}`);
                tile.textContent = letter;
                tile.classList.add('filled');
                // Animación de "pop" al escribir
                tile.animate([
                    { transform: 'scale(1)' },
                    { transform: 'scale(1.15)' },
                    { transform: 'scale(1)' }
                ], { duration: 150, easing: 'ease-out' });
                currentCol++;
            }
        }

        function deleteLetter() {
            if (currentCol > 0) {
                currentCol--;
                boardState[currentRow][currentCol] = '';
                const tile = document.getElementById(`tile-${currentRow}-${currentCol}`);
                tile.textContent = '';
                tile.classList.remove('filled');
            }
        }

        function submitGuess() {
            if (currentCol !== currentWordCols) {
                showMessage('Palabra incompleta', 'red');
                shakeRow(currentRow);
                return;
            }

            const guessArr = boardState[currentRow];
            const guessString = guessArr.join('');
            
            // Deshabilitar input mientras anima
            gameOver = true; 
            checkGuess(guessArr, guessString);
        }

        function checkGuess(guessArr, guessString) {
            const targetArray = targetWord.split('');
            const result = Array(currentWordCols).fill('absent');
            const letterCounts = {};
            for (const char of targetArray) letterCounts[char] = (letterCounts[char] || 0) + 1;

            // Paso 1: Correctas
            for (let i = 0; i < currentWordCols; i++) {
                if (guessArr[i] === targetArray[i]) {
                    result[i] = 'correct';
                    letterCounts[guessArr[i]]--;
                }
            }
            // Paso 2: Presentes
            for (let i = 0; i < currentWordCols; i++) {
                if (result[i] !== 'correct' && letterCounts[guessArr[i]] > 0) {
                    result[i] = 'present';
                    letterCounts[guessArr[i]]--;
                }
            }
            animateResults(result, guessArr, guessString);
        }

        function animateResults(result, guessArr, guessString) {
            for (let i = 0; i < currentWordCols; i++) {
                const tile = document.getElementById(`tile-${currentRow}-${i}`);
                setTimeout(() => {
                    tile.classList.add(result[i]);
                    updateKeyboardKey(guessArr[i], result[i]);
                    if (i === currentWordCols - 1) {
                        setTimeout(() => checkWinCondition(guessString), 300);
                    }
                }, i * 250);
            }
        }

        function checkWinCondition(guessString) {
            if (guessString === targetWord) {
                showMessage('¡EXCELENTE! 🎉', 'green');
                celebrateRow(currentRow);
                endGame();
            } else if (currentRow === ROWS - 1) {
                showMessage(`La palabra era: ${displayWord}`, 'black', true);
                endGame();
            } else {
                currentRow++;
                currentCol = 0;
                gameOver = false; // Habilitar input de nuevo
            }
        }

        function endGame() {
            gameOver = true;
            document.getElementById('new-game-container').classList.remove('hidden');
        }

        function updateKeyboardKey(letter, state) {
            const keyBtn = document.querySelector(`.key[data-key="${letter}"]`);
            if (!keyBtn) return;
            const oldState = keyboardState[letter];
            if (oldState === 'correct') return;
            if (oldState === 'present' && state === 'absent') return;

            keyboardState[letter] = state;
            keyBtn.classList.remove('correct', 'present', 'absent');
            keyBtn.classList.add(state);
        }

        function showMessage(text, type, permanent = false) {
            const container = document.getElementById('message-container');
            const msg = document.createElement('div');
            let classes = 'px-6 py-3 rounded-full font-bold text-sm shadow-xl animate-popIn pointer-events-auto flex items-center gap-2 backdrop-blur-sm';
            if (type === 'green') classes += ' bg-green-500/95 text-white';
            else if (type === 'red') classes += ' bg-white text-red-600 border-2 border-red-100';
            else classes += ' bg-slate-800/95 text-white';

            msg.className = classes;
            msg.innerHTML = text;
            container.innerHTML = '';
            container.appendChild(msg);

            // Solo auto-ocultar mensajes rojos y si no es permanente
            if (type === 'red' && !permanent) {
                setTimeout(() => {
                    if (container.contains(msg)) {
                        msg.classList.remove('animate-popIn');
                        msg.classList.add('animate-popOut');
                        setTimeout(() => container.removeChild(msg), 300);
                    }
                }, 2000);
            }
        }

        function shakeRow(rowIdx) {
            const row = document.getElementById(`row-${rowIdx}`);
            row.classList.add('shake');
            setTimeout(() => row.classList.remove('shake'), 400);
        }

        function celebrateRow(rowIdx) {
            for (let i = 0; i < currentWordCols; i++) {
                const tile = document.getElementById(`tile-${rowIdx}-${i}`);
                setTimeout(() => tile.classList.add('bounce'), i * 100);
            }
        }

        function showInstructions() {
            const modal = document.getElementById('instructionsModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            // Resetear scroll del modal
            modal.querySelector('div').scrollTop = 0;
        }

        function hideInstructions() {
            const modal = document.getElementById('instructionsModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        init();
    </script>
    <style>
        /* Animaciones Adicionales */
        @keyframes popIn {
            0% { opacity: 0; transform: scale(0.8) translateY(-20px); }
            100% { opacity: 1; transform: scale(1) translateY(0); }
        }
        @keyframes popOut {
            0% { opacity: 1; transform: scale(1); }
            100% { opacity: 0; transform: scale(0.8); }
        }
        .animate-popIn { animation: popIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
        .animate-popOut { animation: popOut 0.3s ease-in forwards; }
    </style>
</body>
</html>