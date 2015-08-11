#!/usr/bin/python
# -*- coding: utf8 -*-

# This script fakes 'name' object of JSON files in the current directory

import glob
import json
from faker import Factory
fake = Factory.create()

# Search every json files
for _file in glob.glob('*.json'):
	# Open found file and get data
	with file(_file) as f:
		json_object = json.loads(f.read())

	# Replace all json 'name' data with random words
	for node in json_object['nodes']:
		node['name'] = 'esw' + fake.domain_word()

	# Overwrite with new data
	f = open(_file, 'w+')
	json.dump(json_object, f, separators=(',', ':'))
	f.close()
