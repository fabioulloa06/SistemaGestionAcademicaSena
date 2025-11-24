# PROYECTO DE INVESTIGACIÓN: SISTEMA DE GESTIÓN ACADÉMICA SENA

## 1. TÍTULO
Desarrollo de un Sistema Web para la Optimización del Seguimiento Académico, Disciplinario y de Asistencia en Centros de Formación del SENA.

## 2. INTRODUCCIÓN
El presente proyecto de investigación aborda el diseño y desarrollo de una solución tecnológica orientada a modernizar la gestión académica en los centros de formación del Servicio Nacional de Aprendizaje (SENA). La investigación se centra en la problemática del manejo manual y fragmentado de la información relacionada con la asistencia, evaluación y procesos disciplinarios de los aprendices. A través de este sistema, se busca centralizar estos procesos en una plataforma web integral que permita a instructores, coordinadores y aprendices interactuar con datos en tiempo real, facilitando la toma de decisiones y mejorando las estrategias de retención de aprendices.

## 3. PLANTEAMIENTO DEL PROBLEMA Y JUSTIFICACIÓN
En la actualidad, muchos procesos de seguimiento en los centros de formación se realizan mediante formatos físicos o archivos de hojas de cálculo dispersos, lo que conlleva a la redundancia de datos, pérdida de información y tiempos de respuesta lentos ante situaciones críticas como la deserción escolar. La falta de un sistema unificado dificulta la detección temprana de aprendices en riesgo por inasistencia o bajo rendimiento, impidiendo la activación oportuna de las rutas de mejora establecidas en el Reglamento del Aprendiz.

Este proyecto es relevante y pertinente porque se alinea con la transformación digital del sector educativo, ofreciendo una herramienta que no solo digitaliza procesos operativos, sino que aporta valor estratégico al permitir la trazabilidad completa del historial del aprendiz. El impacto esperado es la reducción de la carga administrativa para los instructores y una disminución en los índices de deserción gracias a las alertas tempranas y la gestión eficiente de planes de mejoramiento.

## 4. OBJETIVOS

### Objetivo General
Desarrollar e implementar un sistema de información web para la gestión integral del seguimiento académico, disciplinario y de asistencia de los aprendices, que optimice los procesos administrativos y apoye las estrategias de retención en el centro de formación.

### Objetivos Específicos
1.  Diagnosticar los requerimientos funcionales y no funcionales necesarios para la digitalización de los formatos de asistencia y seguimiento disciplinario del SENA.
2.  Diseñar una arquitectura de software modular que integre los módulos de control de asistencia, evaluación por competencias y gestión de comités disciplinarios.
3.  Implementar un módulo de alertas tempranas que notifique automáticamente sobre inasistencias críticas y faltas disciplinarias según el reglamento institucional.
4.  Validar la funcionalidad y usabilidad del sistema mediante pruebas piloto con instructores y coordinadores académicos.

## 5. REFERENTE TEÓRICO
La investigación se fundamenta en los **Sistemas de Gestión de Información Educativa (EMIS)**, definidos como herramientas que sistematizan la recolección y procesamiento de datos para apoyar la gestión escolar. Se apoya en la **Teoría de la Retención Estudiantil**, que postula que la intervención temprana basada en datos de asistencia y rendimiento es crucial para prevenir la deserción.

Desde el punto de vista normativo, el proyecto se rige por el **Reglamento del Aprendiz SENA (Acuerdo 0007 de 2012)**, que establece los deberes, derechos y el debido proceso disciplinario, conceptos que se modelan en la lógica del software (faltas leves, graves, planes de mejoramiento).

Tecnológicamente, se sustenta en la **Arquitectura Modelo-Vista-Controlador (MVC)**, un patrón de diseño de software que separa la lógica de negocio de la interfaz de usuario, garantizando escalabilidad y mantenibilidad. Se utilizan tecnologías web modernas como PHP (Laravel) para el backend y bases de datos relacionales (MySQL) para garantizar la integridad de la información.

## 6. METODOLOGÍA
*   **Tipo de Investigación:** Investigación Aplicada y Tecnológica, ya que busca resolver un problema práctico mediante la creación de un artefacto software.
*   **Diseño de Investigación:** Se adoptó un enfoque mixto con predominancia cualitativa en la fase de levantamiento de requisitos y cuantitativa en la validación de métricas de rendimiento.
*   **Población y Muestra:** La población objetivo son los instructores y aprendices del centro de formación. La muestra para el piloto incluye a los coordinadores académicos y un grupo de control de instructores técnicos.
*   **Técnicas de Recolección de Datos:**
    *   **Análisis Documental:** Revisión de formatos Excel actuales y el Reglamento del Aprendiz.
    *   **Entrevista Semiestructurada:** Realizada a coordinadores para identificar cuellos de botella en el proceso disciplinario.
    *   **Observación Participante:** Seguimiento al proceso actual de toma de asistencia en ambientes de formación.

## 7. RESULTADOS
Como resultado principal de la investigación (en curso/terminada), se obtuvo un prototipo funcional de software web desplegado en un entorno local. Los resultados específicos incluyen:
1.  **Módulo de Asistencia:** Capacidad para registrar asistencia masiva y calcular automáticamente porcentajes de fallas, eliminando el cálculo manual.
2.  **Sistema de Alertas:** Implementación exitosa de notificaciones automáticas al detectar 3 inasistencias consecutivas o acumuladas, permitiendo una intervención inmediata.
3.  **Gestión Disciplinaria:** Digitalización del flujo de sanciones, permitiendo la generación automática de reportes y planes de mejoramiento, asegurando el cumplimiento del debido proceso.
4.  **Portal del Aprendiz:** Interfaz dedicada donde los estudiantes pueden consultar su estado en tiempo real, fomentando la autogestión y transparencia.

## 8. CONCLUSIONES
La implementación del sistema de gestión académica demuestra que la automatización de procesos rutinarios como el control de asistencia impacta positivamente en la labor docente, liberando tiempo para actividades pedagógicas. Se concluye que la centralización de la información disciplinaria y académica en una única plataforma mejora significativamente la trazabilidad de los casos de deserción, permitiendo a la coordinación académica actuar con base en evidencias concretas y oportunas. Además, la inclusión de los aprendices en el ecosistema digital a través de su propio portal fortalece la comunicación institucional y la transparencia de los procesos evaluativos.

## 9. BIBLIOGRAFÍA

1.  Servicio Nacional de Aprendizaje (SENA). (2012). *Acuerdo 0007 de 2012: Por el cual se adopta el reglamento del aprendiz SENA*. Diario Oficial.
2.  Vahos, L. E., & Muñoz, L. (2019). *Estrategias de retención estudiantil en la educación superior: Una revisión sistemática*. Revista Educación y Desarrollo Social, 13(1), 45-62.
3.  Pressman, R. S., & Maxim, B. R. (2020). *Software Engineering: A Practitioner's Approach* (9th ed.). McGraw-Hill Education.
4.  UNESCO. (2018). *Re-orienting Education Management Information Systems (EMIS) towards inclusive and equitable quality education and lifelong learning*. UNESCO Digital Library.
5.  Otter, T., & Seipel, P. (2021). *Laravel: Up & Running: A Framework for Building Modern PHP Apps* (2nd ed.). O'Reilly Media.
