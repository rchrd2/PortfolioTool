
    __/\\\\\\\\\\\\\\\______________________________/\\\\\\____        
     _\///////\\\/////______________________________\////\\\____       
      _______\/\\\______________________________________\/\\\____      
       _______\/\\\___________/\\\\\________/\\\\\_______\/\\\____     
        _______\/\\\_________/\\\///\\\____/\\\///\\\_____\/\\\____    
         _______\/\\\________/\\\__\//\\\__/\\\__\//\\\____\/\\\____   
          _______\/\\\_______\//\\\__/\\\__\//\\\__/\\\_____\/\\\____  
           _______\/\\\________\///\\\\\/____\///\\\\\/____/\\\\\\\\\_ 
            _______\///___________\/////________\/////_____\/////////__
                                                                                   
,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,

DESCRIPTION:

This tool parses content in folders and generates a static html portfolio site.

Some configuration is required. See the makefile and config.php for variables.

Note some functionality might not work because of external library dependencies.
Please file issues in the forum so we can address these as they come up.

Please see the following site to get an example of the possiblities 
http://portfolio.rchrd.net/. More media will be added in the future.

Enjoy!

,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,

GETTING STARTED:

cd into the tool directory. Run the following command first:
	> make html 
If that works you should have generated html in example/output, then you can try
to also generate the media with this make command:
	> make 
This will have resized images and copied them over into the output directory. 
This is the most used command.

Later you can try using the sync command to sync this to your server. But first 
make sure to modify the makefile to include the proper url:
	> make sync

,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,

SITES USING THIS TOOL:

http://portfolio.rchrd.net/

,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,

RELEASE NOTES: 

01/22/13 -- Pushed to Github®

,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,

AUTHOR:

Richard Caceres <me@rchrd.net>
Copyright Richard Caceres, 2012
http://github.com/rcaceres/
http://rchrd.net

Note: Ascii art generated at http://patorjk.com/software/taag/


,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,

TODO:

- Reduce the amount of configuration necessary.
- Add an example directory of data to be used for testing.

,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,,

OPEN SOURCE MIT LICENSE:

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.

