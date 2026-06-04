# QA Checklist - Admin & Client (QuickEvents)

Date: \_**\_ / \_\_** / **\_\_**
Testeur: ********\_\_********
Environnement: local / staging / prod
Build/Commit: ********\_\_********

## Legend

- [ ] Not run
- [x] Pass
- [!] Fail

## 1) Public Navigation

| ID     | Scenario         | Steps                                                                 | Expected Result                  | Status | Notes |
| ------ | ---------------- | --------------------------------------------------------------------- | -------------------------------- | ------ | ----- |
| PUB-01 | Home loads       | Open `/`                                                              | Page loads without 500/timeout   | [ ]    |       |
| PUB-02 | Catalogues load  | Open `/catalogues`                                                    | List is visible and clickable    | [ ]    |       |
| PUB-03 | Event pages load | Open `/mariage`, `/anniversaire`, `/soiree-theme`, `/repas-seminaire` | Pages render, media area visible | [ ]    |       |

## 2) Registration & Auth

| ID      | Scenario                    | Steps                                          | Expected Result                     | Status | Notes |
| ------- | --------------------------- | ---------------------------------------------- | ----------------------------------- | ------ | ----- |
| AUTH-01 | Client registration success | Open `/register`, fill required fields, submit | Account created, no fatal error     | [ ]    |       |
| AUTH-02 | Registration validation     | Submit empty/invalid email/password mismatch   | Error message shown, no crash       | [ ]    |       |
| AUTH-03 | Login/logout                | Login with valid client, then logout           | Session opens then closes correctly | [ ]    |       |

## 3) Admin Access Control

| ID     | Scenario                          | Steps                         | Expected Result                                           | Status | Notes |
| ------ | --------------------------------- | ----------------------------- | --------------------------------------------------------- | ------ | ----- |
| ADM-01 | Admin route blocked for non-admin | Open `/admin` as non-admin    | Access denied or redirect login                           | [ ]    |       |
| ADM-02 | Admin dashboard visible for admin | Login as admin, open `/admin` | 4 cards visible: Medias/Prestataires/Clients/Statistiques | [ ]    |       |

## 4) Admin Media CRUD

| ID     | Scenario     | Steps                                                | Expected Result                            | Status | Notes |
| ------ | ------------ | ---------------------------------------------------- | ------------------------------------------ | ------ | ----- |
| MED-01 | Create media | Open `/admin/event-medias/create`, submit valid data | Success message and row appears in list    | [ ]    |       |
| MED-02 | Update media | Edit existing media title/url                        | Success message and updated values visible | [ ]    |       |
| MED-03 | Delete media | Delete created test media                            | Row disappears from list                   | [ ]    |       |

## 5) Admin Prestataires CRUD

| ID       | Scenario           | Steps                                             | Expected Result                            | Status | Notes |
| -------- | ------------------ | ------------------------------------------------- | ------------------------------------------ | ------ | ----- |
| PREST-01 | Create prestataire | Open `/admin/prestataires/create`, fill full form | Success message and row appears            | [ ]    |       |
| PREST-02 | Update prestataire | Edit telephone/adresse                            | Success message and updated values visible | [ ]    |       |
| PREST-03 | Delete prestataire | Delete created test prestataire                   | Row disappears from list                   | [ ]    |       |

## 6) Admin Clients CRUD

| ID     | Scenario      | Steps                                | Expected Result                            | Status | Notes |
| ------ | ------------- | ------------------------------------ | ------------------------------------------ | ------ | ----- |
| CLI-01 | Create client | Open `/admin/clients/create`, submit | Success message and row appears            | [ ]    |       |
| CLI-02 | Update client | Edit telephone/email                 | Success message and updated values visible | [ ]    |       |
| CLI-03 | Delete client | Delete created test client           | Row disappears from list                   | [ ]    |       |

## 7) Admin Statistics

| ID      | Scenario          | Steps                      | Expected Result                                             | Status | Notes |
| ------- | ----------------- | -------------------------- | ----------------------------------------------------------- | ------ | ----- |
| STAT-01 | KPI cards visible | Open `/admin/statistiques` | Clients, Prestataires, Devis, Prestations, CA total visible | [ ]    |       |
| STAT-02 | Tables visible    | Check status/CA tables     | Data displayed without SQL error                            | [ ]    |       |

## 8) Client Account

| ID     | Scenario                      | Steps                                  | Expected Result              | Status | Notes |
| ------ | ----------------------------- | -------------------------------------- | ---------------------------- | ------ | ----- |
| ACC-01 | Account page displays profile | Open `/mon-compte` as connected client | Name/email/telephone visible | [ ]    |       |

## 9) Responsive Checks

| ID     | Scenario            | Steps                                  | Expected Result                           | Status | Notes |
| ------ | ------------------- | -------------------------------------- | ----------------------------------------- | ------ | ----- |
| RWD-01 | Burger menu mobile  | Set viewport ~390px, open any page     | Menu button visible, nav opens/closes     | [ ]    |       |
| RWD-02 | Admin tables mobile | Open admin list pages on mobile width  | Horizontal scroll works, content readable | [ ]    |       |
| RWD-03 | Forms mobile        | Open create/edit forms on mobile width | Fields/buttons usable without overlap     | [ ]    |       |

## 10) Server Health (Local)

| ID     | Scenario         | Steps                          | Expected Result              | Status | Notes |
| ------ | ---------------- | ------------------------------ | ---------------------------- | ------ | ----- |
| OPS-01 | Port health      | Check `http://localhost:8000/` | HTTP 200 and no timeout      | [ ]    |       |
| OPS-02 | Restart recovery | Restart PHP local server       | Routes recover after restart | [ ]    |       |

## Defects Log

| Defect ID  | Area | Severity | Repro Steps | Expected | Actual | Status |
| ---------- | ---- | -------- | ----------- | -------- | ------ | ------ |
| BUG-\_\_\_ |      |          |             |          |        | Open   |
