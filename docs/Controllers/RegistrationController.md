# ClubInno Documentation
## ActivityController

### Content
- [register](#register)
- [showActivity](#showactivity)
- [addToBasket](#addtobasket)

#### register
Redirects to the register page, registers user to the system and logs the user in after succesfully registering.
Uses the [RegistrationFormType](../Forms/RegistrationFormType.md).

Route:
> [domain].[ext]/register

#### random_str
Generates a string of random characters. Used in register.

Variables:
```
int $lenght; //The desired lenght of the string.
String $keyspace; //The characters to use to generate the string.
```

Usage:
```
$this->random_str($lenght, $keyspace);  

//Generates a 32 character lenght password using the default keyspace.
$this->random_str(32);  

//Generates a 32 character lenght password using the specified keyspace.
$this->random_str(32, azerty123);
```
