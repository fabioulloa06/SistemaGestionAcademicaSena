-- ============================================
-- SCRIPT SQL PARA INSERTAR USUARIOS Y DATOS SENA
-- Sistema de Gestión Académica SENA
-- ============================================

-- ============================================
-- 1. INSERTAR USUARIOS (Admin y Coordinador)
-- ============================================

-- Usuario Administrador
INSERT INTO `users` (`name`, `email`, `password`, `role`, `email_verified_at`, `created_at`, `updated_at`)
VALUES (
    'Administrador Sistema',
    'admin@sena.edu.co',
    '$2y$12$RGW8kE7lBDx7i6wRtxF72.rSpeWJuWeyf5V7BqleNpzIkqtDnVt42', -- password: password123
    'admin',
    NOW(),
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE 
    `name` = VALUES(`name`),
    `password` = VALUES(`password`),
    `role` = VALUES(`role`),
    `updated_at` = NOW();

-- Usuario Coordinador
INSERT INTO `users` (`name`, `email`, `password`, `role`, `email_verified_at`, `created_at`, `updated_at`)
VALUES (
    'María González',
    'coordinador@sena.edu.co',
    '$2y$12$RGW8kE7lBDx7i6wRtxF72.rSpeWJuWeyf5V7BqleNpzIkqtDnVt42', -- password: password123
    'coordinator',
    NOW(),
    NOW(),
    NOW()
)
ON DUPLICATE KEY UPDATE 
    `name` = VALUES(`name`),
    `password` = VALUES(`password`),
    `role` = VALUES(`role`),
    `updated_at` = NOW();

-- ============================================
-- 2. INSERTAR PROGRAMAS DE FORMACIÓN SENA
-- ============================================

-- Programa 1: Técnico en Programación de Software
INSERT INTO `programs` (`codigo`, `nombre`, `descripcion`, `duracion_meses`, `nivel`, `activo`, `created_at`, `updated_at`)
VALUES (
    '228106',
    'Técnico en Programación de Software',
    'Formar técnicos en el desarrollo de software aplicando metodologías ágiles, lenguajes de programación y herramientas de desarrollo.',
    18,
    'Técnico',
    1,
    NOW(),
    NOW()
);

-- Programa 2: Tecnólogo en Análisis y Desarrollo de Software
INSERT INTO `programs` (`codigo`, `nombre`, `descripcion`, `duracion_meses`, `nivel`, `activo`, `created_at`, `updated_at`)
VALUES (
    '228118',
    'Tecnólogo en Análisis y Desarrollo de Software',
    'Formar tecnólogos capaces de analizar, diseñar, desarrollar, implementar y mantener sistemas de información.',
    24,
    'Tecnólogo',
    1,
    NOW(),
    NOW()
);

-- Programa 3: Técnico en Sistemas
INSERT INTO `programs` (`codigo`, `nombre`, `descripcion`, `duracion_meses`, `nivel`, `activo`, `created_at`, `updated_at`)
VALUES (
    '228106',
    'Técnico en Sistemas',
    'Formar técnicos en el mantenimiento, instalación y configuración de sistemas informáticos y redes de computadores.',
    18,
    'Técnico',
    1,
    NOW(),
    NOW()
);

-- Programa 4: Técnico en Redes de Computadores
INSERT INTO `programs` (`codigo`, `nombre`, `descripcion`, `duracion_meses`, `nivel`, `activo`, `created_at`, `updated_at`)
VALUES (
    '228108',
    'Técnico en Redes de Computadores',
    'Formar técnicos en el diseño, implementación y administración de redes de computadores.',
    18,
    'Técnico',
    1,
    NOW(),
    NOW()
);

-- ============================================
-- 3. INSERTAR COMPETENCIAS
-- ============================================

-- Obtener IDs de programas (ajustar según los IDs generados)
SET @programa_software = (SELECT id FROM `programs` WHERE nombre = 'Técnico en Programación de Software' LIMIT 1);
SET @tecnologo_software = (SELECT id FROM `programs` WHERE nombre = 'Tecnólogo en Análisis y Desarrollo de Software' LIMIT 1);
SET @tecnico_sistemas = (SELECT id FROM `programs` WHERE nombre = 'Técnico en Sistemas' LIMIT 1);
SET @tecnico_redes = (SELECT id FROM `programs` WHERE nombre = 'Técnico en Redes de Computadores' LIMIT 1);

-- COMPETENCIAS PARA: Técnico en Programación de Software
INSERT INTO `competencias` (`program_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
-- Competencia 1
(@programa_software, '210101001', 'Analizar los requisitos del cliente para desarrollar el software de acuerdo con las necesidades del mercado.', 'Analizar y documentar los requisitos funcionales y no funcionales del software a desarrollar.', 1, NOW(), NOW()),

-- Competencia 2
(@programa_software, '210101002', 'Construir la solución que cumpla con los requisitos de la necesidad planteada utilizando programación orientada a objetos.', 'Desarrollar software utilizando programación orientada a objetos, aplicando buenas prácticas de desarrollo.', 1, NOW(), NOW()),

-- Competencia 3
(@programa_software, '210101003', 'Aplicar metodologías ágiles de desarrollo de software según el requerimiento del proyecto.', 'Implementar metodologías ágiles como Scrum, Kanban para gestionar proyectos de software.', 1, NOW(), NOW()),

-- Competencia 4
(@programa_software, '210101004', 'Desarrollar aplicaciones web utilizando frameworks y herramientas de desarrollo.', 'Crear aplicaciones web modernas utilizando frameworks como Laravel, React, Vue.js.', 1, NOW(), NOW()),

-- Competencia 5
(@programa_software, '210101005', 'Gestionar bases de datos según el requerimiento del proyecto de software.', 'Diseñar, implementar y gestionar bases de datos relacionales y no relacionales.', 1, NOW(), NOW());

-- COMPETENCIAS PARA: Tecnólogo en Análisis y Desarrollo de Software
INSERT INTO `competencias` (`program_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
-- Competencia 1
(@tecnologo_software, '220501001', 'Analizar los requerimientos del cliente para construir el sistema de información.', 'Realizar análisis detallado de requerimientos funcionales y no funcionales del sistema.', 1, NOW(), NOW()),

-- Competencia 2
(@tecnologo_software, '220501002', 'Diseñar el sistema de información de acuerdo con los requerimientos del cliente.', 'Crear el diseño arquitectónico y de base de datos del sistema de información.', 1, NOW(), NOW()),

-- Competencia 3
(@tecnologo_software, '220501003', 'Construir el sistema que cumpla con los requerimientos de la solución informática.', 'Desarrollar el sistema de información utilizando tecnologías y frameworks apropiados.', 1, NOW(), NOW()),

-- Competencia 4
(@tecnologo_software, '220501004', 'Aplicar buenas prácticas de calidad en el proceso de desarrollo de software.', 'Implementar pruebas, revisiones de código y estándares de calidad en el desarrollo.', 1, NOW(), NOW()),

-- Competencia 5
(@tecnologo_software, '220501005', 'Gestionar proyectos de desarrollo de software según metodologías establecidas.', 'Planificar, ejecutar y controlar proyectos de desarrollo de software.', 1, NOW(), NOW());

-- COMPETENCIAS PARA: Técnico en Sistemas
INSERT INTO `competencias` (`program_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
-- Competencia 1
(@tecnico_sistemas, '228106001', 'Instalar y configurar software de aplicación según las necesidades del cliente.', 'Instalar y configurar sistemas operativos y software de aplicación.', 1, NOW(), NOW()),

-- Competencia 2
(@tecnico_sistemas, '228106002', 'Mantener el hardware de los equipos de cómputo según las especificaciones técnicas.', 'Realizar mantenimiento preventivo y correctivo de hardware de computadores.', 1, NOW(), NOW()),

-- Competencia 3
(@tecnico_sistemas, '228106003', 'Configurar redes de datos según protocolos y estándares técnicos.', 'Configurar y administrar redes locales de computadores.', 1, NOW(), NOW()),

-- Competencia 4
(@tecnico_sistemas, '228106004', 'Brindar soporte técnico a los usuarios según los requerimientos del sistema.', 'Atender y resolver problemas técnicos de usuarios finales.', 1, NOW(), NOW());

-- COMPETENCIAS PARA: Técnico en Redes de Computadores
INSERT INTO `competencias` (`program_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
-- Competencia 1
(@tecnico_redes, '228108001', 'Diseñar redes de computadores según estándares y protocolos establecidos.', 'Diseñar topologías de red y planos de cableado estructurado.', 1, NOW(), NOW()),

-- Competencia 2
(@tecnico_redes, '228108002', 'Instalar y configurar equipos de red según las especificaciones técnicas.', 'Instalar y configurar switches, routers, access points y otros equipos de red.', 1, NOW(), NOW()),

-- Competencia 3
(@tecnico_redes, '228108003', 'Administrar redes de computadores según protocolos y estándares técnicos.', 'Administrar y monitorear redes de computadores, configurar servicios de red.', 1, NOW(), NOW()),

-- Competencia 4
(@tecnico_redes, '228108004', 'Implementar seguridad en redes de computadores según políticas de la organización.', 'Configurar firewalls, VPNs y políticas de seguridad de red.', 1, NOW(), NOW());

-- ============================================
-- 4. INSERTAR RESULTADOS DE APRENDIZAJE (RA)
-- ============================================

-- Obtener IDs de competencias del programa Técnico en Programación de Software
SET @comp_210101001 = (SELECT id FROM `competencias` WHERE codigo = '210101001' AND program_id = @programa_software LIMIT 1);
SET @comp_210101002 = (SELECT id FROM `competencias` WHERE codigo = '210101002' AND program_id = @programa_software LIMIT 1);
SET @comp_210101003 = (SELECT id FROM `competencias` WHERE codigo = '210101003' AND program_id = @programa_software LIMIT 1);
SET @comp_210101004 = (SELECT id FROM `competencias` WHERE codigo = '210101004' AND program_id = @programa_software LIMIT 1);
SET @comp_210101005 = (SELECT id FROM `competencias` WHERE codigo = '210101005' AND program_id = @programa_software LIMIT 1);

-- RESULTADOS DE APRENDIZAJE para Competencia 210101001
INSERT INTO `learning_outcomes` (`competencia_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
(@comp_210101001, '21010100101', 'Identificar los requisitos funcionales y no funcionales del software.', 'Analizar y documentar los requisitos del cliente para el desarrollo del software.', 1, NOW(), NOW()),
(@comp_210101001, '21010100102', 'Elaborar la documentación técnica del proyecto de software.', 'Crear diagramas UML, casos de uso y documentación técnica del proyecto.', 1, NOW(), NOW()),
(@comp_210101001, '21010100103', 'Validar los requisitos con el cliente según los estándares establecidos.', 'Presentar y validar los requisitos con el cliente antes de iniciar el desarrollo.', 1, NOW(), NOW());

-- RESULTADOS DE APRENDIZAJE para Competencia 210101002
INSERT INTO `learning_outcomes` (`competencia_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
(@comp_210101002, '21010100201', 'Aplicar principios de programación orientada a objetos en el desarrollo de software.', 'Implementar clases, objetos, herencia, polimorfismo y encapsulamiento.', 1, NOW(), NOW()),
(@comp_210101002, '21010100202', 'Desarrollar código siguiendo estándares y buenas prácticas de programación.', 'Escribir código limpio, legible y mantenible siguiendo convenciones de código.', 1, NOW(), NOW()),
(@comp_210101002, '21010100203', 'Implementar pruebas unitarias para validar el funcionamiento del software.', 'Crear y ejecutar pruebas unitarias utilizando frameworks de testing.', 1, NOW(), NOW());

-- RESULTADOS DE APRENDIZAJE para Competencia 210101003
INSERT INTO `learning_outcomes` (`competencia_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
(@comp_210101003, '21010100301', 'Aplicar metodología Scrum en el desarrollo de proyectos de software.', 'Organizar el trabajo en sprints, crear user stories y realizar daily standups.', 1, NOW(), NOW()),
(@comp_210101003, '21010100302', 'Utilizar herramientas de gestión de proyectos ágiles.', 'Usar herramientas como Jira, Trello o Azure DevOps para gestionar proyectos.', 1, NOW(), NOW()),
(@comp_210101003, '21010100303', 'Participar en ceremonias ágiles según la metodología establecida.', 'Participar en planning, review, retrospective y daily standup meetings.', 1, NOW(), NOW());

-- RESULTADOS DE APRENDIZAJE para Competencia 210101004
INSERT INTO `learning_outcomes` (`competencia_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
(@comp_210101004, '21010100401', 'Desarrollar aplicaciones web frontend utilizando frameworks modernos.', 'Crear interfaces de usuario utilizando React, Vue.js o Angular.', 1, NOW(), NOW()),
(@comp_210101004, '21010100402', 'Desarrollar aplicaciones web backend utilizando frameworks de desarrollo.', 'Crear APIs RESTful utilizando Laravel, Express.js o Django.', 1, NOW(), NOW()),
(@comp_210101004, '21010100403', 'Integrar frontend y backend para crear aplicaciones web completas.', 'Conectar el frontend con el backend mediante APIs y servicios web.', 1, NOW(), NOW());

-- RESULTADOS DE APRENDIZAJE para Competencia 210101005
INSERT INTO `learning_outcomes` (`competencia_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
(@comp_210101005, '21010100501', 'Diseñar modelos de base de datos según los requerimientos del sistema.', 'Crear diagramas entidad-relación y normalizar bases de datos.', 1, NOW(), NOW()),
(@comp_210101005, '21010100502', 'Implementar bases de datos relacionales utilizando SQL.', 'Crear tablas, índices, relaciones y consultas SQL complejas.', 1, NOW(), NOW()),
(@comp_210101005, '21010100503', 'Gestionar bases de datos utilizando sistemas gestores de bases de datos.', 'Administrar bases de datos MySQL, PostgreSQL o SQL Server.', 1, NOW(), NOW());

-- RESULTADOS DE APRENDIZAJE para Tecnólogo (algunos ejemplos)
SET @comp_220501001 = (SELECT id FROM `competencias` WHERE codigo = '220501001' AND program_id = @tecnologo_software LIMIT 1);
SET @comp_220501002 = (SELECT id FROM `competencias` WHERE codigo = '220501002' AND program_id = @tecnologo_software LIMIT 1);

INSERT INTO `learning_outcomes` (`competencia_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
(@comp_220501001, '22050100101', 'Analizar el contexto organizacional para identificar necesidades de información.', 'Realizar análisis del negocio y contexto organizacional.', 1, NOW(), NOW()),
(@comp_220501001, '22050100102', 'Elaborar el documento de especificación de requerimientos del sistema.', 'Documentar requerimientos funcionales y no funcionales del sistema.', 1, NOW(), NOW()),
(@comp_220501002, '22050100201', 'Diseñar la arquitectura del sistema de información.', 'Crear el diseño arquitectónico del sistema utilizando patrones de diseño.', 1, NOW(), NOW()),
(@comp_220501002, '22050100202', 'Diseñar el modelo de datos del sistema de información.', 'Crear el modelo de datos y diagramas de base de datos.', 1, NOW(), NOW());

-- RESULTADOS DE APRENDIZAJE para Técnico en Sistemas
SET @comp_228106001 = (SELECT id FROM `competencias` WHERE codigo = '228106001' AND program_id = @tecnico_sistemas LIMIT 1);
SET @comp_228106002 = (SELECT id FROM `competencias` WHERE codigo = '228106002' AND program_id = @tecnico_sistemas LIMIT 1);

INSERT INTO `learning_outcomes` (`competencia_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
(@comp_228106001, '22810600101', 'Instalar sistemas operativos según las especificaciones técnicas.', 'Instalar y configurar Windows, Linux y otros sistemas operativos.', 1, NOW(), NOW()),
(@comp_228106001, '22810600102', 'Configurar software de aplicación según las necesidades del usuario.', 'Instalar y configurar software ofimático, navegadores y aplicaciones.', 1, NOW(), NOW()),
(@comp_228106002, '22810600201', 'Realizar mantenimiento preventivo de hardware de computadores.', 'Limpiar, revisar y mantener componentes físicos de computadores.', 1, NOW(), NOW()),
(@comp_228106002, '22810600202', 'Diagnosticar y reparar fallas de hardware de computadores.', 'Identificar y solucionar problemas de hardware de equipos de cómputo.', 1, NOW(), NOW());

-- RESULTADOS DE APRENDIZAJE para Técnico en Redes
SET @comp_228108001 = (SELECT id FROM `competencias` WHERE codigo = '228108001' AND program_id = @tecnico_redes LIMIT 1);
SET @comp_228108002 = (SELECT id FROM `competencias` WHERE codigo = '228108002' AND program_id = @tecnico_redes LIMIT 1);

INSERT INTO `learning_outcomes` (`competencia_id`, `codigo`, `nombre`, `descripcion`, `activo`, `created_at`, `updated_at`)
VALUES
(@comp_228108001, '22810800101', 'Diseñar topologías de red según estándares de cableado estructurado.', 'Crear diseños de red LAN y WAN siguiendo estándares TIA/EIA.', 1, NOW(), NOW()),
(@comp_228108001, '22810800102', 'Elaborar planos de cableado estructurado según especificaciones técnicas.', 'Crear planos técnicos de instalación de cableado de red.', 1, NOW(), NOW()),
(@comp_228108002, '22810800201', 'Configurar switches y routers según protocolos de red.', 'Configurar equipos de red Cisco, TP-Link u otros fabricantes.', 1, NOW(), NOW()),
(@comp_228108002, '22810800202', 'Implementar VLANs y protocolos de enrutamiento.', 'Configurar VLANs, STP, OSPF y otros protocolos de red.', 1, NOW(), NOW());

-- ============================================
-- FIN DEL SCRIPT
-- ============================================

-- Verificar datos insertados
SELECT 'Usuarios creados:' AS Tipo, COUNT(*) AS Cantidad FROM `users` WHERE role IN ('admin', 'coordinator')
UNION ALL
SELECT 'Programas creados:', COUNT(*) FROM `programs`
UNION ALL
SELECT 'Competencias creadas:', COUNT(*) FROM `competencias`
UNION ALL
SELECT 'Resultados de Aprendizaje creados:', COUNT(*) FROM `learning_outcomes`;

