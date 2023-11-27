# UserStory de CreerLivre

La UserStory nécessite la création de 2 classes :

- La classe **CreerLivreRequete** pour créer la requête contenant les données à ajouter la table **Livre** de la base de données.
- La classe **CreerLivre** pour vérifier les données de **CreerLivreRequete** et les insérer dans la base de données si les critères d'acceptation sont validés.

## Classe CreerLivreRequete

Cette classe construit la requête qui contient les données à ajouter dans la base de données dans la table **Livre**.  
## Attributs

### $titre
- Type : string (chaîne de caractères).  
- Critère d'acceptation : Le titre doit être renseigné.

### $isbn
- Type : string (chaîne de caractères).  
- Critères d'acceptation : L'ISBN doit être renseigné et doit être unique.

### $auteur
- Type : string (chaîne de caractères).  
- Critère d'acceptation : Le nom de l'auteur doit être renseigné.

### $dateCreation
- Type : string (chaîne de caractères).  
- Critère d'acceptation : La date de création doit être renseignée.

### $nbPages
- Type : string (chaîne de caractères).  
- Critères d'acceptation : Le nombre de pages doit être renseigné et doit être supérieur à 0.

## Classe CreerLivre

Cette classe récupère une instance de **CreerLivreRequete** et vérifie les données à l'intérieur.  
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
- Paramètre : **$requete** (Classe **CreerLivreRequete**)
- Renvoie **True** si les données ont été synchronisées dans la base de données,  
sinon renvoie une **Exception**.

## Exemple de code

### Création d'un Livre
```php
        // Arrange
        $requete = new CreerLivreRequete("La noyade","978-3-1249-3451-3",
        "Le petit GREGORY","02/11/2023 10:35:15",164);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        // Act
        $resultat = $creerLivre->execute($requete);
```

### Test si le nom de l'auteur n'est pas renseigné
```php
        // Arrange
        $requete = new CreerLivreRequete("La noyade","978-3-1249-3451-3",
        "","02/11/2023 10:35:15",164);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("Le nom de l'auteur doit être renseigné !");
        // Act
        $resultat = $creerLivre->execute($requete);
```