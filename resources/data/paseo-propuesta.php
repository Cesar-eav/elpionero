<?php

// Puntos centrales de /cuidemoselpaseo/propuesta: por qué la institución del Parque Barón debe hacerse cargo del Paseo.
// - 'citas': 'doc' es un id de resources/data/paseo-documentos.php y 'pagina', la página del PDF.
// - 'principio': id de un principio de resources/data/paseo-manifiesto.php (enlaza a #p-{id} del manifiesto).
// - Los textos admiten [[id-del-glosario]], [[id|texto visible]] y **negrita**.

return [
    [
        'id' => 'promesa',
        'titulo' => 'El convenio ya promete un paseo costero',
        'idea' => 'Desde 2019, uno de los objetivos del Parque Barón es **consolidar un paseo costero**.',
        'citas' => [
            ['doc' => 'convenio', 'pagina' => 4, 'texto' => '…consolidar un paseo costero en ese sector y crear equipamientos y servicios para los porteños y los turistas que visitan la ciudad.'],
        ],
        'por_que' => 'El Paseo Wheelwright es el paseo costero que sigue al del parque hacia Caleta Portales. Sumarlo no cambia el propósito del [[convenio-marco|convenio]]: lo cumple a la escala que el borde necesita.',
        'principio' => 'responsable',
    ],
    [
        'id' => 'se-tocan',
        'titulo' => 'Parque y Paseo ya están conectados',
        'idea' => 'La primera etapa del parque incluye la conexión con el Paseo, y el parque ya cuida su propio paseo costero hasta el Muelle Barón.',
        'citas' => [
            ['doc' => 'acta-16', 'pagina' => 2, 'texto' => '…la que contiene el sector rotonda, conexión con Paseo Wheelrigth [sic] y acceso a muelle.'],
            ['doc' => 'acta-entrega', 'pagina' => 2, 'texto' => 'Paseo costero que va desde la Rotonda hasta el Muelle Barón.'],
        ],
        'por_que' => 'Quien llega al parque desde Viña del Mar lo hace por el Paseo. Si el Paseo está cerrado o deteriorado, el parque queda con una de sus puertas cerrada. Hoy un mismo frente al mar tiene dos administraciones y dos estándares distintos.',
        'principio' => 'responsable',
    ],
    [
        'id' => 'mecanismo',
        'titulo' => 'La herramienta para sumarlo ya existe',
        'idea' => 'El [[comite-gestion|Comité de Gestión]] puede proponer cambios al convenio, ya pensó en sumar el Muelle y está preparando un convenio nuevo.',
        'citas' => [
            ['doc' => 'convenio', 'pagina' => 8, 'texto' => 'Proponer cualquier modificación al presente Convenio, que permita una mejor y más eficaz colaboración entre las Partes.'],
            ['doc' => 'acta-13', 'pagina' => 1, 'texto' => 'Se esboza la idea de incorporar el muelle al polígono de intervención. Se buscará la figura que convenga a todas las partes.'],
            ['doc' => 'acta-16', 'pagina' => 4, 'texto' => 'Se está trabajando la propuesta de un nuevo Convenio, ya que el avance de las inversiones en Paño Barón ha sido dinámico y exige hacerse cargo de bastantes más materias…'],
        ],
        'por_que' => 'No hace falta una ley nueva. El Comité puede proponer el cambio y las partes firmarlo como [[addendum|addendum]], o incluirlo en el convenio nuevo que ya se prepara. Lo mismo se conversó para el Muelle Barón.',
        'principio' => 'responsable',
    ],
    [
        'id' => 'recursos',
        'titulo' => 'El parque tendrá contrato y recursos de mantención',
        'idea' => 'Se licita la conservación y la seguridad del parque para 2026–2028, y los ingresos del parque deben pagar primero su mantención.',
        'citas' => [
            ['doc' => 'acta-2026', 'pagina' => 3, 'texto' => 'Se deja constancia del compromiso de los actores por ingresar bases de licitación de Conservación y Seguridad a la CGR y gestionar su licitación pública este año. De esto depende la apertura del parque.'],
            ['doc' => 'convenio', 'pagina' => 7, 'texto' => 'Para determinar las utilidades que se obtengan, deberán en forma previa estar cubiertos los costos del contrato de administración y mantención suscrito por parte del Serviu.'],
        ],
        'por_que' => 'Un contrato de [[ficha-conservacion|conservación]] y seguridad es justo lo que le falta al Paseo: alguien que limpie, cambie luminarias y repare a tiempo, todos los días. Sumar 1,7 km a un contrato que ya existe es más simple que crear uno nuevo.',
        'principio' => 'mantencion',
    ],
    [
        'id' => 'municipio',
        'titulo' => 'El Municipio ya dijo hasta dónde llega',
        'idea' => 'En la Rotonda del parque, el Municipio aceptaría solo aseo y seguridad, **sin inversión ni presupuesto propio**.',
        'citas' => [
            ['doc' => 'acta-2026', 'pagina' => 3, 'texto' => 'Se deja establecido que la administración de la rotonda por parte del Municipio no implicaría inversión y presupuesto de su parte. Solo sería Gestión (incluye Aseo y Seguridad Ciudadana).'],
        ],
        'por_que' => 'Es comprensible: el Municipio tiene toda una ciudad que cuidar con recursos limitados. Pero una obra frente al mar, golpeada por marejadas, necesita más que aseo. Hoy el Paseo depende del Municipio y la mantención no ha alcanzado: en 2025 se intervinieron 580 de los 1.300 metros en mal estado.',
        'principio' => 'mantencion',
    ],
    [
        'id' => 'a-pie',
        'titulo' => 'Un parque metropolitano necesita su llegada a pie',
        'idea' => 'El proyecto quiere recuperar el acceso al mar «del ciudadano a pie» y ensancha sus accesos a 7 metros.',
        'citas' => [
            ['doc' => 'talleres-bsb', 'pagina' => 10, 'texto' => 'Recuperar el acceso ciudadano al mar, del ciudadano a pie: justicia territorial.'],
            ['doc' => 'acta-16', 'pagina' => 3, 'texto' => '…en consonancia con la escala metropolitana del Parque, logrando pasar de un tamaño de 2.5 a 7 metros de ancho.'],
        ],
        'por_que' => 'El Paseo es el único camino por el borde, lejos de los autos, entre Viña del Mar y Valparaíso. Es la llegada natural de quienes caminan, trotan o pedalean al parque. Un parque pensado para miles de visitantes no puede tener su acceso costero cerrado.',
        'principio' => 'abierto',
    ],
    [
        'id' => 'mismo-mar',
        'titulo' => 'Mismo mar, mismas marejadas, una sola respuesta',
        'idea' => 'Las marejadas de julio de 2026 golpearon a la vez el Paseo, la avenida Altamirano y el borde costero de la región.',
        'citas' => [
            ['doc' => 'oficio-236', 'pagina' => 129, 'texto' => '…en Valparaíso, socavamiento en avenida Altamirano y daños en rellenos, parrillas de acero, pavimento y sectores del paseo Wheelwright.'],
        ],
        'por_que' => 'El muro de defensa del parque y el Paseo enfrentan el mismo oleaje. Un solo administrador para todo el frente puede usar las [[alerta-temprana|alertas]] que ya existen (el protocolo de [[senapred|SENAPRED]] y [[directemar|DIRECTEMAR]]) para avisar, cerrar a tiempo, retirar lo móvil y reabrir apenas pase el golpe.',
        'principio' => 'alerta',
    ],
    [
        'id' => 'saber-tecnico',
        'titulo' => 'La experiencia en obras frente al mar está en el Estado',
        'idea' => 'El Paseo lo construyó la [[dop|Dirección de Obras Portuarias]], que también evaluó su daño en 2026; el parque lo construye el [[serviu|SERVIU]] en terreno de la [[epv|Empresa Portuaria (EPV)]].',
        'citas' => [
            ['doc' => 'oficio-236', 'pagina' => 186, 'texto' => 'Marejadas generaron socavones y pérdida de sustentación del paseo peatonal, comprometiendo la seguridad de usuarios.'],
        ],
        'por_que' => 'Los equipos que diseñan obras costeras, licitan conservaciones y administran concesiones están en el sistema del parque y en el MOP. Un municipio difícilmente tiene esa capacidad técnica para una obra expuesta al mar. Juntarlos en un mismo sistema permite diseñar con criterios de [[infraestructura-danable|infraestructura dañable]] y reparar rápido.',
        'principio' => 'mar',
    ],
];
