**Attention :**
- Il est strictement interdit de supprimer le fichier `LICENSE.md`.
- Il est strictement interdit de republier le projet sans un accord au préalable.

Dans ce package, le maximum de vérifications sont faites afin d'éviter les erreurs (vérification du `customerId`, des `makerCodes`, etc.).

## Pour installer le package :

Utiliser COMPOSER (projet publié sur packagist).

```bash
composer require npservices/nps-soap
```
## Utilisation du package:
Dans le fichier où vous souhaitez utiliser ce package il faudra faire :
```php
use NPServices\NpsSoapPackage\NPS;
```
Ensuite il faudra faire appel à ce package :
```php
$nps = new NPS("url_wsdl","votre_identifiant", "votre_mot_de_passe");
```
Voici la liste des méthodes disponibles : 
- getMakerCodes (Récupère la liste des makerCodes)
- getAvailability (Demande de disponibilité)
- createOrder (Création commande)

### getMakerCodes
Cette méthode n’a besoin d’aucun paramètre pour vous retourner la liste des makerCodes :
```php
$nps->getMakerCodes();
```
### getAvailability
Cette méthode a besoin de 2 paramètres pour fonctionner : **(! - Ordre des paramètres à respecter)**
-   la référence de la demande (‘exempleDispo01’)
-   Une liste contenant tous les éléments à vérifier

Exemple :
```php
$itemsToCheck = [
	[
		'reference' => '835035',
		'makerCode' => 'VALEO',
		'positionNumber' => 1,
		'requestedQuantity' => 2,
	],
	[
		'reference' => '835035',
		'makerCode' => 'VALEO',
		'positionNumber' => 2,
		'requestedQuantity' => 3,
	],
];
```
**! - Chaque élément de la liste doit contenir : ‘reference’, ‘makerCode’, ‘positionNumber’, ‘requestedQuantity’**

Explications :
-   ‘reference’ est la référence de votre produit
-   ‘makerCode’ est l’équipementier qui fourni la référence
-   ‘positionNumber’ correspond simplement à la position de la référence pour la liste de sortie
-   ‘requestedQuantity’ est la quantité demandée pour la référence
    
Utilisation :
```php
$nps->getAvailability('TEST', $itemsToCheck);
```

### createOrder
Cette méthode à besoin de 10 paramètres : **(! - Ordre des paramètres à respecter)**
-   customerId → votre numéro de client commande (**C01XXXXX** ou **CNDXXXXX**)
-   contact → Le nom complet de votre client ('Alfred Archambault')
-   phone → Le numéro de téléphone de votre client ('06.01.02.03.04')
-   email → L’email de votre client ('example.ex@test.fr')
-   reference → Une référence à donner à votre commande ('exempleCommande01')
-   entries → Une liste contenant tout les éléments à commander
-   billingAddress → Une liste contenant les informations de l’adresse de facturations ***(doit contenir les champs suivant : 'societe', 'name1', 'name2', 'street1', 'street2', 'postalCode', 'city', 'countryIsoCode', 'countryName')***
-   shippingAddress → Une liste contenant les informations de l’adresse de facturations ***(doit contenir les champs suivant : 'societe', 'name1', 'name2', 'street1', 'street2', 'postalCode', 'city', 'countryIsoCode', 'countryName')***
-   deliveryId → votre numéro d’adresse de livraison (**C00**, **LIVXX** ou **L0099**)
-   express → Est-ce que c’est une commande express ? (true ou false)

Utilisation :
On va utiliser pour l’exemple la même adresse pour la facturation et l’expédition :
```php
$address = [
	'societe' => 'societe',
	'name1' => 'Alfred',
	'name2' => 'Archambault',
	'street1' => '53, rue Beauvau',
	'street2' => 'résidence B',
	'postalCode' => '57070',
	'city' => 'METZ',
	'countryIsoCode' => 'fr',
	'countryName' => 'France',
];
```
Liste des items à commander :
```php
$itemsToOrder = [
	[
		'reference' => '835035',
		'makerCode' => 'VALEO',
		'positionNumber' => 1,
		'requestedQuantity' => 2,
	],
	[
		'reference' => '835035',
		'makerCode' => 'VALEO',
		'positionNumber' => 2,
		'requestedQuantity' => 3,
	],
];
```
Passage de la commande :
```php
$nps->createOrder(
	'CNDXXXXX',
	'Alfred Archambault',
	'01.52.42.99.41',
	'example.ex@test.fr',
	'exempleCommande01',
	$itemsToOrder,
	$address,
	$address,
	'L0099',
	false
);
