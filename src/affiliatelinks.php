<?php

return [
    
    /* Environments where the links should be replaced.
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