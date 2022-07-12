# Simple Talkback Bot


## Deploy

- To deploy, you need to create two environment variables `API_ID` and `API_HASH` and `BOT_TOKEN`  and set them with values you
 received from my.telegram.org
- Create `session` directory
- Create `log` directory
- Run `composer update`
- Run `php bot.php`

## Run with Docker

You can run this bot using my MadelineProto image from GHCR:

```
docker run --rm --name mybot --volume `pwd`:/app ghcr.io/djnotes/madelineproto-container:main php bot.php

```
You can also use `podman` instead of `docker` in the above command if you have [Podman](https://podman.io) installed.

## Deploy To Heroku
[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy)

*Powered by* [MadelineProto][https://madelineproto.xyz]
