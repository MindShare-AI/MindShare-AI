# MindShare-API
RESTFUL API to access to the database of the MindShare application

## Requests List

### GET
> - /device/{id}
> - /follow/{id}
> - /follow/stats/{id}
> - /account
> - /account/{id}
> - /account/{last_name}/{first_name}

### POST
> - /device |||| {'uuid' => [id_device_to_add]}
> - /follow |||| {'follower' => [id_account], 'following' => [id_account]}
> - /account |||| {'last_name' => [last_name], ...}

### DELETE
> - /account/{id}
> - /account/{last_name}/{first_name}
