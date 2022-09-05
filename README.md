# Simple Talkback Bot


## Deploy

- To deploy, you need to create two environment variables `API_ID` and `API_HASH` and `BOT_TOKEN`  and `ADMIN_ID` set them with values you
 received from my.telegram.org
- Create `session` directory
- Create `log` directory
- Run `composer update`
- Run `php bot.php`

## Run with Docker

You can run this bot using my MadelineProto image from GHCR:

```
podman run --rm --name mybot --volume `pwd`/bot.php:/app/bot.php ghcr.io/djnotes/madelineproto-container:latest php bot.php

```
You can also use `docker` instead of `podman` in the above command.

## Deploy To Heroku
[![Deploy](https://www.herokucdn.com/deploy/button.svg)](https://heroku.com/deploy)

*Powered by* [MadelineProto][https://madelineproto.xyz]
