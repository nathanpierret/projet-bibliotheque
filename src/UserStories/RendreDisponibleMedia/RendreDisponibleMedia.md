# UserStory de RendreDisponibleMedia

**En tant que** bibliothécaire  
**Je veux** rendre disponible un nouveau média  
**Afin de** le rendre empruntable par les adhérents de la bibliothèque

## Indications

L'accès au média à rendre disponible se fera via son id.

## Critères d'acceptation

### Média existe

Le média que l'on souhaite rendre disponible doit exister.

### Statut du média

Seul un média ayant le statut 'Nouveau' peut être rendu disponible.

### Enregistrement dans la base de données

Le changement de statut du média doit être correctement enregistré dans la base de données.

### Messages d'erreurs

Des messages d'erreurs explicites doivent être retournés en cas d'informations manquantes ou incorrectes.

## Classe à implémenter

La UserStory nécessite la création d'une seule classe :  

- La classe **RendreDisponibleMedia** pour changer le statut **Nouveau** d'un objet **Média** au statut **Disponible** et synchronise les changements dans la base de données.

## Classe RendreDisponibleMedia

Cette classe récupère un objet **Média** dans la base de données à partir d'un **id** donné en paramètre, et lui modifie son **statut** en **Disponible**.

## Attribut

### $entityManager

- Type : Classe **EntityManagerInterface**.
- Utilisation : Accéder à l'outil **EntityManager** de Doctrine pour récupérer les données dans la base de données.

## Méthode

### execute

- Paramètre : **$idMedia** (integer)
- Renvoie **True** si le **statut** de l'objet **Média** choisi est passé à **Disponible** au lieu de **Nouveau** dans la base de données, sinon renvoie une **Exception**

## Commandes

### Tests d'intégration
Voici la commande à utiliser pour visualiser les tests d'intégration de l'User Story :
```batch
  composer test-disponibility
```

### Silly
Voici la commande Silly qui utilise l'User Story :
```batch
  php app.php biblio:disponibility
```