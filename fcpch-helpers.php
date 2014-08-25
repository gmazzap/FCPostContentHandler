<?php namespace GM\FCPCH;

function custom_init_fragment_cache() {
    global $fragment_cache;
    if ( ! $fragment_cache instanceof \Rarst\Fragment_Cache\Plugin ) {
        if ( ! defined( 'FRAGMENT_CACHE_NOT_INSTALLED' ) ) {
            define( 'FRAGMENT_CACHE_NOT_INSTALLED', TRUE );
        }
        $fragment_cache = new \Rarst\Fragment_Cache\Plugin(
            [ 'timeout' => HOUR_IN_SECONDS, 'update_server' => new \TLC_Transient_Update_Server ]
        );
        $fragment_cache->run();
    }
    if ( ! defined( 'FRAGMENT_CACHE_WORKS' ) ) {
        define( 'FRAGMENT_CACHE_WORKS', $fragment_cache instanceof \Rarst\Fragment_Cache\Plugin );
    }
}

function registerServices() {
    if ( ! FRAGMENT_CACHE_WORKS ) {
        return;
    }
    global $fragment_cache;
    $fragment_cache['fcpch_metabox'] = function( $container ) {
        return new Metabox( $container['fcpch_set_field_capability'] );
    };
    $fragment_cache['fcpch_settings'] = function() {
        return new Settings\Settings;
    };
    $fragment_cache['fcpch_set_section_core'] = function() {
        return new Settings\Core;
    };
    $fragment_cache['fcpch_set_section_postcontent'] = function() {
        return new Settings\PostContent;
    };
    $fragment_cache['fcpch_set_field_capability'] = function() {
        return new Settings\Fields\Capability;
    };
    $fragment_cache['fcpch_set_field_coreallow'] = function() {
        return new Settings\Fields\CoreAllow;
    };
    $fragment_cache['fcpch_set_field_posttypes'] = function() {
        return new Settings\Fields\PostTypes;
    };
}

function fcpch( $service = NULL ) {
    if ( FRAGMENT_CACHE_WORKS ) {
        global $fragment_cache;
        if ( is_null( $service ) ) {
            return $fragment_cache;
        } elseif ( isset( $fragment_cache["fcpch_{$service}"] ) ) {
            return $fragment_cache["fcpch_{$service}"];
        }
    }
}

function registerCoreHandlers() {
    if (
        ! FRAGMENT_CACHE_WORKS
        || ! defined( 'FRAGMENT_CACHE_NOT_INSTALLED' )
        || ! FRAGMENT_CACHE_NOT_INSTALLED
    ) {
        return;
    }
    $core = (array) get_option( 'fcph_core_allow' );
    if ( ! empty( $core ) ) {
        foreach ( $core as $handler ) {
            $class = 'Rarst\\Fragment_Cache\\' . ucfirst( $handler ) . '_Cache';
            fcpch()->add_fragment_handler( $handler, $class );
        }
    }
}
