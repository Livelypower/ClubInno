# ClubInno Documentation
## ActivityGroupController

### Content
- [index](#index)
- [new](#new)
- [editActivityGroup](#editactivitygroup)
- [deleteActivityGroup](#deleteactivitygroup)
- [assign](#assign)

#### index
Returns the index page of all the groups. Shows all the activities and their respective groups.

Route:
> [domain].[ext]/admin/activities/groups

#### new
Creates a new ActivityGroup. Uses the [ActivityGroupForm](../Forms/ActivityGroupForm.md).

Route:
> [domain].[ext]/admin/activities/groups/new

#### editActivityGroup
Redirects to the edit page of the specified group.

Route:
> [domain].[ext]/admin/activities/groups/edit/{groupId}

#### deleteActivityGroup
Deletes the specified group.

Route:
> [domain].[ext]/admin/activities/groups/delete/{id}

#### assign
Redirects to the assignment page of the specified activities.
Using the API, you can assign users of a certain activity to that activity's groups.

Route:
> [domain].[ext]/admin/activities/groups/assign/{activityId}
