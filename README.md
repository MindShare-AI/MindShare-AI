# MindShare-API
RESTFUL API to access to the database of the MindShare application

## Request

### GET
> - /device/{id}
> - /follow/{id}
> - /follow/stats/{id}

### POST
> - /device |||| {'uuid' => [id_device_to_add]}
> - /follow |||| {'follower' => [id_account], 'following' => [id_account]}
