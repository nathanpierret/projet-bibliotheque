# UserStory de CreerLivre

**En tant que** bibliothécaire  
**Je veux** créer un livre  
**Afin de** le rendre accessible aux adhérents de la bibliothèque  

## Critères d'acceptation

### Validation des données

Ces données doivent être renseignées :

- Le titre du livre.
- L'ISBN du livre.
- Le nom de l'auteur.
- Le nombre de pages du livre.
- La date de création du livre dans la base de données.

L'ISBN doit aussi être unique et valide.

### Enregistrement dans la base de données

Les informations du livre doivent être correctement enregistrées dans la base de données.

### Messages d'erreurs

Des messages d'erreur explicites doivent être retournés en cas d'informations manquantes ou incorrectes.

### Cas du statut et de la durée de l'emprunt

- Le statut par défaut lors de la création d'un livre devra être **'Nouveau'**.
- La durée de l'emprunt devra être égale à la durée définie lors de la présentation du projet.

## Classes à implémenter

La UserStory nécessite la création de 2 classes :

- La classe **CreerLivreRequete** pour créer la requête contenant les données à ajouter la table **Livre** de la base de données.
- La classe **CreerLivre** pour vérifier les données de **CreerLivreRequete** et les insérer dans la base de données si les critères d'acceptation sont validés.

## Classe CreerLivreRequete

Cette classe construit la requête qui contient les données à ajouter dans la base de données dans la table **Livre**.  
## Attributs

**$titre :** string (chaîne de caractères).  
**$isbn :** string (chaîne de caractères).  
**$auteur :** string (chaîne de caractères).  
**$nbPages :** string (chaîne de caractères).  

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
        "Le petit GREGORY",164);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        // Act
        $resultat = $creerLivre->execute($requete);
```

### Test si le nom de l'auteur n'est pas renseigné
```php
        // Arrange
        $requete = new CreerLivreRequete("La noyade","978-3-1249-3451-3",
        "",164);
        $creerLivre = new CreerLivre($this->entityManager,$this->validateur);
        $this->expectExceptionMessage("Le nom de l'auteur doit être renseigné !");
        // Act
        $resultat = $creerLivre->execute($requete);
```