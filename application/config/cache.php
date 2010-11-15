<?php
defined('BASE') or exit('Access Denied!');
/*
| -------------------------------------------------------------------------
| OUTPUT CACHE AND COMPRESS SETTINGS
| -------------------------------------------------------------------------
| Please see the user guide for complete details:
|
| Chapters / General Topics / Caching
|
*/                  

/*
|--------------------------------------------------------------------------
| Output Cache Directory Path
|--------------------------------------------------------------------------
|
| Leave this BLANK unless you would like to set something other than the
| default  " application/system/cache/ " folder. 
| 
| Use a full server path with trailing slash.
|
*/
$cache['cache_path']             = '';

/*
|--------------------------------------------------------------------------
| Output Compression
|--------------------------------------------------------------------------
|
| Enables Gzip output compression for faster page loads.  When enabled,
| the output class will test whether your server supports Gzip.
| Even if it does, however, not all browsers support compression
| so enable only if you are reasonably sure your visitors can handle it.
|
| VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
| means you are prematurely outputting something to your browser. It could
| even be a line of whitespace at the end of one of your scripts.  For
| compression to work, nothing can be sent before the output buffer is called
| by the output class.  Do not "echo" any values with compression enabled.
|
*/
$cache['compress_output']       = FALSE;

/*
|--------------------------------------------------------------------------
| Output Compression Level
|--------------------------------------------------------------------------
| Set your Gzip compression level
|
*/
$cache['compression_level']     = 8;

/* End of file cache.php */
/* Location: ./application/config/cache.php */