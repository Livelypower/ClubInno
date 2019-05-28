# ClubInno Documentation
## BlogPostController

### Content
- [index](#index)
- [newBlogPost](#newblogpost)
- [showBlogPost](#showblogpost)
- [showBlogPostsPerActivity](#showblogpostsperactivity)
- [deleteBlogPost](#deleteblogpost)
- [editBlogPost](#editblogpost)
- [editBlogFiles](#editblogfiles)
- [deleteBlogFile](#deleteblogfile)
- [generateUniqueFileName](#generateUniqueFileName)

#### index
Redirects to the index page of the blogposts, showing them all in an overview, with the option to filter on activity.

Route:
> [domain].[ext]/blog

#### newBlogPost
Redirects to the page to create a new blogpost, sends an email the admin when the blogpost is created.

Route:
> [domain].[ext]/blog/new

#### showBlogPost
Redirects to the page that shows the blog in detail, here a user can leave a comment.

Route:
> [domain].[ext]/blog/{id}

#### showBlogPostsPerActivity
Redirects to the page that shows an overview of all the blogposts that are about a certain activity.

Route:
> [domain].[ext]/blog/activity/{id}

#### deleteBlogPost
Deletes an existing blogpost, redirects to index page of the blogposts.

Route:
> [domain].[ext]/blog/delete/{id}

#### editBlogPost
Redirects to the page to edit a blogpost.

Route:
> [domain].[ext]/blog/edit/{id}

#### editBlogFiles
Redirects to the page to add files to a blogpost or delete files already added to the blogpost

Route:
> [domain].[ext]/blog/edit/{id}/files

#### deleteBlogFile
Deletes an existing file attached to a blog, redirects to the page where you can edit blogpost files.

Route:
> [domain].[ext]/blog/delete/{id}/file/{name}

#### generateUniqueFileName
Returns a unique filename using the "md5" and "uniqid" functions.



