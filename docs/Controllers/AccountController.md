# ClubInno Documentation
## AccountController

### Content
- index()
- editAccount()
- changePassword()
- basket()
- deleteFromBasket()
- clearbasket()
- forgotPassword()
- viewApplications()
- random_str()
- generateUniqueFileName()

#### index()
Returns the profile page of the user you are currently logged in as.

Route:
> [domain].[ext]/account


#### editAccount()
Redirects you to the profile edit page, where you can change your account details.
Makes use of the [AccountEditForm](../Forms/AccountEditForm.md).

Route:
> [domain].[ext]/account/aditAccount

#### changePassword()
Redirects you to the password change form.
Uses the [ChangePasswordForm](../Forms/ChangePasswordForm.md).

Route:
> [domain].[ext]/account/changePassword

### basket()
Shows you the contents of your basket.

Route:
> [domain].[ext]/account/basket

#### deleteFromBasket()
Removes a specified Activity from your basket.

Route:
> [domain].[ext]/account/basket/delete/{activityId}

#### clearbasket()
Removes all Activities from your basket.

Route:
> [domain].[ext]/account/basket/clear

#### forgotPassword()
Redirects you to the password forgotten form.
Uses the [PasswordForgottenForm](../Forms/PasswordForgottenForm.md).

Route:
> [domain].[ext]/account/forgotPassword

#### viewApplications()
Shows you all the applications you sent in from your basket.

Route:
> [domain].[ext]/account/applications

#### random_str()
Generates a string of random characters. Used in forgotPassword().

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

#### generateUniqueFileName()
Generates a unique name for files.

Usage:
```
$fileName = $this->generateUniqueFileName();
```
