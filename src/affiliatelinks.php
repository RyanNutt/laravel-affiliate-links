<?php

return [
    
    /* If true, any matching affiliate tags in the original URL will 
     * be replaced by your tags.
     * 
     * For example, if you are appending 'tag=123&other=345' to matching
     * URLs and the target URL is example.com?s=1&tag=234 then it will
     * be replaced with example.com?s=1&tag=123&other=345 if this is
     * set to true or example.com?s=1&tag=234&other=345 if set to false. 
     */
    'replace_existing' => true,
    
    /* Environments where the links should be replaces.
     * 
     * Leave empty to replace in all environments. 
     * 
     * If you only want this to replace in certain environments, fill
     * the array with the environment names. Ex: if you only want to 
     * run in production - 'environments' => ['production']
     * You can list multiple - 'environments' => ['production', 'staging']
     */ 
    'environments' => [],
    
    /* List of domains that should have your tags applied.
     * 
     * The key for this is the domain to look for, case insensitive and
     * without a leading www. The value for the array is the tag or tags
     * you want to append to the link. 
     * 
     * If you have multiple tags separate them with & symbols. 
     */
    'domains' => [
        // 'example.com' => 'tag&123&other=345'
    ]
];