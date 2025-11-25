# Database Schema Documentation

This document outlines the database structure for the **Sistema de Gestión Académica SENA**.

## Tables Overview

### Core System
- **users**: Stores user information (admin, coordinators, instructors, students).
- **password_reset_tokens**: Tokens for password recovery.
- **sessions**: User session data.
- **cache**: Application cache.
- **jobs**: Queued jobs.
- **notifications**: System notifications.
- **audit_logs** / **auditoria**: Audit trails for system actions.

### Academic Structure
- **centros_formacion**: Training centers.
- **programas_formacion** / **programs**: Academic programs.
- **fichas** / **groups**: Class groups (Fichas).
- **competencias**: Competencies associated with programs.
- **resultados_aprendizaje** / **learning_outcomes**: Learning outcomes for competencies.
- **matriculas**: Student enrollments in groups.
- **sesiones_formacion**: Scheduled training sessions.

### Users & Roles
- **instructors**: Instructor details.
- **students**: Student details (linked to users).
- **competencia_instructor**: Assignment of competencies to instructors.
- **competencia_group_instructor**: Assignment of instructors to specific groups and competencies.

### Academic Process
- **asistencias** / **attendance_lists**: Attendance records.
- **control_inasistencias**: Management of absences.
- **actividades_aprendizaje**: Learning activities.
- **entregas_evidencias**: Evidence submissions by students.
- **calificaciones_evidencias**: Grades for evidence.
- **evaluaciones_ra**: Evaluations of learning outcomes.
- **comentarios_evidencias**: Feedback on evidence.

### Disciplinary
- **disciplinary_actions** / **llamados_atencion**: Disciplinary actions.
- **disciplinary_faults** / **tipos_falta**: Types of faults.
- **descargos**: Student defense/explanations.
- **sanciones**: Sanctions applied.
- **planes_mejoramiento** / **improvement_plans**: Improvement plans for students.

## Key Relationships

- **Users** have one **Role** (Admin, Coordinator, Instructor, Student).
- **Students** belong to a **Group (Ficha)** and are enrolled in a **Program**.
- **Instructors** are assigned to **Competencies** and **Groups**.
- **Attendance** is tracked per **Session** or **Group**.
- **Grades** are linked to **Evidence** and **Learning Outcomes**.

> [!NOTE]
> The database contains some overlapping tables due to bilingual naming conventions (e.g., `programs` vs `programas_formacion`). The application logic primarily uses the English naming in newer migrations, but legacy tables may exist.
