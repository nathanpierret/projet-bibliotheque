# UserStory de CreerMagazine

**En tant que** bibliothécaire  
**Je veux** créer un magazine  
**Afin de** le rendre accessible aux adhérents de la bibliothèque

## Critères d'acceptation

### Validation des données

Ces données doivent être renseignées :

- Le titre du magazine.
- Le numéro d'édition du magazine.
- La date de publication du magazine.
- La date de création du magazine dans la base de données.

### Enregistrement dans la base de données

Les informations du magazine doivent être correctement enregistrées dans la base de données.

### Messages d'erreurs

Des messages d'erreur explicites doivent être retournés en cas d'informations manquantes ou incorrectes.

### Cas du statut et de la durée de l'emprunt

- Le statut par défaut lors de la création d'un magazine devra être **'Nouveau'**.
- La durée de l'emprunt devra être égale à la durée définie lors de la présentation du projet.

## Classes à implémenter

La UserStory nécessite la création de 2 classes :

- La classe **CreerMagazineRequete** pour créer la requête contenant les données à ajouter la table **Magazine** de la base de données.
- La classe **CreerMagazine** pour vérifier les données de **CreerMagazineRequete** et les insérer dans la base de données si les critères d'acceptation sont validés.

## Classe CreerMagazineRequete

Cette classe construit la requête qui contient les données à ajouter dans la base de données dans la table **Magazine**.
## Attributs

**$titre :** string (chaîne de caractères).  
**$numero :** string (chaîne de caractères).  
**$datePublication :** string (chaîne de caractères).

## Classe CreerMagazine

Cette classe récupère une instance de **CreerMagazineRequete** et vérifie les données à l'intérieur.  
Si les données sont valides, alors elles sont ajoutées dans la base de données.

## Attributs

### $entityManager
- Type : Classe **EntityManagerInterface**.
- Utilisation : Accéder à l'outil **EntityManager** de Doctrine pour insérer les données dans la base de données.

### $validateur
- Type : Classe **ValidatorInterface**.
- Utilisation : Accéder à l'outil **Validator** pour faire des tests d'intégration.

## Méthode

### execute
- Paramètre : **$requete** (Classe **CreerMagazineRequete**)
- Renvoie **True** si les données ont été synchronisées dans la base de données,  
  sinon renvoie une **Exception**.

## Exemple de code

### Création d'un Magazine
```php
        // Arrange
        $requete = new CreerMagazineRequete("Weebdo","169","18/11/2023 16:15:15");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        // Act
        $resultat = $creerMagazine->execute($requete);
```

### Test si le titre n'est pas renseigné
```php
        // Arrange
        $requete = new CreerMagazineRequete("","169","18/11/2023 16:15:15");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("Le titre doit être renseigné !");
        // Act
        $resultat = $creerMagazine->execute($requete);
```