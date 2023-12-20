# UserStory de RetournerEmprunt

**En tant que** bibliothécaire  
**Je veux** enregistrer le retour d'un média (emprunt) à partir du numéro d'emprunt   
**Afin de** rendre le média disponible pour un prochain emprunt

## Indications

L'accès à l'emprunt à retourner se fera via son numéro d'emprunt.

## Critères d'acceptation

### Emprunt existe

L'emprunt que l'on souhaite retourner doit exister dans la base de données.

### Emprunt pas encore restitué

Seul un emprunt qui n'est pas encore restitué peut être retourné.

### Enregistrement dans la base de données

- Le média contenu dans l'emprunt doit avoir son statut à **Disponible**.
- La **date de retour** doit être équivalente à la date du jour (date courante).

### Messages d'erreurs

Des messages d'erreurs explicites doivent être retournés en cas d'informations manquantes ou incorrectes.

## Classe à implémenter

La UserStory nécessite la création d'une seule classe :

- La classe **RetournerEmprunt** pour changer le statut **Emprunté** d'un objet **Média** au statut **Disponible** et d'ajouter une **DateRetour** dans l'emprunt.

## Classe RetournerEmprunt

Cette classe ajoute à un emprunt récupéré par son numéro une **DateRetour** et modifie le statut du **Média** dans l'emprunt en **Disponible**.

## Attribut

### $entityManager

- Type : Classe **EntityManagerInterface**.
- Utilisation : Accéder à l'outil **EntityManager** de Doctrine pour récupérer les données dans la base de données.

## Méthode

### execute

- Paramètre : **$$numeroEmprunt** (chaîne de caractères)
- Renvoie **True** si le **statut** de l'objet **Média** dans l'emprunt choisi est passé à **Disponible** et que la **DateRetour** soit bien ajoutée, sinon renvoie une **Exception**

## Commandes

### Tests d'intégration
Voici la commande à utiliser pour visualiser les tests d'intégration de l'User Story :
```batch
  composer test-return-emprunt
```

### Silly
Voici la commande Silly qui utilise l'User Story :
```batch
  php app.php biblio:return:emprunt
```