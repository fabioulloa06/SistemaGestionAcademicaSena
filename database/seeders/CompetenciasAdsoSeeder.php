<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Program;
use App\Models\Competencia;
use App\Models\LearningOutcome;

class CompetenciasAdsoSeeder extends Seeder
{
    public function run(): void
    {
        // Buscar el programa ADSO
        $program = Program::where('nombre', 'LIKE', '%Análisis y Desarrollo de Software%')->first();
        
        if (!$program) {
            if ($this->command) {
                $this->command->error('No se encontró el programa de Análisis y Desarrollo de Software');
            }
            return;
        }

        // Eliminar competencias existentes del programa ADSO para evitar duplicados
        if ($this->command) {
            $this->command->warn('Eliminando competencias existentes del programa ADSO...');
        }
        
        $existingCompetencias = Competencia::where('program_id', $program->id)->get();
        foreach ($existingCompetencias as $comp) {
            // Eliminar primero los resultados de aprendizaje
            LearningOutcome::where('competencia_id', $comp->id)->delete();
            // Luego eliminar la competencia
            $comp->delete();
        }
        
        if ($this->command) {
            $this->command->info('Competencias anteriores eliminadas. Creando nuevas competencias...');
        }

        $competencias = [
            [
                'codigo' => 'COMP-01',
                'nombre' => 'Controlar la calidad del servicio de software de acuerdo con los estándares técnicos',
                'resultados' => [
                    'INCORPORAR ACTIVIDADES DE ASEGURAMIENTO DE LA CALIDAD DEL SOFTWARE DE ACUERDO CON ESTÁNDARES DE LA INDUSTRIA.',
                    'VERIFICAR LA CALIDAD DEL SOFTWARE DE ACUERDO CON LAS PRÁCTICAS ASOCIADAS EN LOS PROCESOS DE DESARROLLO.',
                    'REALIZAR ACTIVIDADES DE MEJORA DE LA CALIDAD DEL SOFTWARE A PARTIR DE LOS RESULTADOS DE LA VERIFICACIÓN.',
                ]
            ],
            [
                'codigo' => 'COMP-02',
                'nombre' => 'Establecer requisitos de la solución de software de acuerdo con estándares y procedimiento técnico',
                'resultados' => [
                    'CARACTERIZAR LOS PROCESOS DE LA ORGANIZACIÓN DE ACUERDO CON EL SOFTWARE A CONSTRUIR.',
                    'RECOLECTAR INFORMACIÓN DEL SOFTWARE A CONSTRUIR DE ACUERDO CON LAS NECESIDADES DEL CLIENTE.',
                    'ESTABLECER LOS REQUISITOS DEL SOFTWARE DE ACUERDO CON LA INFORMACIÓN RECOLECTADA.',
                    'VALIDAR EL INFORME DE REQUISITOS DE ACUERDO CON LAS NECESIDADES DEL CLIENTE.',
                ]
            ],
            [
                'codigo' => 'COMP-03',
                'nombre' => 'Desarrollar la solución de software de acuerdo con el diseño y metodologías de desarrollo',
                'resultados' => [
                    'PLANEAR ACTIVIDADES DE CONSTRUCCIÓN DEL SOFTWARE DE ACUERDO CON EL DISEÑO ESTABLECIDO.',
                    'CONSTRUIR LA BASE DE DATOS PARA EL SOFTWARE A PARTIR DEL MODELO DE DATOS.',
                    'CREAR COMPONENTES FRONT-END DEL SOFTWARE DE ACUERDO CON EL DISEÑO.',
                    'CODIFICAR EL SOFTWARE DE ACUERDO CON EL DISEÑO ESTABLECIDO.',
                    'REALIZAR PRUEBAS AL SOFTWARE PARA VERIFICAR SU FUNCIONALIDAD.',
                ]
            ],
            [
                'codigo' => 'COMP-04',
                'nombre' => 'Diseñar la solución de software de acuerdo con procedimientos y requisitos técnicos',
                'resultados' => [
                    'ELABORAR LOS ARTEFACTOS DE DISEÑO DEL SOFTWARE SIGUIENDO LAS PRÁCTICAS DE LA METODOLOGÍA SELECCIONADA.',
                    'ESTRUCTURAR EL MODELO DE DATOS DEL SOFTWARE DE ACUERDO CON LAS ESPECIFICACIONES DEL ANÁLISIS.',
                    'DETERMINAR LAS CARACTERÍSTICAS TÉCNICAS DE LA INTERFAZ GRÁFICA DEL SOFTWARE ADOPTANDO ESTÁNDARES.',
                    'VERIFICAR LOS ENTREGABLES DE LA FASE DE DISEÑO DEL SOFTWARE DE ACUERDO CON LO ESTABLECIDO EN EL INFORME DE ANÁLISIS.',
                ]
            ],
            [
                'codigo' => 'COMP-05',
                'nombre' => 'Estructurar propuesta técnica de servicio de tecnología de la información según requisitos técnicos y normativa',
                'resultados' => [
                    'DEFINIR ESPECIFICACIONES TÉCNICAS DEL SOFTWARE DE ACUERDO CON LAS CARACTERÍSTICAS DEL SOFTWARE A CONSTRUIR.',
                    'ELABORAR PROPUESTA TÉCNICA DEL SOFTWARE DE ACUERDO CON LAS ESPECIFICACIONES TÉCNICAS DEFINIDAS.',
                    'VALIDAR LAS CONDICIONES DE LA PROPUESTA TÉCNICA DEL SOFTWARE DE ACUERDO CON LOS INTERESES DE LAS PARTES.',
                ]
            ],
            [
                'codigo' => 'COMP-06',
                'nombre' => 'Evaluar requisitos de la solución de software de acuerdo con metodologías de análisis y estándares',
                'resultados' => [
                    'PLANEAR ACTIVIDADES DE ANÁLISIS DE ACUERDO CON LA METODOLOGÍA SELECCIONADA.',
                    'MODELAR LAS FUNCIONES DEL SOFTWARE DE ACUERDO CON EL INFORME DE REQUISITOS.',
                    'DESARROLLAR PROCESOS LÓGICOS A TRAVÉS DE LA IMPLEMENTACIÓN DE ALGORITMOS.',
                    'VERIFICAR LOS MODELOS REALIZADOS EN LA FASE DE ANÁLISIS DE ACUERDO CON LO ESTABLECIDO EN EL INFORME DE REQUISITOS.',
                ]
            ],
            [
                'codigo' => 'COMP-07',
                'nombre' => 'Implementar la solución de software de acuerdo con los requisitos de operación y modelos de referencia',
                'resultados' => [
                    'PLANEAR ACTIVIDADES DE IMPLANTACIÓN DEL SOFTWARE DE ACUERDO CON LAS CONDICIONES DEL SISTEMA.',
                    'DESPLEGAR EL SOFTWARE DE ACUERDO CON LA ARQUITECTURA Y LAS POLÍTICAS ESTABLECIDAS.',
                    'DOCUMENTAR EL PROCESO DE IMPLANTACIÓN DE SOFTWARE SIGUIENDO ESTÁNDARES DE CALIDAD.',
                    'IMPLANTAR EL SOFTWARE DE ACUERDO CON LOS NIVELES DE SERVICIO ESTABLECIDOS CON EL CLIENTE.',
                ]
            ],
            [
                'codigo' => 'COMP-08',
                'nombre' => 'Utilizar herramientas informáticas de acuerdo con las necesidades de manejo de información',
                'resultados' => [
                    'ALISTAR HERRAMIENTAS DE TECNOLOGÍAS DE LA INFORMACIÓN Y LA COMUNICACIÓN (TIC), DE ACUERDO CON LAS NECESIDADES DE PROCESAMIENTO DE INFORMACIÓN Y COMUNICACIÓN.',
                    'APLICAR FUNCIONALIDADES DE HERRAMIENTAS Y SERVICIOS TIC, DE ACUERDO CON MANUALES DE USO, PROCEDIMIENTOS ESTABLECIDOS Y BUENAS PRÁCTICAS.',
                    'EVALUAR LOS RESULTADOS, DE ACUERDO CON LOS REQUERIMIENTOS.',
                    'OPTIMIZAR LOS RESULTADOS, DE ACUERDO CON LA VERIFICACIÓN.',
                ]
            ],
            [
                'codigo' => 'COMP-09',
                'nombre' => 'Aplicación de conocimientos de las ciencias naturales de acuerdo con situaciones del contexto productivo y social',
                'resultados' => [
                    'IDENTIFICAR LOS PRINCIPIOS Y LEYES DE LA FÍSICA EN LA SOLUCIÓN DE PROBLEMAS DE ACUERDO AL CONTEXTO PRODUCTIVO.',
                    'SOLUCIONAR PROBLEMAS ASOCIADOS CON EL SECTOR PRODUCTIVO CON BASE EN LOS PRINCIPIOS Y LEYES DE LA FÍSICA.',
                    'VERIFICAR LAS TRANSFORMACIONES FÍSICAS DE LA MATERIA UTILIZANDO HERRAMIENTAS TECNOLÓGICAS.',
                    'PROPONER ACCIONES DE MEJORA EN LOS PROCESOS PRODUCTIVOS DE ACUERDO CON LOS PRINCIPIOS Y LEYES DE LA FÍSICA.',
                ]
            ],
            [
                'codigo' => 'COMP-10',
                'nombre' => 'Aplicar prácticas de protección ambiental, seguridad y salud en el trabajo de acuerdo con las políticas organizacionales y la normatividad vigente',
                'resultados' => [
                    'ANALIZAR LAS ESTRATEGIAS PARA LA PREVENCIÓN Y CONTROL DE LOS IMPACTOS AMBIENTALES Y DE LOS ACCIDENTES Y ENFERMEDADES LABORALES (ATEL) DE ACUERDO CON LAS POLÍTICAS ORGANIZACIONALES Y EL ENTORNO SOCIAL.',
                    'IMPLEMENTAR ESTRATEGIAS PARA EL CONTROL DE LOS IMPACTOS AMBIENTALES Y DE LOS ACCIDENTES Y ENFERMEDADES DE ACUERDO CON LOS PLANES Y PROGRAMAS ESTABLECIDOS POR LA ORGANIZACIÓN.',
                    'REALIZAR SEGUIMIENTO Y ACOMPAÑAMIENTO AL DESARROLLO DE LOS PLANES Y PROGRAMAS AMBIENTALES Y SST, SEGÚN EL ÁREA DE DESEMPEÑO.',
                    'PROPONER ACCIONES DE MEJORA PARA EL MANEJO AMBIENTAL Y EL CONTROL DE LA SST, DE ACUERDO CON ESTRATEGIAS DE TRABAJO, COLABORATIVO, COOPERATIVO Y COORDINADO EN EL CONTEXTO PRODUCTIVO Y SOCIAL.',
                ]
            ],
            [
                'codigo' => 'COMP-11',
                'nombre' => 'Razonar cuantitativamente frente a situaciones susceptibles de ser abordadas de manera matemática en contextos laborales, sociales y personales',
                'resultados' => [
                    'IDENTIFICAR MODELOS MATEMÁTICOS DE ACUERDO CON LOS REQUERIMIENTOS DEL PROBLEMA PLANTEADO EN CONTEXTOS SOCIALES Y PRODUCTIVO.',
                    'PLANTEAR PROBLEMAS MATEMÁTICOS A PARTIR DE SITUACIONES GENERADAS EN EL CONTEXTO SOCIAL Y PRODUCTIVO.',
                    'RESOLVER PROBLEMAS MATEMÁTICOS A PARTIR DE SITUACIONES GENERADAS EN EL CONTEXTO SOCIAL Y PRODUCTIVO.',
                    'PROPONER ACCIONES DE MEJORA FRENTE A LOS RESULTADOS DE LOS PROCEDIMIENTOS MATEMÁTICOS DE ACUERDO CON EL PROBLEMA PLANTEADO.',
                ]
            ],
            [
                'codigo' => 'COMP-12',
                'nombre' => 'Desarrollar procesos de comunicación eficaces y efectivos, teniendo en cuenta situaciones de orden social, personal y productivo',
                'resultados' => [
                    'ANALIZAR LOS COMPONENTES DE LA COMUNICACIÓN SEGÚN SUS CARACTERÍSTICAS, INTENCIONALIDAD Y CONTEXTO.',
                    'ARGUMENTAR EN FORMA ORAL Y ESCRITA ATENDIENDO LAS EXIGENCIAS Y PARTICULARIDADES DE LAS DIVERSAS SITUACIONES COMUNICATIVAS MEDIANTE LOS DISTINTOS SISTEMAS DE REPRESENTACIÓN.',
                    'RELACIONAR LOS PROCESOS COMUNICATIVOS TENIENDO EN CUENTA CRITERIOS DE LÓGICA Y RACIONALIDAD.',
                    'ESTABLECER PROCESOS DE ENRIQUECIMIENTO LEXICAL Y ACCIONES DE MEJORAMIENTO EN EL DESARROLLO DE PROCESOS COMUNICATIVOS SEGÚN REQUERIMIENTOS DEL CONTEXTO.',
                ]
            ],
            [
                'codigo' => 'COMP-13',
                'nombre' => 'Interactuar en el contexto productivo y social de acuerdo con principios éticos para la construcción de una cultura de paz',
                'resultados' => [
                    'PROMOVER MI DIGNIDAD Y LA DEL OTRO A PARTIR DE LOS PRINCIPIOS Y VALORES ÉTICOS COMO APORTE EN LA INSTAURACIÓN DE UNA CULTURA DE PAZ.',
                    'ESTABLECER RELACIONES DE CRECIMIENTO PERSONAL Y COMUNITARIO A PARTIR DEL BIEN COMÚN COMO APORTE PARA EL DESARROLLO SOCIAL.',
                    'PROMOVER EL USO RACIONAL DE LOS RECURSOS NATURALES A PARTIR DE CRITERIOS DE SOSTENIBILIDAD Y SUSTENTABILIDAD ÉTICA Y NORMATIVA VIGENTE.',
                    'CONTRIBUIR CON EL FORTALECIMIENTO DE LA CULTURA DE PAZ A PARTIR DE LA DIGNIDAD HUMANA Y LAS ESTRATEGIAS PARA LA TRANSFORMACIÓN DE CONFLICTOS.',
                ]
            ],
            [
                'codigo' => 'COMP-14',
                'nombre' => 'Generar hábitos saludables de vida mediante la aplicación de programas de actividad física en los contextos productivos y sociales',
                'resultados' => [
                    'DESARROLLAR HABILIDADES PSICOMOTRICES EN EL CONTEXTO PRODUCTIVO Y SOCIAL.',
                    'PRACTICAR HÁBITOS SALUDABLES MEDIANTE LA APLICACIÓN DE FUNDAMENTOS DE NUTRICIÓN E HIGIENE.',
                    'EJECUTAR ACTIVIDADES DE ACONDICIONAMIENTO FÍSICO ORIENTADAS HACIA EL MEJORAMIENTO DE LA CONDICIÓN FÍSICA EN LOS CONTEXTOS PRODUCTIVO Y SOCIAL.',
                    'IMPLEMENTAR UN PLAN DE ERGONOMÍA Y PAUSAS ACTIVAS SEGÚN LAS CARACTERÍSTICAS DE LA FUNCIÓN PRODUCTIVA.',
                ]
            ],
            [
                'codigo' => 'COMP-15',
                'nombre' => 'Gestionar procesos propios de la cultura emprendedora y empresarial de acuerdo con el perfil personal y los requerimientos de los contextos productivo y social',
                'resultados' => [
                    'INTEGRAR ELEMENTOS DE LA CULTURA EMPRENDEDORA TENIENDO EN CUENTA EL PERFIL PERSONAL Y EL CONTEXTO DE DESARROLLO SOCIAL.',
                    'CARACTERIZAR LA IDEA DE NEGOCIO TENIENDO EN CUENTA LAS OPORTUNIDADES Y NECESIDADES DEL SECTOR PRODUCTIVO Y SOCIAL.',
                    'ESTRUCTURAR EL PLAN DE NEGOCIO DE ACUERDO CON LAS CARACTERÍSTICAS EMPRESARIALES Y TENDENCIAS DE MERCADO.',
                    'VALORAR LA PROPUESTA DE NEGOCIO CONFORME CON SU ESTRUCTURA Y NECESIDADES DEL SECTOR PRODUCTIVO Y SOCIAL.',
                ]
            ],
            [
                'codigo' => 'COMP-16',
                'nombre' => 'Interactuar en lengua inglesa de forma oral y escrita dentro de contextos sociales y laborales según los criterios establecidos por el Marco Común Europeo de Referencia para las Lenguas',
                'resultados' => [
                    'COMPRENDER INFORMACIÓN SOBRE SITUACIONES COTIDIANAS Y LABORALES ACTUALES Y FUTURAS A TRAVÉS DE INTERACCIONES SOCIALES DE FORMA ORAL Y ESCRITA.',
                    'INTERCAMBIAR OPINIONES SOBRE SITUACIONES COTIDIANAS Y LABORALES ACTUALES, PASADAS Y FUTURAS EN CONTEXTOS SOCIALES ORALES Y ESCRITOS.',
                    'DISCUTIR SOBRE POSIBLES SOLUCIONES A PROBLEMAS DENTRO DE UN RANGO VARIADO DE CONTEXTOS SOCIALES Y LABORALES.',
                    'IMPLEMENTAR ACCIONES DE MEJORA RELACIONADAS CON EL USO DE EXPRESIONES, ESTRUCTURAS Y DESEMPEÑO SEGÚN LOS RESULTADOS DE APRENDIZAJE FORMULADOS PARA EL PROGRAMA.',
                    'PRESENTAR UN PROCESO PARA LA REALIZACIÓN DE UNA ACTIVIDAD EN SU QUEHACER LABORAL DE ACUERDO CON LOS PROCEDIMIENTOS ESTABLECIDOS DESDE SU PROGRAMA DE FORMACIÓN.',
                    'EXPLICAR LAS FUNCIONES DE SU OCUPACIÓN LABORAL USANDO EXPRESIONES DE ACUERDO AL NIVEL REQUERIDO POR EL PROGRAMA DE FORMACIÓN.',
                ]
            ],
            [
                'codigo' => 'COMP-17',
                'nombre' => 'Orientar investigación formativa según referentes técnicos',
                'resultados' => [
                    'ANALIZAR EL CONTEXTO PRODUCTIVO SEGÚN SUS CARACTERÍSTICAS Y NECESIDADES.',
                    'ESTRUCTURAR EL PROYECTO DE ACUERDO A CRITERIOS DE LA INVESTIGACIÓN.',
                    'ARGUMENTAR ASPECTOS TEÓRICOS DEL PROYECTO SEGÚN REFERENTES NACIONALES E INTERNACIONALES.',
                    'PROPONER SOLUCIONES A LAS NECESIDADES DEL CONTEXTO SEGÚN RESULTADOS DE LA INVESTIGACIÓN.',
                ]
            ],
            [
                'codigo' => 'COMP-18',
                'nombre' => 'Ejercer derechos fundamentales del trabajo en el marco de la constitución política y los convenios internacionales',
                'resultados' => [
                    'Reconocer el trabajo como factor de movilidad social y transformación vital con referencia a la fenomenología y a los derechos fundamentales en el trabajo.',
                    'Valorar la importancia de la ciudadanía laboral con base en el estudio de los derechos humanos y fundamentales en el trabajo.',
                    'Practicar los derechos fundamentales en el trabajo de acuerdo con la Constitución Política y los Convenios Internacionales.',
                    'Participar en acciones solidarias teniendo en cuenta el ejercicio de los derechos humanos, de los pueblos y de la naturaleza.',
                ]
            ],
            [
                'codigo' => 'COMP-19',
                'nombre' => 'Resultado de Aprendizaje de la Inducción',
                'resultados' => [
                    'IDENTIFICAR LA DINÁMICA ORGANIZACIONAL DEL SENA Y EL ROL DE LA FORMACIÓN PROFESIONAL INTEGRAL DE ACUERDO CON SU PROYECTO DE VIDA Y EL DESARROLLO PROFESIONAL.',
                ]
            ],
            [
                'codigo' => 'COMP-20',
                'nombre' => 'Resultados de Aprendizaje Etapa Práctica',
                'resultados' => [
                    'APLICAR EN LA RESOLUCIÓN DE PROBLEMAS REALES DEL SECTOR PRODUCTIVO, LOS CONOCIMIENTOS, HABILIDADES Y DESTREZAS PERTINENTES A LAS COMPETENCIAS DEL PROGRAMA DE FORMACIÓN ASUMIENDO ESTRATEGIAS Y METODOLOGÍAS DE AUTOGESTIÓN.',
                ]
            ],
        ];

        foreach ($competencias as $index => $compData) {
            if ($this->command) {
                $this->command->info("Creando competencia {$compData['codigo']}: {$compData['nombre']}");
            }
            
            $competencia = Competencia::create([
                'program_id' => $program->id,
                'codigo' => $compData['codigo'],
                'nombre' => $compData['nombre'],
                'activo' => true,
            ]);

            foreach ($compData['resultados'] as $raIndex => $raDescripcion) {
                $raNumero = str_pad($raIndex + 1, 2, '0', STR_PAD_LEFT);
                
                LearningOutcome::create([
                    'competencia_id' => $competencia->id,
                    'codigo' => "{$compData['codigo']}-RA{$raNumero}",
                    'nombre' => $raDescripcion,
                    'descripcion' => null,
                ]);
            }
            
            if ($this->command) {
                $this->command->info("  ✓ Creados " . count($compData['resultados']) . " resultados de aprendizaje");
            }
        }

        if ($this->command) {
            $this->command->info("\n✅ Seeder completado: " . count($competencias) . " competencias creadas para el programa ADSO");
        }
    }
}
