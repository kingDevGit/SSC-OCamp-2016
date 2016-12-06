#!/bin/bash

set -eo pipefail

propel config:convert
propel migrate

exec "$@"
