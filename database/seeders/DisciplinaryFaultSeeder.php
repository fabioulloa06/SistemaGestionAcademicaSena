<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DisciplinaryFaultSeeder extends Seeder
{
    public function run(): void
    {
        $faults = [
            [
                'codigo' => 'Literal 1',
                'description' => 'Aportar documentos o registrar información en los sistemas de información del SENA, que difiera de la real, para el ingreso, formación, certificación o titulación; o para obtener cualquier beneficio de esta.'
            ],
            [
                'codigo' => 'Literal 2',
                'description' => 'Suplantar identidad en cualquier trámite académico o administrativo del SENA.'
            ],
            [
                'codigo' => 'Literal 3',
                'description' => 'Alterar, adulterar, falsificar o sustraer documentos públicos o privados emitidos por el SENA o en custodia del SENA.'
            ],
            [
                'codigo' => 'Literal 4',
                'description' => 'Plagiar, materiales, trabajos y demás documentos generados en los grupos de trabajo o producto del trabajo en equipo institucional, así como en actividades evaluativas del proceso formativo o en concursos, juegos o competencias de cualquier carácter.'
            ],
            [
                'codigo' => 'Literal 5',
                'description' => 'Utilizar el internet y demás tecnologías de información y comunicación dispuestas por el SENA en o para el desarrollo de su proceso formativo, con la finalidad de acceder, generar, transmitir, publicar o enviar información confidencial, de circulación restringida, inadecuada, malintencionada, violenta, ilegal, peligrosa, pornográfica, insultos o agresiones por los medios de comunicación físicos o electrónicos, o cualquier actividad que pueda causar daños al nombre, honra o derechos ajenos.'
            ],
            [
                'codigo' => 'Literal 6',
                'description' => 'Ingerir, ingresar, comercializar, promocionar o suministrar bebidas alcohólicas o sustancias psicoactivas, dentro de las instalaciones físicas y virtuales del SENA o en los ambientes formativos SENA, o ingresar a la entidad en estado que indique alteraciones de conducta ocasionadas por el consumo de estas o bajo su efecto.'
            ],
            [
                'codigo' => 'Literal 7',
                'description' => 'Ingresar o portar cualquier tipo de armas, objetos cortopunzantes, explosivos u otros artefactos que representen riesgo o puedan ser empleados para atentar contra la vida o la integridad física de las personas. Los miembros de la fuerza pública y organismos de seguridad del Estado que se encuentren en un proceso de aprendizaje, no podrán portar armas en el Centro de Formación.'
            ],
            [
                'codigo' => 'Literal 8',
                'description' => 'Utilizar el nombre del SENA, las instalaciones físicas y virtuales, el internet y las tecnologías de información y comunicación del SENA dispuestas para el desarrollo de su proceso de formación, para actividades particulares con o sin ánimo de lucro y que no estén relacionadas con su proceso formativo.'
            ],
            [
                'codigo' => 'Literal 9',
                'description' => 'Cometer, ser cómplice o copartícipe de delitos contra la comunidad educativa o contra la Institución.'
            ],
            [
                'codigo' => 'Literal 10',
                'description' => 'Destruir, sustraer, dañar total o parcialmente instalaciones físicas o virtuales, equipos, materiales, software, elementos y demás bienes o dotación en general del SENA o de instituciones, empresas u otras entidades donde el aprendiz represente la entidad o se desarrollen actividades de aprendizaje, culturales, recreativas, deportivas y sociales, intercambios estudiantiles nacionales o internacionales.'
            ],
            [
                'codigo' => 'Literal 11',
                'description' => 'Realizar acciones proselitistas de carácter político o religioso dentro de las instalaciones físicas y virtuales del SENA y demás ambientes donde se desarrollen actividades formativas.'
            ],
            [
                'codigo' => 'Literal 12',
                'description' => 'Ingresar o salir de cualquier instalación del Centro de Formación o de la entidad donde se desarrolle la formación, por sitios diferentes a la portería, saltando muros, cercas o violentando puertas, ventanas y cerraduras.'
            ],
            [
                'codigo' => 'Literal 13',
                'description' => 'Dibujar o escribir sobre cualquier objeto o mueble de las instalaciones físicas y virtuales donde se desarrollan programas de formación; o pegar avisos, carteles, pancartas o análogos en sitios no autorizados. Los lugares autorizados no podrán ser utilizados para publicar mensajes que se constituyan en hostigamiento, acoso de cualquier tipo, (bullying, mobbing) dirigido a integrantes de la comunidad educativa.'
            ],
            [
                'codigo' => 'Literal 14',
                'description' => 'Discriminar cualquier miembro de la comunidad SENA por condiciones como: sexo, nacionalidad, origen étnico, lengua, religión, identidad de género, orientación sexual, religión, situación económica, discapacidad, o preferencias políticas.'
            ],
        ];

        foreach ($faults as $fault) {
            DB::table('disciplinary_faults')->insert([
                'codigo' => $fault['codigo'],
                'description' => $fault['description'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
