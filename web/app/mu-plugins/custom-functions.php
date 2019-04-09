<?php

/**
 * Plugin Name: Custom functions overriding
 * Plugin URI:
 * Description: Allow forcing correct configuration for Scaleway compliant S3 Api
 * Version: 1.0.0
 * Author: Tristan Bessoussa
 * Author URI: https://github.com/tristanbes
 * License: Proprietary
 */
use Roots\WPConfig\Config;

if (Config::get('S3_UPLOADS_BUCKET_ENDPOINT_URL')) {
    // Filter S3 Uploads params.
    add_filter('s3_uploads_s3_client_params', function ($params) {
        $params['endpoint'] = Config::get('S3_UPLOADS_BUCKET_ENDPOINT_URL');

        return $params;
    });

    // Filter S3 Uploads params.
    add_filter('s3_uploads_bucket_url', function ($bucketUrl) {
        $url = parse_url(Config::get('S3_UPLOADS_BUCKET_ENDPOINT_URL'));
        $protocol = $url['scheme'];
        $domain = $url['host'];

        $bucket = Config::get('S3_UPLOADS_BUCKET');
        $bucket = strtok($bucket, '/');
        $path = substr($bucket, strlen($bucket));

        return $protocol.'://'.$bucket.'.'.$domain.$path;
    });
}
