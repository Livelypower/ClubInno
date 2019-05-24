# ClubInno Documentation
## SecurityController

### Content
- [login](#login)
- [logout](#logout)

#### login
Redirects to the login page, checks if given input is correct.
If so redirects to homepage, if not redirects back to login page with last username filled in and an error message.

Route:
> [domain].[ext]/login/{username}

#### logout
Logs the current user out and cleans out the current session, redirects to login page.

Route:
> [domain].[ext]/logout

