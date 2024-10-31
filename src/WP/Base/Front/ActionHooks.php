<?php

namespace ResponsivePagination\WP\Base\Front;

/**
 * WordPress Action Hooks in Front side.
 * All hooks in this class operates after 'wp' hooks
 */
class ActionHooks
{
    /**
     * Whether this Action Hooks already registered or not
     *
     * @var boolean
     */
    private static $registered = false;

    public static function register()
    {
        if (!self::$registered) {
            new self();
        }
        self::$registered = true;
    }

    private function __construct()
    {
        add_action('wp', function () {
            $this->enqueueScripts();
            $this->wpHead();
            $this->loopEnd();
            $this->wpFooter();
            $this->wpPrintFooterScripts();
        });
    }

    protected function enqueueScripts()
    {
        wp_enqueue_script("jquery");
    }

    protected function wpHead()
    {
        add_action('wp_head', function () {
            // init javascript object
            printf('<script>window._respg = new Object(); window._respg.initialValue = new Object(); window.respg = new Object();</script>');
            
            // Pass pagination settings & breakpoint initial value
            $initialPaginationSettings = respg()->proxyPaginationSettings->initialValue();
            $initialBreakpoint = respg()->proxyPaginationSettings->initialBreakpointValue();
            printf('<script>window._respg.initialValue.paginationSettings = %s; window._respg.initialValue.breakpoint = %s;</script>', json_encode($initialPaginationSettings['default']), json_encode($initialBreakpoint));
        });
    }

    protected function loopEnd()
    {
        add_action('loop_end', function () {
            ConversionRegistrator::register();
        });
    }

    protected function wpFooter()
    {
        add_action('wp_footer', function () {
        });
    }

    protected function wpPrintFooterScripts()
    {
        add_action('wp_print_footer_scripts', function () {
            VisualStyleApplicator::apply();
            BreakpointStyleApplicator::apply();
            ResponsivenessApplicator::apply();
            PaginationApplicator::apply();

            // we've already enqueue jquery
            $frontMainScript = apply_filters(
                'respg_front_main_script',
                respg()->isPublic ?
                    respg_url('assets/js/front.wp.respg.min.js?v='.respg()->version) :
                    respg_url(sprintf('assets/%s/js/front.wp.respg.js?h=%s', respg()->assetsNamespace, uniqid()))
            );
            printf('<script src="%s"></script>', $frontMainScript);

            do_action('respg_front_print_scripts');
        });
    }
}
