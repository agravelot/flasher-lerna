#!/bin/sh

# https://github.com/mozilla/sops/issues/304
GPG_TTY=$(tty)
export GPG_TTY

gpg --import private.key
gpg --import public.key

bash