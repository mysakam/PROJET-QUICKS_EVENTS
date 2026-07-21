# MERISE - Modèle Conceptuel de Données (MCD)

## Entités

### 1. CLIENT

**Identifiant** : id_client  
**Attributs** :

- id_client (numérique, PK)
- nom (chaîne 100, NN)
- prenom (chaîne 100, NN)
- email (chaîne 150, NN, UQ)
- mot_de_passe (chaîne 255, NN)
- telephone (chaîne 30)
- created_at (datetime, NN)

---

### 2. CATEGORIE

**Identifiant** : id_categorie  
**Attributs** :

- id_categorie (numérique, PK)
- nom (chaîne 100, NN)
- slug (chaîne 120, NN, UQ)
- created_at (datetime, NN)

---

### 3. PRESTATAIRE

**Identifiant** : id_prestataire  
**Attributs** :

- id_prestataire (numérique, PK)
- nom (chaîne 150, NN)
- email (chaîne 150, UQ)
- telephone (chaîne 30)
- adresse (chaîne 255)
- description (texte)
- iban (chaîne 34)
- bic (chaîne 20)
- banque_nom (chaîne 150)
- titulaire_compte (chaîne 150)
- note_sur_10 (décimal 3,1)
- created_at (datetime, NN)

---

### 4. PRESTATION

**Identifiant** : id_prestation  
**Attributs** :

- id_prestation (numérique, PK)
- nom (chaîne 150, NN)
- description (texte)
- prix_unitaire (décimal 10,2, NN)
- is_active (booléen, NN)
- created_at (datetime, NN)

---

### 5. DEVIS

**Identifiant** : id_devis  
**Attributs** :

- id_devis (numérique, PK)
- reference (chaîne 50, NN, UQ)
- statut (chaîne 50, NN)
- date_evenement (date)
- message_client (texte)
- montant_total (décimal 10,2, NN)
- created_at (datetime, NN)

---

### 6. DEVIS_LIGNE (Association porteuse)

**Identifiant** : id_ligne_devis  
**Attributs** :

- id_ligne_devis (numérique, PK)
- quantite (numérique, NN)
- prix_unitaire (décimal 10,2, NN)
- montant_ligne (décimal 10,2, NN)

---

### 7. FACTURE

**Identifiant** : id_facture  
**Attributs** :

- id_facture (numérique, PK)
- reference (chaîne 50, NN, UQ)
- statut (chaîne 50, NN)
- montant_ttc (décimal 10,2, NN)
- date_emission (date)
- date_echeance (date)
- date_paiement (date)
- date_envoi_mail (datetime)
- created_at (datetime, NN)

---

### 8. EVENT_MEDIA

**Identifiant** : id_media  
**Attributs** :

- id_media (numérique, PK)
- theme_slug (chaîne 80, NN)
- media_type (énumération {'image', 'video'}, NN)
- media_url (chaîne 255, NN)
- title_fr (chaîne 150, NN)
- title_en (chaîne 150, NN)
- description_fr (texte)
- description_en (texte)
- position (numérique, NN)
- is_active (booléen, NN)
- created_at (datetime, NN)
- updated_at (datetime, NN)

---

### 9. PRESTATAIRE_DISPONIBILITE

**Identifiant** : id_disponibilite  
**Attributs** :

- id_disponibilite (numerique, PK)
- date_evenement (date, NN)
- statut (chaine 20, NN, valeurs attendues : disponible ou indisponible)
- commentaire (chaine 255)
- updated_at (datetime, NN)
- created_at (datetime, NN)

---

### 10. NOTIFICATION

**Identifiant** : id_notification  
**Attributs** :

- id_notification (numerique, PK)
- recipient_role (chaine 50, NN)
- type (chaine 60, NN)
- title (chaine 180, NN)
- message (texte, NN)
- payload_json (texte)
- is_read (booleen, NN)
- created_at (datetime, NN)
- read_at (datetime)

---

## Associations

### 1. FAIT (CLIENT - DEVIS)

- **CLIENT** (0, N) --- FAIT --- (1, 1) **DEVIS**
- **Sens** : Un client fait zéro ou plusieurs devis ; un devis est fait par exactement un client
- **FK dans DEVIS** : id_client

