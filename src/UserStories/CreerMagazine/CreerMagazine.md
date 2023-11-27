# UserStory de CreerMagazine

La UserStory nécessite la création de 2 classes :

- La classe **CreerMagazineRequete** pour créer la requête contenant les données à ajouter la table **Magazine** de la base de données.
- La classe **CreerMagazine** pour vérifier les données de **CreerMagazineRequete** et les insérer dans la base de données si les critères d'acceptation sont validés.

## Classe CreerMagazineRequete

Cette classe construit la requête qui contient les données à ajouter dans la base de données dans la table **Magazine**.
## Attributs

### $titre
- Type : string (chaîne de caractères).
- Critère d'acceptation : Le titre doit être renseigné.

### $dateCreation
- Type : string (chaîne de caractères).
- Critère d'acceptation : La date de création doit être renseignée.

### $numero
- Type : string (chaîne de caractères).
- Critère d'acceptation : Le numéro doit être renseigné.

### $datePublication
- Type : string (chaîne de caractères).
- Critère d'acceptation : La date de publication doi être renseignée.

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
        $requete = new CreerMagazineRequete("Weebdo","05/11/2023 15:16:13",
        "169","18/11/2023 16:15:15");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        // Act
        $resultat = $creerMagazine->execute($requete);
```

### Test si le titre n'est pas renseigné
```php
        // Arrange
        $requete = new CreerMagazineRequete("","05/11/2023 15:16:13",
        "169","18/11/2023 16:15:15");
        $creerMagazine = new CreerMagazine($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("Le titre doit être renseigné !");
        // Act
        $resultat = $creerMagazine->execute($requete);
```