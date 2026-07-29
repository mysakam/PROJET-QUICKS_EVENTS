# Rapport de test de securite

Dans le cadre des tests de securite, plusieurs scenarii ont ete simules afin de verifier le comportement de l'application face aux risques les plus courants. L'objectif etait de controler l'authentification, la gestion des acces, la protection des mots de passe et la resistance aux injections.

| Test                                     | Methode                                                           | Resultat obtenu | Commentaire                                                                   |
| ---------------------------------------- | ----------------------------------------------------------------- | --------------- | ----------------------------------------------------------------------------- |
| Connexion avec identifiants valides      | AuthController::authenticate()                                    | OK              | L'utilisateur est authentifie et la session est creee correctement.           |
| Connexion avec mot de passe incorrect    | AuthController::authenticate()                                    | OK              | La connexion est refusee et un message d'erreur est affiche.                  |
| Mot de passe enregistre en base          | ClientModel::createWithProfile() et ClientModel::updatePassword() | OK              | Les mots de passe sont hashes avant stockage, ils ne sont jamais en clair.    |
| Acces a une page protegee sans connexion | AuthMiddleware::handle()                                          | OK              | L'utilisateur non connecte est redirige vers la page de connexion.            |
| Acces a la zone admin sans droit         | AdminMiddleware::handle() et Auth::isAdmin()                      | OK              | L'acces est refuse si l'utilisateur n'a pas le role administrateur.           |
| Tentative d'injection SQL sur le login   | AuthController::authenticate()                                    | OK              | Les requetes preparees limitent le risque d'injection.                        |
| Changement de mot de passe               | ClientModel::updatePassword()                                     | OK              | Le nouveau mot de passe est de nouveau hashé avant l'enregistrement.          |
| Bruteforce sur le login                  | Aucun mecanisme dedie actuellement                                | KO              | Aucune limitation de tentatives ou blocage automatique n'est encore implante. |

**Conclusion**

Ces tests montrent que l'application met deja en place des protections essentielles, notamment le hashage des mots de passe, la gestion des sessions et le controle d'acces. En revanche, certaines mesures de durcissement restent a completer, comme la limitation des tentatives de connexion et la mise en place d'une authentification multifacteur.
