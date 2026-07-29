# Tableau routes panier et devis

| Module              | Methode HTTP | Route                  | Controleur       | Methode           | Role                                  |
| ------------------- | ------------ | ---------------------- | ---------------- | ----------------- | ------------------------------------- |
| Panier              | GET          | /panier                | PanierController | index             | Afficher le panier                    |
| Panier              | POST         | /panier/ajouter/{id}   | PanierController | add               | Ajouter une prestation au panier      |
| Panier              | POST         | /panier/supprimer/{id} | PanierController | remove            | Retirer une prestation du panier      |
| Panier              | POST         | /panier/vider          | PanierController | clear             | Vider le panier                       |
| Devis (preparation) | GET          | /mon-evenement         | DevisController  | eventRequest      | Afficher le formulaire evenement      |
| Devis (preparation) | POST         | /mon-evenement         | DevisController  | eventRequestStore | Enregistrer les infos evenement       |
| Devis (checkout)    | GET          | /devis/checkout        | DevisController  | checkout          | Afficher le recapitulatif avant envoi |
| Devis (creation)    | POST         | /devis/store           | DevisController  | store             | Creer le devis en base                |
| Devis (workflow)    | POST         | /devis/{id}/valider    | DevisController  | validate          | Valider un devis                      |
| Devis (workflow)    | POST         | /devis/{id}/annuler    | DevisController  | cancel            | Annuler un devis                      |
| Devis (workflow)    | POST         | /devis/{id}/reprendre  | DevisController  | reopen            | Reprendre un devis annule             |
| Devis (resultat)    | GET          | /devis/success/{id}    | DevisController  | success           | Afficher la confirmation              |
| Devis (liste)       | GET          | /devis                 | DevisController  | index             | Lister les devis du client            |
| Factures            | GET          | /factures              | DevisController  | factures          | Lister les factures liees             |
| Devis (detail)      | GET          | /devis/{id}            | DevisController  | show              | Afficher le detail d un devis         |
