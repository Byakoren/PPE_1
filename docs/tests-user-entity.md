# ✅ Tests de l'entité `User` avec PHPUnit

## 🎯 Objectif

S'assurer que les méthodes `getters` et `setters` de l'entité `User` fonctionnent correctement, en particulier :

- Le champ `resetToken`
- Le champ `email` et l'identifiant utilisateur (`getUserIdentifier()`)

---

## 🧪 Tests mis en place

### 📁 Fichier : `tests/Entity/UserTest.php`

```php
namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testResetTokenGetterAndSetter(): void
    {
        $user = new User();

        // Le resetToken est null par défaut
        $this->assertNull($user->getResetToken(), 'Le resetToken est null par défaut.');

        // On définit un token fictif
        $token = 'sample-token-123';
        $user->setResetToken($token);

        // On vérifie que le token est bien stocké
        $this->assertSame($token, $user->getResetToken(), 'Le resetToken est correctement défini et récupéré.');
    }

    public function testEmailSetterAndGetter(): void
    {
        $user = new User();
        $email = 'test@example.com';

        $user->setEmail($email);

        // Vérifie que l’email est stocké et renvoyé
        $this->assertSame($email, $user->getEmail());

        // Vérifie que l’identifiant retourné est bien l’email
        $this->assertSame($email, $user->getUserIdentifier());
    }
}


## Resultat après test ##

## Time: 00:00.005, Memory: 6.00 MB

## OK (2 tests, 4 assertions)