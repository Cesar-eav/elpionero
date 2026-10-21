<?php

/*
 * Galería "El paseo, foto a foto" (/cuidemoselpaseo/galeria).
 *
 * Cada etapa sigue el cronograma. La fecha de cada foto y el sello de cada etapa NO se escriben aquí:
 * se lee del nombre del archivo (IMG_AAAAMMDD_HHMMSS...). Las versiones web se generan en
 * public/cuidemos_el_paseo/galeria/web/<nombre>-800.webp y -1800.webp.
 *
 * f    ruta relativa a public/cuidemos_el_paseo/galeria
 * t    título corto de la cartela
 * d    descripción larga (solo se ve en el visor)
 * dest foto destacada de la sala (una por sala)
 *
 * Las notas describen solo lo que se ve en la foto, sin atribuir causas ni responsables.
 */

$antes = 'Antes de la intervencion de noviembre 2025/';

return [
    [
        'id' => 'senales',
        'num' => '01', // clave interna (colores de la regla de tiempo); no se muestra
        'corto' => 'Señales',
        'titulo' => 'Señales tempranas',
        'periodo' => 'Marzo 2023 – marzo 2024',
        'texto' => 'Dos años antes de la intervención, el deterioro ya era visible: tablones que faltan en la plataforma de la playa, baldosas sueltas, máquinas de ejercicio desarmadas y arena que cubre la ciclovía.',
        'fotos' => [
            ['f' => $antes . 'IMG_20230310_164935.jpg', 't' => 'Plataforma de madera junto a la playa', 'd' => 'Plataforma sobre la arena, al pie de los arcos del paseo. Falta un tramo de tablones al centro.'],
            ['f' => $antes . 'IMG_20230310_173009.jpg', 't' => 'Tablones faltantes y rayados', 'd' => 'El borde de la plataforma, rayado, y el hueco donde faltan tablones.'],
            ['f' => $antes . 'IMG_20230310_173139.jpg', 't' => 'Madera gastada, pernos oxidados', 'd' => 'Tablones secos y gastados por el sol y la sal, con los pernos oxidados.'],
            ['f' => $antes . 'IMG_20240311_104126.jpg', 't' => 'Ciclovía junto al cerco vivo', 'd' => 'Tramo de ciclovía con la línea amarilla todavía visible.'],
            ['f' => $antes . 'IMG_20240311_104334.jpg', 't' => 'Baldosas sueltas', 'd' => 'Baldosas de guía para personas ciegas desprendidas del piso.'],
            ['f' => $antes . 'IMG_20240311_104559.jpg', 't' => 'Jardinera de tierra seca', 'd' => 'Una jardinera sin plantas junto a la ciclovía, con el puerto al fondo.'],
            ['f' => $antes . 'IMG_20240311_104626.jpg', 't' => 'Máquina de ejercicio en el suelo', 'd' => 'Un balancín de la zona de ejercicios, desarmado y tirado sobre el pavimento.'],
            ['f' => $antes . 'IMG_20240311_113910.jpg', 't' => 'Arena sobre el paseo', 'd' => 'El paseo cubierto por una capa de arena a lo largo de la baranda.'],
            ['f' => $antes . 'IMG_20240311_113917.jpg', 't' => 'Arena junto al cerco del tren', 'd' => 'Arena y maleza acumuladas contra el cerco que separa el paseo de la vía férrea.'],
            ['f' => $antes . 'IMG_20240311_113953_4.jpg', 't' => 'Pavimento quebrado bajo la arena', 'd' => 'Bajo la arena asoman las grietas del pavimento.'],
            ['f' => $antes . 'IMG_20240312_172124.jpg', 't' => 'Una grieta de lado a lado', 'd' => 'Una grieta con arena cruza todo el ancho del paseo, mientras la gente sigue caminando.', 'dest' => true],
            ['f' => $antes . 'IMG_20240312_172339.jpg', 't' => 'Pavimento roto junto a la rejilla', 'd' => 'Losa quebrada al costado de una rejilla metálica.'],
        ],
    ],
    [
        'id' => 'registro',
        'num' => '02',
        'corto' => 'Registro',
        'titulo' => 'Recorrer y registrar',
        'periodo' => 'Octubre – noviembre 2025',
        'texto' => 'Tres domingos de octubre, recorriendo el paseo foto por foto. Es el registro más completo de su estado antes de la intervención: decks sin tablones, registros sin tapa, rejillas dobladas, losas hundidas. Al final, dos fotos de noche, cuando el paseo queda casi a oscuras.',
        'por_dia' => true,
        'fotos' => [
            ['f' => $antes . 'IMG_20251005_132526090_HDR.jpg', 't' => 'Jardín con bancas de hormigón', 'd' => 'Una de las áreas verdes del paseo, con pasto y bancas bajas.'],
            ['f' => $antes . 'IMG_20251005_132545112_HDR.jpg', 't' => 'Bancas curvas con rayados', 'd' => 'Bancas de hormigón rayadas, con los edificios del cerro al fondo.'],
            ['f' => $antes . 'IMG_20251005_132701813_HDR.jpg', 't' => 'Tapa de registro hundida', 'd' => 'Una tapa metálica hundida bajo el nivel del piso, rodeada de baldosas quebradas.'],
            ['f' => $antes . 'IMG_20251005_132731041_HDR.jpg', 't' => 'Registro sin tapa', 'd' => 'Un registro abierto, lleno de tierra, y baldosas faltantes alrededor.'],
            ['f' => $antes . 'IMG_20251005_132743051_HDR.jpg', 't' => 'Banca quebrada', 'd' => 'Un trozo de banca de hormigón partido y caído sobre el pavimento.'],
            ['f' => $antes . 'IMG_20251005_132813357_HDR.jpg', 't' => 'Pérgola y charco en la ciclovía', 'd' => 'Gente bajo la pérgola y un charco que ocupa la ciclovía.'],
            ['f' => $antes . 'IMG_20251005_133056907_HDR.jpg', 't' => 'Losa hundida junto al muro', 'd' => 'Una losa hundida deja un hoyo abierto al lado del muro de grafitis.'],
            ['f' => $antes . 'IMG_20251005_133828666_HDR.jpg', 't' => 'Mirador frente a la bodega', 'd' => 'El mirador circular junto a la antigua bodega pintada.'],
            ['f' => $antes . 'IMG_20251005_133910005_HDR.jpg', 't' => 'Muro con la malla a la vista', 'd' => 'El revestimiento del muro se desprendió y dejó la malla de acero expuesta. Los trozos quedaron en el piso.'],
            ['f' => $antes . 'IMG_20251005_133915883_HDR.jpg', 't' => 'Junta abierta entre piso y muro', 'd' => 'Un charco y una separación entre el pavimento y la base del muro.'],
            ['f' => $antes . 'IMG_20251005_133929676_HDR.jpg', 't' => 'Hueco de un panel desaparecido', 'd' => 'Donde había un panel de piso queda un rectángulo de tierra y el marco oxidado.'],
            ['f' => $antes . 'IMG_20251005_134303378_HDR.jpg', 't' => 'Deck sin tablones', 'd' => 'Faltan tablones a lo largo del deck y se ve la estructura de abajo.'],
            ['f' => $antes . 'IMG_20251005_134328460_HDR.jpg', 't' => 'Estructura expuesta junto a la banca', 'd' => 'El costado del deck abierto, junto a la banca de hormigón.'],
            ['f' => $antes . 'IMG_20251005_134449258_HDR.jpg', 't' => 'Paneles del deck levantados', 'd' => 'Paneles de madera y rejilla desplazados, con la viga de hormigón al descubierto.'],
            ['f' => $antes . 'IMG_20251005_134502873_HDR.jpg', 't' => 'Deck desnivelado frente al mar', 'd' => 'Una vista más amplia del mismo tramo: el deck roto y el paseo en uso.'],

            ['f' => $antes . 'IMG_20251012_162658276_HDR.jpg', 't' => 'Enrocado y paseantes', 'd' => 'El enrocado que protege el borde y la gente caminando junto a la baranda.'],
            ['f' => $antes . 'IMG_20251012_163100676_HDR.jpg', 't' => 'Un hueco, medido', 'd' => 'Un registro abierto junto al muro. La huincha amarilla da la escala.'],
            ['f' => $antes . 'IMG_20251012_163131921_HDR.jpg', 't' => 'Registro sin tapa en medio del tránsito', 'd' => 'Un registro abierto, con fierros a la vista, por donde pasa la gente.'],
            ['f' => $antes . 'IMG_20251012_163447793_HDR.jpg', 't' => 'Pasarela junto al muro pintado', 'd' => 'La pasarela de madera entre la baranda y el muro de grafitis, en uso.'],
            ['f' => $antes . 'IMG_20251012_163616537_HDR.jpg', 't' => 'Baranda y enrocado', 'd' => 'La baranda celeste sigue el borde del enrocado.'],
            ['f' => $antes . 'IMG_20251012_163911068_HDR.jpg', 't' => 'La playa desde el enrocado', 'd' => 'La playa y el enrocado en un día despejado.'],
            ['f' => $antes . 'IMG_20251012_164003099_HDR.jpg', 't' => 'Máquina de ejercicio oxidada', 'd' => 'Una máquina de ejercicio con el poste oxidado y los pedales sueltos.'],
            ['f' => $antes . 'IMG_20251012_164219011_HDR.jpg', 't' => 'Bañistas junto al deck de la playa', 'd' => 'Gente en la arena al pie del deck y la baranda.'],
            ['f' => $antes . 'IMG_20251012_164248357_HDR.jpg', 't' => 'Banca quebrada sobre la rejilla', 'd' => 'Una banca de hormigón partida, apoyada sobre la rejilla del deck.'],
            ['f' => $antes . 'IMG_20251012_164342086_HDR.jpg', 't' => 'Tránsito junto al deck roto', 'd' => 'La gente pasa por el costado de un tramo de deck desarmado.'],
            ['f' => $antes . 'IMG_20251012_164355720_HDR.jpg', 't' => 'Losas levantadas sobre el deck', 'd' => 'Bloques de hormigón fuera de su lugar, encima de la madera.'],
            ['f' => $antes . 'IMG_20251012_164445656_HDR.jpg', 't' => 'Tablones arrancados bajo la pérgola', 'd' => 'Bajo la pérgola, los tablones del deck están levantados y amontonados.', 'dest' => true],
            ['f' => $antes . 'IMG_20251012_164708439_HDR.jpg', 't' => 'La rejilla doblada', 'd' => 'Una rejilla metálica del deck, doblada en curva y fuera de su lugar.'],
            ['f' => $antes . 'IMG_20251012_164858625_HDR.jpg', 't' => 'Paseantes con los cerros de fondo', 'd' => 'Un domingo de paseo, con los cerros de Valparaíso detrás.'],
            ['f' => $antes . 'IMG_20251012_164949430_HDR.jpg', 't' => 'Parche roto en la ciclovía', 'd' => 'Un parche de adoquines desarmado cruza la ciclovía.'],
            ['f' => $antes . 'IMG_20251012_165030974_HDR.jpg', 't' => 'Hoyo bajo la losa, junto a la pérgola', 'd' => 'La losa junto al muro tiene un hoyo abierto. Un trozo de hormigón quedó al lado.'],

            ['f' => $antes . 'IMG_20251019_121553149_HDR.jpg', 't' => 'El cañón frente a la bodega', 'd' => 'El cañón sobre su base, con la bodega pintada de grafitis al fondo.'],
            ['f' => $antes . 'IMG_20251019_121849627_HDR.jpg', 't' => 'Cámara abierta, tapa rota', 'd' => 'Una cámara con la tapa quebrada y una tubería a la vista.'],
            ['f' => $antes . 'IMG_20251019_121947063_HDR.jpg', 't' => 'Deck y rejillas a lo largo del borde', 'd' => 'Un tramo largo de deck con rejillas, junto al paseo.'],
            ['f' => $antes . 'IMG_20251019_122029400_HDR.jpg', 't' => 'Sumidero sin rejilla', 'd' => 'Un sumidero sin rejilla, con piedras adentro, en plena ciclovía.'],
            ['f' => $antes . 'IMG_20251019_122147319_HDR.jpg', 't' => 'Grieta entre el muro y el piso', 'd' => 'Una abertura entre el muro y el pavimento, junto a la rueda de una bicicleta.'],
            ['f' => $antes . 'IMG_20251019_122152864_HDR.jpg', 't' => 'Arena junto a la baranda', 'd' => 'Arena acumulada sobre el paseo, con los arcos a lo lejos.'],
            ['f' => $antes . 'IMG_20251019_122420343_HDR.jpg', 't' => 'Adoquines levantados', 'd' => 'Adoquines rojos desnivelados, a ras de suelo.'],
            ['f' => $antes . 'IMG_20251019_123322692_HDR.jpg', 't' => 'Canaleta con maleza', 'd' => 'Una canaleta metálica con maleza entre dos tramos de piso.'],
            ['f' => $antes . 'IMG_20251019_123829634_HDR.jpg', 't' => 'Palmeras junto a la ciclovía', 'd' => 'La hilera de palmeras y la ciclovía verde.'],
            ['f' => $antes . 'IMG_20251019_123932049_HDR.jpg', 't' => 'Borde quebrado en la ciclovía', 'd' => 'El canto de la losa de la ciclovía, quebrado, mientras pasan ciclistas.'],

            ['f' => $antes . 'IMG_20251103_201839850_HDR.jpg', 't' => 'Charco y escombros al atardecer', 'd' => 'Un charco con trozos de pavimento, con la última luz del día.'],
            ['f' => $antes . 'IMG_20251103_202121268_HDR.jpg', 't' => 'Un hoyo abierto al anochecer', 'd' => 'Un hoyo en medio del paseo, difícil de ver cuando oscurece.'],
        ],
    ],
    [
        'id' => 'intervencion',
        'num' => '03',
        'corto' => 'Intervención',
        'titulo' => 'La intervención',
        'periodo' => '18 – 23 noviembre 2025',
        'texto' => 'En noviembre el municipio, junto a voluntarios, intervino 580 de los 1.300 metros en mal estado. Las fotos de esa semana muestran la ciclovía pintada de azul, hoyos rellenados con cemento y la base del cañón repintada.',
        'diptico' => [
            'a' => $antes . 'IMG_20251019_121553149_HDR.jpg',
            'b' => 'IMG_20251123_171005291_HDR.jpg',
            'titulo' => 'El mismo cañón, cinco semanas después',
            'texto' => 'El 19 de octubre, la base con un afiche pegado. El 23 de noviembre, la base pintada, la ciclovía azul alrededor y un letrero de campaña al fondo.',
        ],
        'fotos' => [
            ['f' => 'IMG_20251118_191550678_HDR.jpg', 't' => 'La ciclovía, recién pintada', 'd' => 'La ciclovía pintada de azul, con sus líneas amarillas.'],
            ['f' => 'IMG_20251118_192021557_HDR.jpg', 't' => 'Explanada hacia el mar', 'd' => 'La explanada al final de la tarde, con gente caminando.'],
            ['f' => 'IMG_20251118_192044254_HDR.jpg', 't' => 'Pasillo junto al muro', 'd' => 'El pasillo entre el muro y la ciclovía.'],
            ['f' => 'IMG_20251122_092555975_HDR.jpg', 't' => 'Parche de cemento', 'd' => 'Un hoyo rellenado con cemento en medio del paseo.'],
            ['f' => 'IMG_20251122_092613269_HDR.jpg', 't' => 'Parches junto al cerco', 'd' => 'Más parches de cemento cerca del cerco, en una mañana nublada.'],
            ['f' => 'IMG_20251122_093330700_HDR.jpg', 't' => 'El cañón y el letrero', 'd' => 'El cañón con la base pintada y un letrero que dice "Valpo no se ensucia, Valpo se cuida".'],
            // Personas identificables: confirmar que están de acuerdo con publicar la foto
            ['f' => 'IMG_20251122_125809568_HDR.jpg', 't' => 'Voluntarias en la jornada', 'd' => 'Dos voluntarias con pañuelos naranjos durante la jornada, sobre la ciclovía azul.'],
            ['f' => 'IMG_20251123_171005291_HDR.jpg', 't' => 'El cañón, al día siguiente', 'd' => 'El cañón con su base pintada y la ciclovía azul que lo rodea.'],
            ['f' => 'IMG_20251123_171251174_HDR.jpg', 't' => 'Ciclovía azul en uso', 'd' => 'Un ciclista y un corredor por la ciclovía recién pintada.', 'dest' => true],
            ['f' => 'IMG_20251123_172219510_HDR.jpg', 't' => 'La canaleta, igual que antes', 'd' => 'La canaleta entre los tramos de piso sigue con basura y maleza.'],
        ],
    ],
    [
        'id' => 'despues',
        'num' => '04',
        'corto' => 'Después',
        'titulo' => 'Lo que quedó',
        'periodo' => 'Diciembre 2025 – enero 2026',
        'texto' => 'Uno y dos meses después de la intervención, varios tramos se ven como en octubre: decks sin tablones, jardineras sin plantas y, junto al muro de grafitis, un piso que se hundió.',
        'diptico' => [
            'a' => $antes . 'IMG_20251012_164708439_HDR.jpg',
            'b' => 'IMG_20251226_181135673_HDR.jpg',
            'titulo' => 'La misma rejilla, dos meses y medio después',
            'texto' => 'El 12 de octubre la rejilla estaba doblada sobre el deck. El 26 de diciembre seguía ahí, curvada como un arco.',
        ],
        'fotos' => [
            // Persona identificable: confirmar que está de acuerdo con publicar la foto
            ['f' => 'IMG_20251226_175120466_HDR.jpg', 't' => 'Jardinera sin plantas frente al mar', 'd' => 'Una jardinera de tierra y piedras junto al borde.'],
            ['f' => 'IMG_20251226_175132849_HDR.jpg', 't' => 'Tierra y maleza', 'd' => 'De cerca, la misma jardinera: tierra suelta y algunas matas.'],
            ['f' => 'IMG_20251226_180906932_HDR.jpg', 't' => 'El deck de la playa', 'd' => 'El deck que baja a la playa, con la baranda celeste.'],
            ['f' => 'IMG_20251226_181135673_HDR.jpg', 't' => 'La rejilla, todavía ahí', 'd' => 'La rejilla doblada sigue sobre el deck, ahora en forma de arco.'],
            ['f' => 'IMG_20251226_181529130_HDR.jpg', 't' => 'Grieta abierta junto al muro', 'd' => 'Una separación profunda entre el piso y el muro.'],
            ['f' => 'IMG_20260107_161012807_HDR.jpg', 't' => 'Palmeras y franja seca', 'd' => 'La franja de tierra entre las palmeras y la ciclovía.'],
            ['f' => 'IMG_20260120_185719445_HDR.jpg', 't' => 'Banca, arena y ciclovía azul', 'd' => 'Una banca junto a una jardinera de arena y la ciclovía pintada.'],
            ['f' => 'IMG_20260120_185843705_HDR.jpg', 't' => 'Huellas en la jardinera', 'd' => 'Huellas de neumáticos sobre la tierra de la jardinera.'],
            ['f' => 'IMG_20260120_190550414_HDR.jpg', 't' => 'Ciclovía azul junto al cerco', 'd' => 'La ciclovía pintada corre junto al cerco del tren.'],
            ['f' => 'IMG_20260120_190613271_HDR.jpg', 't' => 'Pavimento quebrado', 'd' => 'Losas partidas y un parche de adoquines hundido.'],
            ['f' => 'IMG_20260120_190741296_HDR.jpg', 't' => 'Deck con piezas sueltas', 'd' => 'Un tramo de deck desarmado junto a la banca de hormigón.'],
            ['f' => 'IMG_20260120_190852369_HDR.jpg', 't' => 'Panel metálico levantado', 'd' => 'Un panel metálico de pie, fuera de su lugar, junto a la rejilla.'],
            ['f' => 'IMG_20260120_190951799_HDR.jpg', 't' => 'Deck sin tablones', 'd' => 'Faltan tablones al centro del deck; se ve la estructura.'],
            ['f' => 'IMG_20260120_191036021_HDR.jpg', 't' => 'Hoyo en el deck', 'd' => 'Un hoyo abierto en el deck, frente al mar.'],
            ['f' => 'IMG_20260120_191258741_HDR.jpg', 't' => 'Arena sobre la explanada', 'd' => 'Arena acumulada sobre el piso de la explanada.'],
            ['f' => 'IMG_20260120_191356489_HDR.jpg', 't' => 'Arena junto a la baranda', 'd' => 'Arena contra la baranda y alrededor de una tapa.'],
            ['f' => 'IMG_20260120_191547533_HDR.jpg', 't' => 'El piso hundido junto al muro', 'd' => 'Junto al muro de grafitis, el piso colapsó y dejó a la vista losas partidas y escombros.', 'dest' => true],
        ],
    ],
    [
        'id' => 'cierre',
        'num' => '05',
        'corto' => 'Cierre',
        'titulo' => 'Con el paseo cerrado',
        'periodo' => 'Octubre 2026',
        'texto' => 'Desde fines de julio de 2026 el paseo está cerrado indefinidamente. Las fotos del 18 de octubre muestran personas barriendo arena de la ciclovía, bicicletas apoyadas en la baranda y el deck con tramos desnivelados.',
        'anchas' => true, // fotos panorámicas: cada una ocupa dos columnas del mosaico
        'fotos' => [
            ['f' => 'Photos-1-001/IMG_20261018_114102500_HDR_AE.jpg', 't' => 'Barriendo arena de la ciclovía', 'd' => 'Una persona barre arena y gravilla sobre la ciclovía, junto al deck. Pasan un ciclista y un corredor.'],
            ['f' => 'Photos-1-001/IMG_20261018_114123797_HDR_AE.jpg', 't' => 'Arena junto al cerco', 'd' => 'Una persona con casco barre la arena acumulada junto al cerco. Dos bicicletas están apoyadas en la baranda del deck, con la bahía al fondo.', 'dest' => true],
            // Aparece un niño (de espaldas): confirmar que están de acuerdo con publicar la foto
            ['f' => 'Photos-1-001/IMG_20261018_115315908_HDR_AE.jpg', 't' => 'Ciclovía con gravilla y restos de pavimento', 'd' => 'Un adulto y un niño en patines caminan por un tramo de ciclovía con gravilla y restos de pavimento. A la derecha, el deck con tablones desnivelados.'],
        ],
    ],
];
