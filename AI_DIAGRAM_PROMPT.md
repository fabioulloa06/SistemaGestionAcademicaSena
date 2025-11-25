# AI Prompt for Database Diagrams

Use the following prompt to generate Entity-Relationship Diagrams (ERD) and database visualizations using an AI tool (like ChatGPT, Claude, or a specialized diagram generator).

---

**Prompt:**

I have a Laravel application for an Academic Management System (SENA). The database has the following key tables and relationships. Please generate a **Mermaid.js** class diagram (ERD) representing this schema.

**Tables:**
1.  **Users & Roles**: `users` (id, name, email, role, password), `students`, `instructors`.
2.  **Academic Structure**: `programs` (id, name, code), `groups` (id, program_id, name), `competencias` (id, program_id, name), `learning_outcomes` (id, competencia_id, name).
3.  **Enrollment**: `matriculas` (student_id, group_id).
4.  **Assignments**: `competencia_group_instructor` (instructor_id, group_id, competencia_id).
5.  **Attendance**: `attendance_lists` (group_id, instructor_id, date), `asistencias` (student_id, session_id, status).
6.  **Grading & Evidence**: `actividades_aprendizaje`, `entregas_evidencias` (student_id, activity_id, file), `calificaciones_evidencias` (submission_id, grade).
7.  **Disciplinary**: `disciplinary_actions` (student_id, fault_type, description), `improvement_plans`.

**Relationships:**
- A **Program** has many **Groups** and **Competencies**.
- A **Competency** has many **Learning Outcomes**.
- A **Group** has many **Students**.
- An **Instructor** is assigned to **Groups** for specific **Competencies**.
- **Students** submit **Evidence** for **Activities**.
- **Attendance** is recorded for **Students** in **Groups**.

**Output Format:**
Please provide the Mermaid code inside a \`\`\`mermaid block. Group related tables into subgraphs (e.g., "Academic", "Users", "Grading").

---
