#--------------------------------------------------------------------------------
# This is a make file (see <http://en.wikipedia.org/wiki/Make_(software)>)
# @author Richard Caceres
#--------------------------------------------------------------------------------

#--------------------------------------------------------------------------------
# Variables to modify
#--------------------------------------------------------------------------------

CONTENT_DIR = content
THEME_DIR   = rchrd

OUTPUT_DIR  = /Users/richard/Sites/net.rchrd.dotdotslash/dotdotslash.rchrd.net/sites/portfolio.rchrd.net/public/
SERVER_DIR  = rcaceres@portfolio.rchrd.net:/home/rcaceres/sites/net.rchrd.portfolio/public/

#--------------------------------------------------------------------------------
# Targets are defined below this line.
#--------------------------------------------------------------------------------

__DIR__  = `pwd`
__SYNC_TEMPLATE__ = --checksum -a -v $(OUTPUT_DIR) $(SERVER_DIR)

#--------------------------------------------------------------------------------
# For building
#--------------------------------------------------------------------------------

main : 
	@echo 'Compiling html and media.'
	@php tool.php -m

html : 
	@echo 'Only compiling html.'
	@php tool.php

clear :
	rm -r $(OUTPUT_DIR)/*

all :
	@echo 'The all target is disabled.'

publish : main sync

#--------------------------------------------------------------------------------
# For syncing to server
#--------------------------------------------------------------------------------
sync-test :
	rsync --dry-run $(__SYNC_TEMPLATE__)

sync :
	rsync $(__SYNC_TEMPLATE__)

sync-test-delete :
	rsync  --dry-run --delete $(__SYNC_TEMPLATE__)

sync-delete :
	rsync --delete $(__SYNC_TEMPLATE__)

#--------------------------------------------------------------------------------
# make tests
#--------------------------------------------------------------------------------

curr_dir :
	@echo $(__DIR__)

var :
	@echo '$(CONTENT_DIR)'
	
multiple : curr_dir var