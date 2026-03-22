<?php

namespace Geo_Posts;

defined( 'ABSPATH' ) || die();

const OPT_GOOGLE_API_KEY = 'ppgp_google_api_key';
const OPT_GEO_POST_TYPES = 'ppgp_geo_post_types';

const GMAP_CLASS = 'ppgp-map';

const META_LAT = 'geo_lat';
const META_LON = 'geo_lon';

const DEFAULT_MAP_ZOOM = 5;
const DEFAULT_MAP_LAT  = 54;
const DEFAULT_MAP_LON  = -3;

const DEFAULT_GEO_POST_TYPES = array( 'post' );

const META_IS_GEO_POST = 'geo_enabled';
const META_GEO_MODE    = 'geo_mode';

const DEFAULT_GEO_MODE = 'latlon';