### 2. CONTIENT (DEVIS - DEVIS_LIGNE)

- **DEVIS** (1, N) --- CONTIENT --- (1, 1) **DEVIS_LIGNE**
- **Sens** : Un devis contient une ou plusieurs lignes ; une ligne appartient à exactement un devis
- **FK dans DEVIS_LIGNE** : id_devis

### 3. CONCERNE (PRESTATION - DEVIS_LIGNE)

- **PRESTATION** (0, N) --- CONCERNE --- (1, 1) **DEVIS_LIGNE**
- **Sens** : Une prestation figure dans zéro ou plusieurs lignes ; une ligne concerne exactement une prestation
- **FK dans DEVIS_LIGNE** : id_prestation

### 4. CLASSE (CATEGORIE - PRESTATION)

- **CATEGORIE** (0, N) --- CLASSE --- (1, 1) **PRESTATION**
- **Sens** : Une catégorie classe zéro ou plusieurs prestations ; une prestation appartient exactement à une catégorie
- **FK dans PRESTATION** : id_categorie

### 5. PROPOSE (PRESTATAIRE - PRESTATION)

- **PRESTATAIRE** (0, N) --- PROPOSE --- (1, 1) **PRESTATION**
- **Sens** : Un prestataire propose zéro ou plusieurs prestations ; une prestation est proposée par exactement un prestataire
- **FK dans PRESTATION** : id_prestataire

### 6. GENERE (DEVIS - FACTURE)

- **DEVIS** (0, N) --- GENERE --- (1, 1) **FACTURE**
- **Sens** : Un devis génère zéro ou plusieurs factures ; une facture est générée par exactement un devis
- **Note** : Metier recommande = (0, 1) pour une facture unique par devis ; l'application tend vers cette contrainte au niveau de l'implementation
- **FK dans FACTURE** : id_devis

### 7. DEFINIT (PRESTATAIRE - PRESTATAIRE_DISPONIBILITE)

- **PRESTATAIRE** (0, N) --- DEFINIT --- (1, 1) **PRESTATAIRE_DISPONIBILITE**
- **Sens** : Un prestataire definit zero ou plusieurs statuts de disponibilite par date ; une disponibilite appartient a exactement un prestataire
- **FK dans PRESTATAIRE_DISPONIBILITE** : id_prestataire

### 8. NOTIFIE (NOTIFICATION)

- **NOTIFICATION** est une structure technique transversale utilisee pour les alertes d'administration
- **Sens** : La table stocke des messages adresses a un role destinataire, sans cle etrangere metier directe dans le schema actuel

---

## Note de perimetre

Le noyau metier principal repose sur CLIENT, CATEGORIE, PRESTATAIRE, PRESTATION, DEVIS, DEVIS_LIGNE, FACTURE et EVENT_MEDIA.

PRESTATAIRE_DISPONIBILITE et NOTIFICATION sont des structures operationnelles complementaires effectivement utilisees par l'application et creees automatiquement si elles sont absentes.

---

## Diagramme ER

```
CLIENT (0,N) --- FAIT --- (1,1) DEVIS
                                    |
                                    | (1,N) CONTIENT
                                    |
                            DEVIS_LIGNE (1,1)
                                    |
                                    | (1,1) CONCERNE
                                    |
                            PRESTATION (0,N)
                                    |
                            +-------+-------+
                            |               |
                    (1,1) CLASSE    (1,1) PROPOSE
                            |               |
                    CATEGORIE       PRESTATAIRE
                            (0,N)   (0,N)

DEVIS (0,N) --- GENERE --- (1,1) FACTURE

PRESTATAIRE (0,N) --- DEFINIT --- (1,1) PRESTATAIRE_DISPONIBILITE

EVENT_MEDIA (isolee)
NOTIFICATION (structure technique isolee)
```

---

## Légende

- **PK** : Primary Key (Clé Primaire)
- **FK** : Foreign Key (Clé Étrangère)
- **NN** : Not Null (Obligatoire)
- **UQ** : Unique (Valeur unique)
- **(min, max)** : Cardinalité (minimum, maximum)
