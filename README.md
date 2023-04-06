# Simple Talkback Bot


## Deploy

- To deploy, create a copy of bot.env.example and name it bot.env, then set all the environment variables inside it. 
- Create `session` directory
- Create `log` directory
- Run `composer update`
- Run `php bot.php`

## Run with Docker Compose

You can run this bot using my MadelineProto image from GHCR:

First, run `composer update` using the MadelineProto image to update the dependencies:  

```
podman run --rm --volume `pwd`:/app ghcr.io/djnotes/madelineproto-container:v8.0.0-beta50 composer update
```

Next, fire up the services using Docker Compose:  

```
docker-compose up

```


*Powered by* [MadelineProto][https://madelineproto.xyz]
