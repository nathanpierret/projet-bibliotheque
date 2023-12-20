# UserStory de EmprunterMedia

**En tant que** bibliothécaire  
**Je veux** enregistrer un emprunt de média disponible pour un adhérent  
**Afin de** permettre à l'adhérent d'utiliser ce média pour une durée déterminée

## Indications

Pour enregistrer l’emprunt, le bibliothécaire aura besoin de l’id du média et du numéro d’adhérent de l’emprunteur.

## Critères d'acceptation

### Média

- Le média doit exister dans la base de données
- Le média doit être disponible

### Adhérent

- L’adhérent doit exister dans la base de données
- L’adhésion de l’adhérent doit être valide

### Enregistrement dans la base de données

L’emprunt doit être correctement enregistré dans la base de données. La date de retour prévue doit être correctement initialisée en fonction du média emprunté (livre, bluray ou magazine) ainsi que la date d’emprunt.

A l’issue de l’enregistrement de l’emprunt, le statut du média doit être à “Emprunte”.

### Messages d'erreurs

Des messages d’erreur explicites doivent être retournés en cas d’informations manquantes ou incorrectes.

## Classes à implémenter

La UserStory nécessite la création de 2 classes :

- La classe **EmprunterMediaRequete** pour créer la requête contenant des données à ajouter la table **Emprunt** de la base de données.
- La classe **EmprunterMedia** pour vérifier les données de **EmprunterMediaRequete** et ajouter un emprunt dans la base de données si les critères d'acceptation sont validés.

## Classe EmprunterMediaRequete

Cette classe construit la requête qui contient des données à ajouter dans la base de données dans la table **Emprunt**.
## Attributs

**$adherent :** string (chaîne de caractères) -> Numéro d'adhérent.  
**$media :** int (nombre entier) -> Identifiant du média.

## Classe EmprunterMedia

Cette classe récupère un objet **EmprunterMediaRequete** et vérifie les données à l'intérieur.  
Si les données sont valides, alors l'emprunt est ajouté dans la base de données.

## Attributs

### $entityManager
- Type : Classe **EntityManagerInterface**.
- Utilisation : Accéder à l'outil **EntityManager** de Doctrine pour insérer les données dans la base de données.

### $generateur
- Type : Classe **GenerateurNumeroEmprunt**.
- Utilisation : Accéder au service **GenerateurNumeroEmprunt** pour créer un numéro d'emprunt automatiquement.

### $validateur
- Type : Classe **ValidatorInterface**.
- Utilisation : Accéder à l'outil **Validator** pour faire des tests d'intégration.

## Méthode

### execute
- Paramètre : **$requete** (Classe **EmprunterMediaRequete**)
- Renvoie **True** si les données ont été synchronisées dans la base de données,  
  sinon renvoie une **Exception**.

## Commandes

### Tests d'intégration
Voici la commande à utiliser pour visualiser les tests d'intégration de l'User Story :
```batch
  composer test-emprunt
```

### Silly
Voici la commande Silly qui utilise l'User Story :
```batch
  php app.php biblio:emprunt
```