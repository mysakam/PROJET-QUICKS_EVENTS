# Rapport diagnostic et tests securite

Date: 2026-06-12
Perimetre: front + back (auth, sessions, formulaires POST, devis)

## 1) Diagnostic (etat actuel)

### Corriges

- CSRF global sur POST cote front via verification dans le routeur.
- CSRF global sur POST cote back via verification dans le routeur.
- Transmission CSRF en fetch front et back (champ formulaire + header HTTP).
- Durcissement session front et back: cookies HttpOnly, SameSite=Lax, mode strict, timeout inactivite, rotation session id, liaison User-Agent.
- En-tetes securite renforces: X-Content-Type-Options, X-Frame-Options, Referrer-Policy, CSP de base.
- IDOR devis client corrige sur affichage details et page succes (verification de propriete id_client).
- Echappement XSS ajoute dans dashboard client et en-tete devis.

### Risques residuels

- CSP contient encore unsafe-inline pour compatibilite legacy.
- Plusieurs vues front utilisent des sorties non echappees qui proviennent surtout de textes statiques; faible risque actuel mais recommandation d uniformiser avec e().
- Pas encore de batterie de tests automatises securite (tests manuels proposes ci-dessous).

## 2) Plan de test manuel (reproductible)

### A. CSRF - Front

1. Se connecter en client puis ouvrir la page panier.
2. Supprimer manuellement le champ \_csrf_token d un formulaire POST via devtools.
3. Soumettre le formulaire.
   Resultat attendu:

- Requete refusee avec statut 419 et message de session expiree.

4. Recharger la page normalement.
5. Soumettre le meme formulaire sans modification.
   Resultat attendu:

- Action acceptee (suppression, ajout ou vidage selon le formulaire).

### B. CSRF - Back

1. Se connecter en admin.
2. Ouvrir un formulaire POST (creation client, update media, etc.).
3. Supprimer \_csrf_token dans le formulaire puis soumettre.
   Resultat attendu:

- Requete refusee avec statut 419.

4. Soumettre ensuite sans retirer le token.
   Resultat attendu:

- Action acceptee.

### C. IDOR - Devis client

1. Se connecter avec Client A.
2. Ouvrir un devis via URL /devis/{id} qui appartient a Client B.
   Resultat attendu:

- Reponse 403 Acces refuse.

3. Tenter /devis/success/{id} sur un devis de Client B.
   Resultat attendu:

- Reponse 403 Acces refuse.

### D. Session hardening

1. Se connecter puis rester inactif au-dela du timeout configure.
2. Refaire une action protegee.
   Resultat attendu:

- Session invalidee et retour vers authentification si necessaire.

3. Verifier cookies de session dans le navigateur.
   Resultat attendu:

- HttpOnly actif.
- SameSite=Lax actif.
- Secure actif en HTTPS.

### E. XSS reflet / stocke (smoke)

1. Tenter d injecter script dans des champs texte client (profil, message devis) avec payload simple: <script>alert(1)</script>.
2. Afficher ensuite les pages qui rendent ces valeurs.
   Resultat attendu:

- Le script ne s execute pas; la valeur est neutralisee/echappee.

## 3) Commandes techniques executees

- php -l sur les fichiers modifies de securite: OK.
- Smoke test generation/verification token CSRF front: OK.
- Smoke test generation/verification token CSRF back: OK.
- Smoke test checkRequest via POST/header front/back: OK.

## 4) Recommandations priorisees

1. Retirer progressivement unsafe-inline dans la CSP (niveau eleve).
2. Uniformiser l echappement de toutes les sorties dynamiques via e() dans les vues (niveau eleve).
3. Ajouter des tests fonctionnels automatises de non-regression sur CSRF et autorisations devis (niveau moyen).
4. Ajouter un journal securite pour les refus 403/419 (niveau moyen).
