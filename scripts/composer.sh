#!/bin/sh

ssh-keyscan -t rsa github.com > ~/.ssh/known_hosts
composer "$@"