# ClubInno Documentation
## AdminController

### Content
- [listApplications](#listApplications)
- [listActivities](#listactivities)
- [listUsers](#listusers)
- [makeAdmin](#makeadmin)
- [makeTeacher](#maketeacher)
- [removeAdmin](#removeadmin)
- [removeTeacher](#removeteacher)
- [newActivity](#newactivity)
- [editActivity](#editactivity)
- [deleteActivity](#deleteactivity)
- [editActivityFiles](#editactivityfiles)
- [deleteActivityFile](#deleteactivityfile)
- [toggleActivity](#toggleactivity)
- [semester](#semester)
- [newSemester](#newsemester)
- [editSemester](#editsemester)
- [deleteSemester](#deletesemester)
- [hasActiveApplications](#hasactiveapplications)
- [generateUniqueFileName](#generateUniqueFileName)

#### listApplications
Redirects to the index page where all the sent applications are showed in a grid.
Here the admin can assign students to certain activities.

Route:
> [domain].[ext]/admin/listApplications

#### listActivities
Redirects to the page where all the activities are listed.

Route:
> [domain].[ext]/admin/activities

#### listUsers
Redirects to the page that shows all the users and where the admin can manage the roles of users.
The admin can search users on first- and or lastname.

Route:
> [domain].[ext]/admin/listUsers

#### makeAdmin
Makes a certain user an admin, redirects back to the page where all the users are listed.

Route:
> [domain].[ext]/admin/editUser/{id}/makeAdmin

#### makeTeacher
Makes a certain user an admin, redirects back to the page where all the users are listed.

Route:
> [domain].[ext]/admin/editUser/{id}/makeTeacher

#### removeAdmin
Takes away the role of admin from a certain user, redirects back to the page where all the users are listed.

Route:
> [domain].[ext]/admin/editUser/{id}/removeAdmin

#### removeTeacher
Takes away the role of admin from a certain user, redirects back to the page where all the users are listed.

Route:
> [domain].[ext]/admin/editUser/{id}/removeTeacher

#### newActivity
Redirects to the page to make a new activity.

Route:
> [domain].[ext]/admin/activity/new

#### editActivity
Redirects to the page to edit an activity.

Route:
> [domain].[ext]/admin/activity/edit/{id}

#### deleteActivity
Deletes an activity and redirects to the page that lists all activities.

Route:
> [domain].[ext]/admin/activity/delete/{id}

#### editActivityFiles
Redirects to the page to add files to an activity or delete files already added to the activity.

Route:
> [domain].[ext]/admin/activity/edit/{id}/files

#### deleteActivityFile
Deletes an existing file attached to an activity, redirects to the page where you can edit activity files.

Route:
> [domain].[ext]/admin/activity/delete/{id}/file/{name}

#### toggleActivity
Toggles the visibility for users of a certain activity.

Route:
> [domain].[ext]/admin/activity/toggle/{id}

#### semester
Redirects to the page where all the semesters are listed.
Route:
> [domain].[ext]/admin/semester

#### newSemester
Redirects to the page to make a new semester.

Route:
> [domain].[ext]/admin/semester/new

#### editSemester
Redirects to the page to edit a semester.

Route:
> [domain].[ext]/admin/semester/edit/{id}

#### deleteSemester
Deletes a semester and redirects to the page that lists all semesters.

Route:
> [domain].[ext]/admin/semester/delete/{id}

#### setActiveSemester
Sets a semester to the active semester and redirects to the page that lists all semesters.

Route:
> [domain].[ext]/admin/semester/setactive/{id}

#### hasActiveApplications
Checks wheter or not a user has sent in an application this semester, returns a boolean.

#### generateUniqueFileName
Returns a unique filename using the "md5" and "uniqid" functions.
