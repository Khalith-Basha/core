#!/bin/bash

sed \
	-e '/^-- /d' \
	-e 's/`//g' \
	-e 's/osc_db./\/*TABLE_PREFIX*\//g' \
	struct.sql > ../webapp/installer/data/struct.sql

