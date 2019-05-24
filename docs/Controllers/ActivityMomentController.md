# ClubInno Documentation
## ActivityMomentController

### Content
- [index](#index)
- [newActivityMoment](#newactivitymoment)
- [editActivityMoment](#editactivitymoment)
- [deleteActivityMoment](#deleteactivitymoment)


#### index
Returns the index page of all the moments.

Route:
> [domain].[ext]/activity/moment

#### newActivityMoment
Creates a new moment. Uses the [ActivityMomentType](../Forms/ActivityMomentType.md).

Route:
> [domain].[ext]/activity/moment/new

#### editActivityMoment
Redirects to the edit page of the specified moment.

Route:
> [domain].[ext]/activity/moment/edit/{id}

#### deleteActivityMoment
Deletes the specified moment.

Route:
> [domain].[ext]/activity/moment/delete/{id}


