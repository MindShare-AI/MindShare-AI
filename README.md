# MindShare-API
RESTFUL API to access to the database of the MindShare application. <br/>
The API is accessible in this url : https://mindhsare-ai.alwaysdata.net/api/ <br/>
All requests are protected, they need a bearer token in the header of the request.

## Requests List

### GET

> - /follow/{id}
> - /follow/stats/{id}

> - /account
> - /account/{id}
> - /account/{last_name}/{first_name}

> - /post
> - /post/{id_post}
> - /post/{last_name}/{first_name}
> - /post/comments/{id_post}
> - /post/stats/{id_account}

### POST

> - /follow |||| {'follower' => [id_account], 'following' => [id_account]}

> - /account |||| {'last_name' => [last_name], ...}

> - /post |||| {'message' => [message], ...}

### DELETE
> - /account/{id}
> - /account/{last_name}/{first_name}

> - /post/{id}
