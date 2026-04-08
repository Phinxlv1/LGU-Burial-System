# LGU Burial System: Technical Blueprint & Architecture

This document serves as the "Source of Truth" for the LGU Burial System (Carmen, Davao del Norte). It is designed to be ingested by an AI or human developer to understand the entire system's structure, logic, and nuances.

---

## 🏗️ 1. System Identity & Stack
- **Project Name**: LGU Burial System
- **Organization**: Municipal Civil Registrar, LGU Carmen
- **Core Strategy**: A digital transformation from manual ledger-based burial tracking to a unified Geographic Information System (GIS).
- **Tech Stack**:
    - **Backend**: Laravel 11.x (PHP 8.2+)
    - **Database**: PostgreSQL/MySQL (Production) / SQLite (Local/Dev)
    - **Frontend**: React 19, Tailwind CSS 4, Framer Motion
    - **GIS/Mapping**: MapLibre GL, Lucide Icons
    - **Reporting**: DomPDF (PDFs), Maatwebsite/Excel (XLSX)

---

## 🗄️ 2. Domain Models & Data Schema

### `DeceasedPerson`
The base identity record.
- **Keys**: `last_name`, `first_name`, `middle_name`, `date_of_death`, `cause_of_death`, `age_at_death`, `sex`.
- **Logic**: Used as the primary lookup for all permits. Scanned for "swapped names" (short last, long first) via Data Quality logic.

### `BurialPermit`
The life-cycle engine of the system.
- **Statuses**: 
    - `active`: Valid permit.
    - `expiring`: Transitions to this state based on `expiry_warning_days` (default ~30 days).
    - `expired`: Read-only/Requires renewal.
- **Categorization**: `cemented`, `niche_1st_floor`, `niche_2nd_floor`, `niche_3rd_floor`, `niche_4th_floor`, `bone_niches`.
- **Renewal**: Permits can be renewed, incrementing `renewal_count` and extending `expiry_date` based on `permit_expiry_years` from settings.

### `CemeteryPlot` & `CemeteryGrid`
The physical manifestation.
- **Geomap**: Uses Long/Lat or local coordinates for physical spots.
- **Niche Grid**: Represents vertical structures (Apartment-style niches). Handles 4-floor layouts.

---

## 🧠 3. Core Business Logic (The "Callouts")

> [!IMPORTANT]
> **Permit Status Engine**: The system does NOT rely on manual status updates. It calculates `expiring` and `expired` based on the current date vs `expiry_date`. Settings provide the "Warning Threshold" (days).

> [!NOTE]
> **Data Quality Scanner (11 Point Scan)**:
> 1. Duplicate Permits (Same Deceased).
> 2. Duplicate Permit Numbers.
> 3. Missing Deceased Links.
> 4. Missing Date of Death.
> 5. Placeholder Applicant Names ("Unknown").
> 6. Active Permits missing Expiry Dates.
> 7. Swapped Names (First ↔ Last).
> 8. Invalid Permit Types.
> 9. Invalid Ages (<= 0).
> 10. Future Dates of Death.
> 11. Identical Full Name matches.

> [!TIP]
> **Settings Storage**: Unlike most Laravel apps, configuration (Fees, Municipal Names, Registrar Info) is stored in `storage/app/settings.json` for rapid, database-free retrieval.

---

## 🎨 4. Frontend & UX Patterns
- **SuperAdmin Dashboard**: A "Glassmorphic" command center featuring real-time stats (Chart.js), activity feeds, and quick exports.
- **Geomap Analytics**: A React-driven `main.tsx` engine that mounts on `/superadmin/geomap`. It visualizes occupancy levels in real-time.
- **Activity Log**: Every `created`, `updated`, `renewed`, and `deleted` action is recorded with "Old vs New" value comparisons for audit trails.

---

## 📡 5. Integrations & Utility
- **SMS System**: Managed by `SmsController`. Used to notify applicants about permit approvals or upcoming expirations.
- **Importer**: Supports bulk Excel imports. Validates data quality during ingestion and records logs in `import_logs`.
- **PDF Engine**: Generates "Premium" reports with municipal branding, including barcoded signatures and official headers.

---

## 📂 6. Folder Architecture Map
- `app/Http/Controllers`: All logic for Mapping, Permits, and Data Quality.
- `app/Models`: Heavy use of Eloquent relations (`BurialPermit -> DeceasedPerson`).
- `database/migrations`: Version-controlled schema including recent Niche Grid geometry additions.
- `resources/js/geomap`: The entire React GIS codebase.
- `resources/views/superadmin`: Custom Blade architecture using `partials.sidebar` and `partials.navbar`.

---

## 💡 7. AI Context (The "Brain" Prompt)
*If you are an AI working on this system, prioritize:*
1. **Consistency**: Ensure permit statuses follow the `active -> expiring -> expired` flow.
2. **Data Integrity**: Always use the `ActivityLog::record()` method when modifying data.
3. **Roles**: Never expose `settings` or `users` roles to the `admin` role; these are `super_admin` only.
4. **Geomap**: Any changes to maps must consider the React component boundaries in `resources/js/geomap`.
5. **Renewal Flow**: Renewing a permit resets its status to `active` and generates a new `expiry_date`.
