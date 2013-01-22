#--------------------------------------------------------------------------------
# This is a make file (see <http://en.wikipedia.org/wiki/Make_(software)>)
# @author Richard Caceres
#--------------------------------------------------------------------------------

#--------------------------------------------------------------------------------
# Variables to modify
#--------------------------------------------------------------------------------

# These are used for the "clear" and "sync" commands
OUTPUT_DIR  = /Users/richard/Sites/net.rchrd.dotdotslash/dotdotslash.rchrd.net/sites/portfolio.rchrd.net/tool/example/output
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

# Note if you want to avoid typing 'y' all the time, run it the following way:
# bash> yes | make clear
clear :
	rm -r -v -i $(OUTPUT_DIR)/*

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
# make tests (these were just for my testing purposes)
#--------------------------------------------------------------------------------

curr_dir :
	@echo $(__DIR__)

	
multiple : curr_dir var