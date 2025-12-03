<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Program;
use App\Models\Competencia;
use App\Models\LearningOutcome;

class TecnologiaADSSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info("\n═══════════════════════════════════════════════════════════");
        $this->command->info("  CREACIÓN DE PROGRAMA: TECNOLOGÍA EN ADS");
        $this->command->info("═══════════════════════════════════════════════════════════\n");

        DB::beginTransaction();

        try {
            // 1. Crear el programa
            $this->command->info("[1/3] Creando programa...");
            $program = Program::updateOrCreate(
                ['codigo' => '228106'],
                [
                    'nombre' => 'Tecnología en Análisis y Desarrollo de Software',
                    'descripcion' => 'Programa de formación tecnológica que desarrolla competencias para analizar, diseñar, desarrollar, implementar y mantener soluciones de software según estándares técnicos y metodologías de desarrollo.',
                    'duracion_meses' => 24,
                    'nivel' => 'Tecnología',
                    'activo' => true,
                ]
            );
            $this->command->info("   ✅ Programa: {$program->nombre} (ID: {$program->id})");
            $this->command->info("   Código: {$program->codigo}\n");

            // 2. Definir competencias y sus resultados de aprendizaje
            $competencias = $this->getCompetencias();

            // 3. Crear competencias y sus resultados de aprendizaje
            $this->command->info("[2/3] Creando competencias y resultados de aprendizaje...");
            $totalCompetencias = 0;
            $totalRAs = 0;

            foreach ($competencias as $competenciaData) {
                $competencia = Competencia::updateOrCreate(
                    [
                        'codigo' => $competenciaData['codigo'],
                        'program_id' => $program->id,
                    ],
                    [
                        'nombre' => $competenciaData['nombre'],
                        'descripcion' => $competenciaData['descripcion'] ?? $competenciaData['nombre'],
                        'activo' => true,
                    ]
                );
                $totalCompetencias++;
                $raCount = count($competenciaData['learning_outcomes']);
                $this->command->info("   ✅ Competencia: {$competencia->codigo} - " . substr($competencia->nombre, 0, 60) . "... ({$raCount} RAs)");

                // Crear resultados de aprendizaje
                foreach ($competenciaData['learning_outcomes'] as $raData) {
                    $learningOutcome = LearningOutcome::updateOrCreate(
                        [
                            'codigo' => $raData['codigo'],
                            'competencia_id' => $competencia->id,
                        ],
                        [
                            'nombre' => $raData['nombre'],
                            'descripcion' => $raData['nombre'],
                            'activo' => true,
                        ]
                    );
                    $totalRAs++;
                }
            }

            $this->command->info("\n[3/3] Resumen:");
            $this->command->info("   ✅ Programa: {$program->nombre}");
            $this->command->info("   ✅ Competencias creadas: {$totalCompetencias}");
            $this->command->info("   ✅ Resultados de Aprendizaje creados: {$totalRAs}");

            DB::commit();
            $this->command->info("\n🎉 PROCESO COMPLETADO EXITOSAMENTE.");
            $this->command->info("   El programa 'Tecnología en Análisis y Desarrollo de Software' ha sido creado con todas sus competencias y resultados de aprendizaje.\n");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("\n❌ ERROR: " . $e->getMessage());
            $this->command->error($e->getTraceAsString());
            throw $e;
        }
    }

    private function getCompetencias(): array
    {
        return [
            [
                'codigo' => '240201500',
                'nombre' => 'APLICACIÓN DE CONOCIMIENTOS DE LAS CIENCIAS NATURALES DE ACUERDO CON SITUACIONES DEL CONTEXTO PRODUCTIVO Y SOCIAL.',
                'descripcion' => 'Aplicar principios y leyes de las ciencias naturales en la solución de problemas del contexto productivo y social.',
                'learning_outcomes' => [
                    ['codigo' => 'RA24020150001', 'nombre' => 'IDENTIFICAR LOS PRINCIPIOS Y LEYES DE LA FÍSICA EN LA SOLUCIÓN DE PROBLEMAS DE ACUERDO AL CONTEXTO PRODUCTIVO.'],
                    ['codigo' => 'RA24020150002', 'nombre' => 'SOLUCIONAR PROBLEMAS ASOCIADOS CON EL SECTOR PRODUCTIVO CON BASE EN LOS PRINCIPIOS Y LEYES DE LA FÍSICA.'],
                    ['codigo' => 'RA24020150003', 'nombre' => 'VERIFICAR LAS TRANSFORMACIONES FÍSICAS DE LA MATERIA UTILIZANDO HERRAMIENTAS TECNOLÓGICAS.'],
                    ['codigo' => 'RA24020150004', 'nombre' => 'PROPONER ACCIONES DE MEJORA EN LOS PROCESOS PRODUCTIVOS DE ACUERDO CON LOS PRINCIPIOS Y LEYES DE LA FÍSICA.'],
                ]
            ],
            [
                'codigo' => '240201501',
                'nombre' => 'APLICAR PRÁCTICAS DE PROTECCIÓN AMBIENTAL, SEGURIDAD Y SALUD EN EL TRABAJO DE ACUERDO CON LAS POLÍTICAS ORGANIZACIONALES Y LA NORMATIVIDAD VIGENTE.',
                'descripcion' => 'Aplicar estrategias para la prevención y control de impactos ambientales y accidentes laborales según normatividad vigente.',
                'learning_outcomes' => [
                    ['codigo' => 'RA24020150101', 'nombre' => 'ANALIZAR LAS ESTRATEGIAS PARA LA PREVENCIÓN Y CONTROL DE LOS IMPACTOS AMBIENTALES Y DE LOS ACCIDENTES Y ENFERMEDADES LABORALES (ATEL) DE ACUERDO CON LAS POLÍTICAS ORGANIZACIONALES Y EL ENTORNO SOCIAL.'],
                    ['codigo' => 'RA24020150102', 'nombre' => 'IMPLEMENTAR ESTRATEGIAS PARA EL CONTROL DE LOS IMPACTOS AMBIENTALES Y DE LOS ACCIDENTES Y ENFERMEDADES DE ACUERDO CON LOS PLANES Y PROGRAMAS ESTABLECIDOS POR LA ORGANIZACIÓN.'],
                    ['codigo' => 'RA24020150103', 'nombre' => 'REALIZAR SEGUIMIENTO Y ACOMPAÑAMIENTO AL DESARROLLO DE LOS PLANES Y PROGRAMAS AMBIENTALES Y SST, SEGÚN EL ÁREA DE DESEMPEÑO.'],
                    ['codigo' => 'RA24020150104', 'nombre' => 'PROPONER ACCIONES DE MEJORA PARA EL MANEJO AMBIENTAL Y EL CONTROL DE LA SST, DE ACUERDO CON ESTRATEGIAS DE TRABAJO, COLABORATIVO, COOPERATIVO Y COORDINADO EN EL CONTEXTO PRODUCTIVO Y SOCIAL.'],
                ]
            ],
            [
                'codigo' => '220501032',
                'nombre' => 'Controlar la calidad del servicio de software de acuerdo con los estándares técnicos',
                'descripcion' => 'Aplicar actividades de aseguramiento y verificación de la calidad del software según estándares de la industria.',
                'learning_outcomes' => [
                    ['codigo' => 'RA22050103201', 'nombre' => 'INCORPORAR ACTIVIDADES DE ASEGURAMIENTO DE LA CALIDAD DEL SOFTWARE DE ACUERDO CON ESTÁNDARES DE LA INDUSTRIA.'],
                    ['codigo' => 'RA22050103202', 'nombre' => 'VERIFICAR LA CALIDAD DEL SOFTWARE DE ACUERDO CON LAS PRÁCTICAS ASOCIADAS EN LOS PROCESOS DE DESARROLLO.'],
                    ['codigo' => 'RA22050103203', 'nombre' => 'REALIZAR ACTIVIDADES DE MEJORA DE LA CALIDAD DEL SOFTWARE A PARTIR DE LOS RESULTADOS DE LA VERIFICACIÓN.'],
                ]
            ],
            [
                'codigo' => '220501033',
                'nombre' => 'DESARROLLAR LA SOLUCIÓN DE SOFTWARE DE ACUERDO CON EL DISEÑO Y METODOLOGÍAS DE DESARROLLO',
                'descripcion' => 'Construir la solución de software siguiendo el diseño establecido y metodologías de desarrollo.',
                'learning_outcomes' => [
                    ['codigo' => 'RA22050103301', 'nombre' => 'PLANEAR ACTIVIDADES DE CONSTRUCCIÓN DEL SOFTWARE DE ACUERDO CON EL DISEÑO ESTABLECIDO.'],
                    ['codigo' => 'RA22050103302', 'nombre' => 'CONSTRUIR LA BASE DE DATOS PARA EL SOFTWARE A PARTIR DEL MODELO DE DATOS.'],
                    ['codigo' => 'RA22050103303', 'nombre' => 'CREAR COMPONENTES FRONT-END DEL SOFTWARE DE ACUERDO CON EL DISEÑO.'],
                    ['codigo' => 'RA22050103304', 'nombre' => 'CODIFICAR EL SOFTWARE DE ACUERDO CON EL DISEÑO ESTABLECIDO.'],
                    ['codigo' => 'RA22050103305', 'nombre' => 'REALIZAR PRUEBAS AL SOFTWARE PARA VERIFICAR SU FUNCIONALIDAD.'],
                ]
            ],
            [
                'codigo' => '240201502',
                'nombre' => 'DESARROLLAR PROCESOS DE COMUNICACIÓN EFICACES Y EFECTIVOS, TENIENDO EN CUENTA SITUACIONES DE ORDEN SOCIAL, PERSONAL Y PRODUCTIVO.',
                'descripcion' => 'Desarrollar habilidades comunicativas para interactuar efectivamente en contextos sociales, personales y productivos.',
                'learning_outcomes' => [
                    ['codigo' => 'RA24020150201', 'nombre' => 'ANALIZAR LOS COMPONENTES DE LA COMUNICACIÓN SEGÚN SUS CARACTERÍSTICAS, INTENCIONALIDAD Y CONTEXTO.'],
                    ['codigo' => 'RA24020150202', 'nombre' => 'ARGUMENTAR EN FORMA ORAL Y ESCRITA ATENDIENDO LAS EXIGENCIAS Y PARTICULARIDADES DE LAS DIVERSAS SITUACIONES COMUNICATIVAS MEDIANTE LOS DISTINTOS SISTEMAS DE REPRESENTACIÓN.'],
                    ['codigo' => 'RA24020150203', 'nombre' => 'RELACIONAR LOS PROCESOS COMUNICATIVOS TENIENDO EN CUENTA CRITERIOS DE LÓGICA Y RACIONALIDAD.'],
                    ['codigo' => 'RA24020150204', 'nombre' => 'ESTABLECER PROCESOS DE ENRIQUECIMIENTO LEXICAL Y ACCIONES DE MEJORAMIENTO EN EL DESARROLLO DE PROCESOS COMUNICATIVOS SEGÚN REQUERIMIENTOS DEL CONTEXTO.'],
                ]
            ],
            [
                'codigo' => '220501031',
                'nombre' => 'Diseñar la solución de software de acuerdo con procedimientos y requisitos técnicos',
                'descripcion' => 'Elaborar los artefactos de diseño del software siguiendo metodologías y estándares establecidos.',
                'learning_outcomes' => [
                    ['codigo' => 'RA22050103101', 'nombre' => 'ELABORAR LOS ARTEFACTOS DE DISEÑO DEL SOFTWARE SIGUIENDO LAS PRÁCTICAS DE LA METODOLOGÍA SELECCIONADA.'],
                    ['codigo' => 'RA22050103102', 'nombre' => 'ESTRUCTURAR EL MODELO DE DATOS DEL SOFTWARE DE ACUERDO CON LAS ESPECIFICACIONES DEL ANÁLISIS.'],
                    ['codigo' => 'RA22050103103', 'nombre' => 'DETERMINAR LAS CARACTERÍSTICAS TÉCNICAS DE LA INTERFAZ GRÁFICA DEL SOFTWARE ADOPTANDO ESTÁNDARES.'],
                    ['codigo' => 'RA22050103104', 'nombre' => 'VERIFICAR LOS ENTREGABLES DE LA FASE DE DISEÑO DEL SOFTWARE DE ACUERDO CON LO ESTABLECIDO EN EL INFORME DE ANÁLISIS.'],
                ]
            ],
            [
                'codigo' => '240201503',
                'nombre' => 'Ejercer derechos fundamentales del trabajo en el marco de la constitución política y los convenios internacionales.',
                'descripcion' => 'Reconocer y ejercer los derechos fundamentales del trabajo según la Constitución Política y convenios internacionales.',
                'learning_outcomes' => [
                    ['codigo' => 'RA24020150301', 'nombre' => 'Reconocer el trabajo como factor de movilidad social y transformación vital con referencia a la fenomenología y a los derechos fundamentales en el trabajo.'],
                    ['codigo' => 'RA24020150302', 'nombre' => 'Valorar la importancia de la ciudadanía laboral con base en el estudio de los derechos humanos y fundamentales en el trabajo.'],
                    ['codigo' => 'RA24020150303', 'nombre' => 'Practicar los derechos fundamentales en el trabajo de acuerdo con la Constitución Política y los Convenios Internacionales.'],
                    ['codigo' => 'RA24020150304', 'nombre' => 'Participar en acciones solidarias teniendo en cuenta el ejercicio de los derechos humanos, de los pueblos y de la naturaleza.'],
                ]
            ],
            [
                'codigo' => '240201504',
                'nombre' => 'Interactuar en el contexto productivo y social de acuerdo con principios éticos para la construcción de una cultura de paz.',
                'descripcion' => 'Promover la dignidad humana y relaciones de crecimiento personal y comunitario basadas en principios éticos.',
                'learning_outcomes' => [
                    ['codigo' => 'RA24020150401', 'nombre' => 'PROMOVER MI DIGNIDAD Y LA DEL OTRO A PARTIR DE LOS PRINCIPIOS Y VALORES ÉTICOS COMO APORTE EN LA INSTAURACIÓN DE UNA CULTURA DE PAZ.'],
                    ['codigo' => 'RA24020150402', 'nombre' => 'ESTABLECER RELACIONES DE CRECIMIENTO PERSONAL Y COMUNITARIO A PARTIR DEL BIEN COMÚN COMO APORTE PARA EL DESARROLLO SOCIAL.'],
                    ['codigo' => 'RA24020150403', 'nombre' => 'PROMOVER EL USO RACIONAL DE LOS RECURSOS NATURALES A PARTIR DE CRITERIOS DE SOSTENIBILIDAD Y SUSTENTABILIDAD ÉTICA Y NORMATIVA VIGENTE.'],
                    ['codigo' => 'RA24020150404', 'nombre' => 'CONTRIBUIR CON EL FORTALECIMIENTO DE LA CULTURA DE PAZ A PARTIR DE LA DIGNIDAD HUMANA Y LAS ESTRATEGIAS PARA LA TRANSFORMACIÓN DE CONFLICTOS.'],
                ]
            ],
            [
                'codigo' => '220501029',
                'nombre' => 'Establecer requisitos de la solución de software de acuerdo con estándares y procedimiento técnico',
                'descripcion' => 'Caracterizar procesos organizacionales y establecer requisitos del software según necesidades del cliente.',
                'learning_outcomes' => [
                    ['codigo' => 'RA22050102901', 'nombre' => 'CARACTERIZAR LOS PROCESOS DE LA ORGANIZACIÓN DE ACUERDO CON EL SOFTWARE A CONSTRUIR.'],
                    ['codigo' => 'RA22050102902', 'nombre' => 'RECOLECTAR INFORMACIÓN DEL SOFTWARE A CONSTRUIR DE ACUERDO CON LAS NECESIDADES DEL CLIENTE.'],
                    ['codigo' => 'RA22050102903', 'nombre' => 'ESTABLECER LOS REQUISITOS DEL SOFTWARE DE ACUERDO CON LA INFORMACIÓN RECOLECTADA.'],
                    ['codigo' => 'RA22050102904', 'nombre' => 'VALIDAR EL INFORME DE REQUISITOS DE ACUERDO CON LAS NECESIDADES DEL CLIENTE.'],
                ]
            ],
            [
                'codigo' => '220501034',
                'nombre' => 'Estructurar propuesta técnica de servicio de tecnología de la información según requisitos técnicos y normativa',
                'descripcion' => 'Definir especificaciones técnicas y elaborar propuesta técnica del software según requisitos.',
                'learning_outcomes' => [
                    ['codigo' => 'RA22050103401', 'nombre' => 'DEFINIR ESPECIFICACIONES TÉCNICAS DEL SOFTWARE DE ACUERDO CON LAS CARACTERÍSTICAS DEL SOFTWARE A CONSTRUIR.'],
                    ['codigo' => 'RA22050103402', 'nombre' => 'ELABORAR PROPUESTA TÉCNICA DEL SOFTWARE DE ACUERDO CON LAS ESPECIFICACIONES TÉCNICAS DEFINIDAS.'],
                    ['codigo' => 'RA22050103403', 'nombre' => 'VALIDAR LAS CONDICIONES DE LA PROPUESTA TÉCNICA DEL SOFTWARE DE ACUERDO CON LOS INTERESES DE LAS PARTES.'],
                ]
            ],
            [
                'codigo' => '220501030',
                'nombre' => 'Evaluar requisitos de la solución de software de acuerdo con metodologías de análisis y estándares',
                'descripcion' => 'Planear y ejecutar actividades de análisis de requisitos según metodologías establecidas.',
                'learning_outcomes' => [
                    ['codigo' => 'RA22050103001', 'nombre' => 'PLANEAR ACTIVIDADES DE ANÁLISIS DE ACUERDO CON LA METODOLOGÍA SELECCIONADA.'],
                    ['codigo' => 'RA22050103002', 'nombre' => 'MODELAR LAS FUNCIONES DEL SOFTWARE DE ACUERDO CON EL INFORME DE REQUISITOS.'],
                    ['codigo' => 'RA22050103003', 'nombre' => 'DESARROLLAR PROCESOS LÓGICOS A TRAVÉS DE LA IMPLEMENTACIÓN DE ALGORITMOS.'],
                    ['codigo' => 'RA22050103004', 'nombre' => 'VERIFICAR LOS MODELOS REALIZADOS EN LA FASE DE ANÁLISIS DE ACUERDO CON LO ESTABLECIDO EN EL INFORME DE REQUISITOS.'],
                ]
            ],
            [
                'codigo' => '240201505',
                'nombre' => 'GENERAR HÁBITOS SALUDABLES DE VIDA MEDIANTE LA APLICACIÓN DE PROGRAMAS DE ACTIVIDAD FÍSICA EN LOS CONTEXTOS PRODUCTIVOS Y SOCIALES.',
                'descripcion' => 'Desarrollar hábitos saludables mediante programas de actividad física y nutrición.',
                'learning_outcomes' => [
                    ['codigo' => 'RA24020150501', 'nombre' => 'DESARROLLAR HABILIDADES PSICOMOTRICES EN EL CONTEXTO PRODUCTIVO Y SOCIAL.'],
                    ['codigo' => 'RA24020150502', 'nombre' => 'PRACTICAR HÁBITOS SALUDABLES MEDIANTE LA APLICACIÓN DE FUNDAMENTOS DE NUTRICIÓN E HIGIENE.'],
                    ['codigo' => 'RA24020150503', 'nombre' => 'EJECUTAR ACTIVIDADES DE ACONDICIONAMIENTO FÍSICO ORIENTADAS HACIA EL MEJORAMIENTO DE LA CONDICIÓN FÍSICA EN LOS CONTEXTOS PRODUCTIVO Y SOCIAL.'],
                    ['codigo' => 'RA24020150504', 'nombre' => 'IMPLEMENTAR UN PLAN DE ERGONOMÍA Y PAUSAS ACTIVAS SEGÚN LAS CARACTERÍSTICAS DE LA FUNCIÓN PRODUCTIVA.'],
                ]
            ],
            [
                'codigo' => '240201506',
                'nombre' => 'Gestionar procesos propios de la cultura emprendedora y empresarial de acuerdo con el perfil personal y los requerimientos de los contextos productivo y social.',
                'descripcion' => 'Integrar elementos de la cultura emprendedora y estructurar planes de negocio según oportunidades del mercado.',
                'learning_outcomes' => [
                    ['codigo' => 'RA24020150601', 'nombre' => 'INTEGRAR ELEMENTOS DE LA CULTURA EMPRENDEDORA TENIENDO EN CUENTA EL PERFIL PERSONAL Y EL CONTEXTO DE DESARROLLO SOCIAL.'],
                    ['codigo' => 'RA24020150602', 'nombre' => 'CARACTERIZAR LA IDEA DE NEGOCIO TENIENDO EN CUENTA LAS OPORTUNIDADES Y NECESIDADES DEL SECTOR PRODUCTIVO Y SOCIAL.'],
                    ['codigo' => 'RA24020150603', 'nombre' => 'ESTRUCTURAR EL PLAN DE NEGOCIO DE ACUERDO CON LAS CARACTERÍSTICAS EMPRESARIALES Y TENDENCIAS DE MERCADO.'],
                    ['codigo' => 'RA24020150604', 'nombre' => 'VALORAR LA PROPUESTA DE NEGOCIO CONFORME CON SU ESTRUCTURA Y NECESIDADES DEL SECTOR PRODUCTIVO Y SOCIAL.'],
                ]
            ],
            [
                'codigo' => '220501035',
                'nombre' => 'Implementar la solución de software de acuerdo con los requisitos de operación y modelos de referencia',
                'descripcion' => 'Planear y ejecutar actividades de implantación del software según arquitectura y políticas establecidas.',
                'learning_outcomes' => [
                    ['codigo' => 'RA22050103501', 'nombre' => 'PLANEAR ACTIVIDADES DE IMPLANTACIÓN DEL SOFTWARE DE ACUERDO CON LAS CONDICIONES DEL SISTEMA.'],
                    ['codigo' => 'RA22050103502', 'nombre' => 'DESPLEGAR EL SOFTWARE DE ACUERDO CON LA ARQUITECTURA Y LAS POLÍTICAS ESTABLECIDAS.'],
                    ['codigo' => 'RA22050103503', 'nombre' => 'DOCUMENTAR EL PROCESO DE IMPLANTACIÓN DE SOFTWARE SIGUIENDO ESTÁNDARES DE CALIDAD.'],
                    ['codigo' => 'RA22050103504', 'nombre' => 'IMPLANTAR EL SOFTWARE DE ACUERDO CON LOS NIVELES DE SERVICIO ESTABLECIDOS CON EL CLIENTE.'],
                ]
            ],
            [
                'codigo' => '240201507',
                'nombre' => 'INTERACTUAR EN LENGUA INGLESA DE FORMA ORAL Y ESCRITA DENTRO DE CONTEXTOS SOCIALES Y LABORALES SEGÚN LOS CRITERIOS ESTABLECIDOS POR EL MARCO COMÚN EUROPEO DE REFERENCIA PARA LAS LENGUAS.',
                'descripcion' => 'Desarrollar competencias comunicativas en inglés para contextos sociales y laborales según MCER.',
                'learning_outcomes' => [
                    ['codigo' => 'RA24020150701', 'nombre' => 'COMPRENDER INFORMACIÓN SOBRE SITUACIONES COTIDIANAS Y LABORALES ACTUALES Y FUTURAS A TRAVÉS DE INTERACCIONES SOCIALES DE FORMA ORAL Y ESCRITA.'],
                    ['codigo' => 'RA24020150702', 'nombre' => 'INTERCAMBIAR OPINIONES SOBRE SITUACIONES COTIDIANAS Y LABORALES ACTUALES, PASADAS Y FUTURAS EN CONTEXTOS SOCIALES ORALES Y ESCRITOS.'],
                    ['codigo' => 'RA24020150703', 'nombre' => 'DISCUTIR SOBRE POSIBLES SOLUCIONES A PROBLEMAS DENTRO DE UN RANGO VARIADO DE CONTEXTOS SOCIALES Y LABORALES.'],
                    ['codigo' => 'RA24020150704', 'nombre' => 'IMPLEMENTAR ACCIONES DE MEJORA RELACIONADAS CON EL USO DE EXPRESIONES, ESTRUCTURAS Y DESEMPEÑO SEGÚN LOS RESULTADOS DE APRENDIZAJE FORMULADOS PARA EL PROGRAMA.'],
                    ['codigo' => 'RA24020150705', 'nombre' => 'PRESENTAR UN PROCESO PARA LA REALIZACIÓN DE UNA ACTIVIDAD EN SU QUEHACER LABORAL DE ACUERDO CON LOS PROCEDIMIENTOS ESTABLECIDOS DESDE SU PROGRAMA DE FORMACIÓN.'],
                    ['codigo' => 'RA24020150706', 'nombre' => 'EXPLICAR LAS FUNCIONES DE SU OCUPACIÓN LABORAL USANDO EXPRESIONES DE ACUERDO AL NIVEL REQUERIDO POR EL PROGRAMA DE FORMACIÓN.'],
                ]
            ],
            [
                'codigo' => '240201508',
                'nombre' => 'Orientar investigación formativa según referentes técnicos',
                'descripcion' => 'Estructurar y desarrollar proyectos de investigación formativa según referentes técnicos.',
                'learning_outcomes' => [
                    ['codigo' => 'RA24020150801', 'nombre' => 'ANALIZAR EL CONTEXTO PRODUCTIVO SEGÚN SUS CARACTERÍSTICAS Y NECESIDADES.'],
                    ['codigo' => 'RA24020150802', 'nombre' => 'ESTRUCTURAR EL PROYECTO DE ACUERDO A CRITERIOS DE LA INVESTIGACIÓN.'],
                    ['codigo' => 'RA24020150803', 'nombre' => 'ARGUMENTAR ASPECTOS TEÓRICOS DEL PROYECTO SEGÚN REFERENTES NACIONALES E INTERNACIONALES.'],
                    ['codigo' => 'RA24020150804', 'nombre' => 'PROPONER SOLUCIONES A LAS NECESIDADES DEL CONTEXTO SEGÚN RESULTADOS DE LA INVESTIGACIÓN.'],
                ]
            ],
            [
                'codigo' => '240201509',
                'nombre' => 'Razonar cuantitativamente frente a situaciones susceptibles de ser abordadas de manera matemática en contextos laborales, sociales y personales.',
                'descripcion' => 'Identificar y resolver problemas matemáticos en contextos laborales, sociales y personales.',
                'learning_outcomes' => [
                    ['codigo' => 'RA24020150901', 'nombre' => 'IDENTIFICAR MODELOS MATEMÁTICOS DE ACUERDO CON LOS REQUERIMIENTOS DEL PROBLEMA PLANTEADO EN CONTEXTOS SOCIALES Y PRODUCTIVO.'],
                    ['codigo' => 'RA24020150902', 'nombre' => 'PLANTEAR PROBLEMAS MATEMÁTICOS A PARTIR DE SITUACIONES GENERADAS EN EL CONTEXTO SOCIAL Y PRODUCTIVO.'],
                    ['codigo' => 'RA24020150903', 'nombre' => 'RESOLVER PROBLEMAS MATEMÁTICOS A PARTIR DE SITUACIONES GENERADAS EN EL CONTEXTO SOCIAL Y PRODUCTIVO.'],
                    ['codigo' => 'RA24020150904', 'nombre' => 'PROPONER ACCIONES DE MEJORA FRENTE A LOS RESULTADOS DE LOS PROCEDIMIENTOS MATEMÁTICOS DE ACUERDO CON EL PROBLEMA PLANTEADO.'],
                ]
            ],
            [
                'codigo' => 'RA-INDUCCION',
                'nombre' => 'Resultado de Aprendizaje de la Inducción.',
                'descripcion' => 'Identificar la dinámica organizacional del SENA y el rol de la formación profesional integral.',
                'learning_outcomes' => [
                    ['codigo' => 'RA-INDUCCION-01', 'nombre' => 'IDENTIFICAR LA DINÁMICA ORGANIZACIONAL DEL SENA Y EL ROL DE LA FORMACIÓN PROFESIONAL INTEGRAL DE ACUERDO CON SU PROYECTO DE VIDA Y EL DESARROLLO PROFESIONAL.'],
                ]
            ],
            [
                'codigo' => 'RA-ETAPA-PRACTICA',
                'nombre' => 'RESULTADOS DE APRENDIZAJE ETAPA PRACTICA',
                'descripcion' => 'Aplicar conocimientos, habilidades y destrezas en la resolución de problemas reales del sector productivo.',
                'learning_outcomes' => [
                    ['codigo' => 'RA-ETAPA-PRACTICA-01', 'nombre' => 'APLICAR EN LA RESOLUCIÓN DE PROBLEMAS REALES DEL SECTOR PRODUCTIVO, LOS CONOCIMIENTOS, HABILIDADES Y DESTREZAS PERTINENTES A LAS COMPETENCIAS DEL PROGRAMA DE FORMACIÓN ASUMIENDO ESTRATEGIAS Y METODOLOGÍAS DE AUTOGESTIÓN'],
                ]
            ],
            [
                'codigo' => '240201510',
                'nombre' => 'Utilizar herramientas informáticas de acuerdo con las necesidades de manejo de información',
                'descripcion' => 'Aplicar herramientas TIC para el procesamiento de información según necesidades y procedimientos establecidos.',
                'learning_outcomes' => [
                    ['codigo' => 'RA24020151001', 'nombre' => 'ALISTAR HERRAMIENTAS DE TECNOLOGÍAS DE LA INFORMACIÓN Y LA COMUNICACIÓN (TIC), DE ACUERDO CON LAS NECESIDADES DE PROCESAMIENTO DE INFORMACIÓN Y COMUNICACIÓN.'],
                    ['codigo' => 'RA24020151002', 'nombre' => 'APLICAR FUNCIONALIDADES DE HERRAMIENTAS Y SERVICIOS TIC, DE ACUERDO CON MANUALES DE USO, PROCEDIMIENTOS ESTABLECIDOS Y BUENAS PRÁCTICAS.'],
                    ['codigo' => 'RA24020151003', 'nombre' => 'EVALUAR LOS RESULTADOS, DE ACUERDO CON LOS REQUERIMIENTOS.'],
                    ['codigo' => 'RA24020151004', 'nombre' => 'OPTIMIZAR LOS RESULTADOS, DE ACUERDO CON LA VERIFICACIÓN.'],
                ]
            ],
        ];
    }
}

