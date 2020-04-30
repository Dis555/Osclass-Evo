<?php if ( ! defined('OC_ADMIN')) exit('Direct access is not allowed.');

osc_add_hook('ajax_admin_compactmode','modern_compactmode_actions');
function modern_compactmode_actions(){
    $compactMode = osc_get_preference('compact_mode','modern_admin_theme');
    
    $modeStatus  = array('compact_mode'=>true);
    
    if($compactMode == true){
        $modeStatus['compact_mode'] = false;
    }
    
    osc_set_preference('compact_mode', $modeStatus['compact_mode'], 'modern_admin_theme');
}

osc_add_hook('ajax_admin_sbfilters','sidebar_filters_change');
function sidebar_filters_change() {
    $color = Params::getParam('color');
    
    osc_set_preference('sidebar_filters', $color, 'osclass');
}

osc_add_hook('ajax_admin_sbbackground','sidebar_background_change');
function sidebar_background_change() {
    $color = Params::getParam('color');
    
    osc_set_preference('sidebar_background', $color, 'osclass');
}

osc_add_hook('ajax_admin_sbimage_show','sidebar_image_status');
function sidebar_image_status() {
    $status = Params::getParam('img_status');

    osc_set_preference('sidebar_image_show', $status, 'osclass');
}

osc_add_hook('ajax_admin_sbimage','sidebar_image');
function sidebar_image() {
    $img = Params::getParam('img');

    osc_set_preference('sidebar_image', $img, 'osclass');
}

// favicons
function admin_header_favicons() {
    $favicons   = array();
    $favicons[] = array(
        'rel'   => 'shortcut icon',
        'sizes' => '',
        'href'  => osc_current_admin_theme_url('images/favicon-48.png')
    );
    $favicons[] = array(
        'rel'   => 'apple-touch-icon-precomposed',
        'sizes' => '144x144',
        'href'  => osc_current_admin_theme_url('images/favicon-144.png')
    );
    $favicons[] = array(
        'rel'   => 'apple-touch-icon-precomposed',
        'sizes' => '114x114',
        'href'  => osc_current_admin_theme_url('images/favicon-114.png')
    );
    $favicons[] = array(
        'rel'   => 'apple-touch-icon-precomposed',
        'sizes' => '72x72',
        'href'  => osc_current_admin_theme_url('images/favicon-72.png')
    );
    $favicons[] = array(
        'rel'   => 'apple-touch-icon-precomposed',
        'sizes' => '',
        'href'  => osc_current_admin_theme_url('images/favicon-57.png')
    );

    $favicons = osc_apply_filter('admin_favicons', $favicons);

    foreach($favicons as $f) { ?>
        <link <?php if($f['rel'] !== '') { ?>rel="<?php echo $f['rel']; ?>" <?php } if($f['sizes'] !== '') { ?>sizes="<?php echo $f['sizes']; ?>" <?php } ?>href="<?php echo $f['href']; ?>">
    <?php }
}
osc_add_hook('admin_header', 'admin_header_favicons');

// admin footer
function admin_footer_html() { ?>
    <footer class="footer">
        <div class="container-fluid">
            <nav class="float-xl-left">
                <ul class="text-center text-xl-left">
                    <li>
                        <a href="https://forum.osclass-evo.com/" title="<?php _e('Forum'); ?>" target="_blank">
                            <?php _e('Forum'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://osclass.market/" title="<?php _e('Osclass Market'); ?>" target="_blank">
                            <?php _e('Osclass Market'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="https://osclass-evo.com/docs" title="<?php _e('Documentation'); ?>" target="_blank">
                            <?php _e('Documentation'); ?>
                        </a>
                    </li>
                    <li>
                        <a id="ninja" href="javascript:;" title="<?php _e('Donate to us'); ?>">
                            <?php _e('Donate to us'); ?>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="copyright float--xl-right text-center text-xl-right mb-3 mb-xl-0">
                &copy;
                <script>
                document.write(new Date().getFullYear())
                </script>, <strong>Osclass Evolution v. <?php echo preg_replace('|.0$|', '', OSCLASS_VERSION); ?></strong> 
                <?php printf(__('made with %s for a better web by <a href="%s" target="_blank">Osclass Evolution Team</a>'), '<i class="material-icons">favorite</i>', 'https://osclass-evo.com/'); ?>
            </div>
        </div>
    </footer>
    
    <form id="donate-form" name="_xclick" action="https://www.paypal.com/in/cgi-bin/webscr" method="post" target="_blank">
       <input type="hidden" name="cmd" value="_donations">
       <input type="hidden" name="business" value="donate@osclass.market">
       <input type="hidden" name="item_name" value="Osclass Evolution">
       <input type="hidden" name="return" value="<?php echo osc_admin_base_url(); ?>">
       <input type="hidden" name="currency_code" value="USD">
       <input type="hidden" name="lc" value="US" />
    </form>
    
    <script type="text/javascript">
        $('body').on('click', 'a#ninja',function(){
            jQuery('#donate-form').submit();
            return false;
        });
    </script>
<?php
}
osc_add_hook('admin_content_footer', 'admin_footer_html');

// scripts
function admin_theme_js() {
    osc_load_scripts();
}
osc_add_hook('admin_header', 'admin_theme_js', 9);

// css
function admin_theme_css() {
    osc_load_styles();
}
osc_add_hook('admin_header', 'admin_theme_css', 9);

function printLocaleTabs($locales = null) {
    if($locales == null) { $locales = osc_get_locales(); }
    $num_locales = count($locales);

    if($num_locales>1) {
        echo '<ul id="language-tab" class="nav nav-pills nav-pills-rose" role="tablist">';
            foreach($locales as $i => $locale) {
                $active = '';

                if(osc_language() == $locale['pk_c_code']) { $active = 'active'; }

                echo '<li class="nav-item"><a class="nav-link ' . $active . '" data-toggle="tab" role="tablist" href="#'.$locale['pk_c_code'].'">'.$locale['s_name'].'</a></li>';
            }
        echo '</ul>';
    }
}

function printLocaleTitle($locales = null, $item = null) {
    if($locales == null) { $locales = osc_get_locales(); }
    if($item == null) { $item = osc_item(); }

    foreach($locales as $i => $locale) {
        $hidden = '';
        $title = (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_title'])) ? $item['locale'][$locale['pk_c_code']]['s_title'] : '';

        if( Session::newInstance()->_getForm('title') != "" ) {
            $title_ = Session::newInstance()->_getForm('title');

            if( $title_[$locale['pk_c_code']] != "" ){
                $title = $title_[$locale['pk_c_code']];
            }
        }

//        if($i) { $hidden = 'fc-limited'; }
        if(osc_language() != $locale['pk_c_code']) { $hidden = 'fc-limited'; }

        $title = osc_apply_filter('admin_item_title', $title, $item, $locale);
        $name = 'title'. '[' . $locale['pk_c_code'] . ']';

        echo '<div id="' . $name . '" class="row no-gutters multilang ' . $hidden . '">';
        echo '<div class="col-lg-12">';
        echo '<div class="form-group">';
        echo '<label for="' . $name . '" class="bmd-label-floating">' . __('Title') . '</label>';
        echo '<input id="' . $name . '" class="form-control w-100" type="text" name="' . $name . '" value="' . osc_esc_html(htmlentities($title, ENT_COMPAT, "UTF-8")) . '"  />';
        echo '</div></div></div>';
    }
}

function printLocaleTitlePage($locales = null, $page = null) {
    if($locales == null) { $locales = osc_get_locales(); }

    $aFieldsDescription = Session::newInstance()->_getForm("aFieldsDescription");

    foreach($locales as $i => $locale) {
        $hidden = '';
        $name = $locale['pk_c_code'] . '#s_title';

//        if($i) { $hidden = 'fc-limited'; }
        if(osc_language() != $locale['pk_c_code']) { $hidden = 'fc-limited'; }

        echo '<div id="' . $name . '" class="row no-gutters multilang ' . $hidden . '"><label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right">' . __('Title') . ' *</label>';

        $title = '';

        if(isset($page['locale'][$locale['pk_c_code']])) {
            $title = $page['locale'][$locale['pk_c_code']]['s_title'];
        }

        if( isset($aFieldsDescription[$locale['pk_c_code']]) && isset($aFieldsDescription[$locale['pk_c_code']]['s_title']) && $aFieldsDescription[$locale['pk_c_code']]['s_title'] != '' ) {
            $title = $aFieldsDescription[$locale['pk_c_code']]['s_title'];
        }

        $title = osc_apply_filter('admin_page_title', $title, $page, $locale);

        echo '<div class="col-xl-5"><div class="form-group"><input class="form-control w-100 w-xl-75" type="text" name="' . $name . '" value="' . osc_esc_html(htmlentities($title, ENT_COMPAT, "UTF-8")) . '" /></div></div>';
        echo '</div>';
    }
}

function printLocaleDescription($locales = null, $item = null) {
    if($locales == null) { $locales = osc_get_locales(); }
    if($item == null) { $item = osc_item(); }

    foreach($locales as $i => $locale) {
        $hidden = '';
        $name = 'description'. '[' . $locale['pk_c_code'] . ']';

//        echo '<div><label for="description">' . __('Description') . ' *</label>';
        $description = (isset($item) && isset($item['locale'][$locale['pk_c_code']]) && isset($item['locale'][$locale['pk_c_code']]['s_description'])) ? $item['locale'][$locale['pk_c_code']]['s_description'] : '';

        if( Session::newInstance()->_getForm('description') != "" ) {
            $description_ = Session::newInstance()->_getForm('description');

            if( $description_[$locale['pk_c_code']] != "" ){
                $description = $description_[$locale['pk_c_code']];
            }
        }

//        if($i) { $hidden = 'fc-limited'; }
        if(osc_language() != $locale['pk_c_code']) { $hidden = 'fc-limited'; }

        $description = osc_apply_filter('admin_item_description', $description, $item, $locale);

        echo '<div id="' . $name . '" class="row no-gutters multilang ' . $hidden . '">';
        echo '<div class="col-lg-12">';
        echo '<div class="form-group">';
        echo '<label for="' . $name . '" class="bmd-label-floating">' . __('Description') . '</label>';
        echo '<textarea id="' . $name . '"  class="form-control w-100 h-75" name="' . $name . '" rows="10">' . $description . '</textarea>';
        echo '</div></div></div>';
    }
}

function printLocaleDescriptionPage($locales = null, $page = null) {
    if($locales == null) { $locales = osc_get_locales(); }
    $aFieldsDescription = Session::newInstance()->_getForm("aFieldsDescription");
    $num_locales = count($locales);

    foreach($locales as $i => $locale) {
        $hidden = '';
        $description = '';

//        if($i) { $hidden = 'fc-limited'; }
        if(osc_language() != $locale['pk_c_code']) { $hidden = 'fc-limited'; }

        if(isset($page['locale'][$locale['pk_c_code']])) {
            $description = $page['locale'][$locale['pk_c_code']]['s_text'];
        }

        if( isset($aFieldsDescription[$locale['pk_c_code']]) && isset($aFieldsDescription[$locale['pk_c_code']]['s_text']) &&$aFieldsDescription[$locale['pk_c_code']]['s_text'] != '' ) {
            $description = $aFieldsDescription[$locale['pk_c_code']]['s_text'];
        }

        $description = osc_apply_filter('admin_page_description', $description, $page, $locale);

        $name = $locale['pk_c_code'] . '#s_text';
        echo '<div id="' . $name . '" class="row no-gutters multilang ' . $hidden . '"><label class="col-12 col-xl-1 col-form-label form-label text-left text-xl-right">' . __('Description') . ' *</label>';
        echo '<div class="col-xl-5"><div class="form-group"><textarea id="' . $name . '" name="' . $name . '" rows="10">' . $description . '</textarea></div></div></div>';
    }
}

function drawMarketItem($item,$color = false){
    //constants
    $updateClass      = '';
    $updateData       = '';
    $thumbnail        = false;
    $featuredClass    = '';
    $style            = '';
    $letterDraw       = '';
    $compatible       = '';
    $type             = strtolower($item['e_type']);
    $items_to_update  = json_decode(osc_get_preference($type.'s_to_update'),true);
    $items_downloaded = json_decode(osc_get_preference($type.'s_downloaded'),true);

    if($item['s_thumbnail']){
        $thumbnail = $item['s_thumbnail'];
    }
    if($item['s_banner']){
        if(@$item['s_banner_path']!=''){
            $thumbnail = $item['s_banner_path'] . $item['s_banner'];
        } else {
            $thumbnail = 'http://market.osclass.org/oc-content/uploads/market/'.$item['s_banner'];
        }
    }

    $downloaded = false;
    if(is_array($items_downloaded) && in_array($item['s_update_url'], $items_downloaded)) {
        if (in_array($item['s_update_url'], $items_to_update)) {
            $updateClass = 'has-update';
            $updateData  = ' data-update="true"';
        } else {
            // market item downloaded !
            $downloaded = true;
        }
    }

    //Check if is compatibleosc_version()
    if($type=='language') {
        if(!check_market_language_compatibility($item['s_update_url'], $item['s_version'])){
            $compatible = ' not-compatible';
        }
    } else {
        if(!check_market_compatibility($item['s_compatible'],$type)){
            $compatible = ' not-compatible';
        }
    }


    if(!$thumbnail && $color){
        $thumbnail = osc_current_admin_theme_url('images/gr-'.$color.'.png');
        $letterDraw = $item['s_update_url'][0];
        if($type == 'language'){
            $letterDraw = $item['s_update_url'];
        }
    }
    if ($item['b_featured']) {
        $featuredClass = ' is-featured';
        if($downloaded || $updateClass){
            $featuredClass .= '-';
        }
    }
    if($downloaded) {
        $featuredClass .= 'is-downloaded';
    }

    $buyClass = '';
    if($item['i_price'] != '' && (float)$item['i_price'] > 0  && $item['b_paid'] == 1) {
        $buyClass = ' is-buy ';
    }

        $style = 'background-image:url('.$thumbnail.');';
    echo '<a href="#'.$item['s_update_url'].'" class="mk-item-parent '.$featuredClass.$updateClass.$compatible.$buyClass.'" data-type="'.$type.'"'.$updateData.' data-gr="'.$color.'" data-letter="'.$item['s_update_url'][0].'">';
    echo '<div class="mk-item mk-item-'.$type.'">';
    echo '    <div class="banner" style="'.$style.'">'.$letterDraw.'</div>';
    echo '    <div class="mk-info"><i class="flag"></i>';
    echo '        <h3>'.$item['s_title'].'</h3>';
    echo '        <span class="downloads"><strong>'.$item['i_total_downloads'].'</strong> '.__('downloads').'</span>';
    echo '        <i class="author">by '.$item['s_contact_name'].'</i>';
    echo '        <div class="market-actions">';
    echo '            <span class="more">'.__('View more').'</span>';
    if($item['i_price'] != '' && (float)$item['i_price'] > 0 && $item['b_paid'] == 0) {
        echo '            <span class="buy-btn' . $compatible . '" data-code="' . $item['s_buy_url'] . '" data-type="' . $type . '"' . '>' . sprintf(__('Buy $%s'), number_format($item['i_price']/1000000, 0, '.', ',')) . '</span>';
    } else {
        echo '            <span class="download-btn' . $compatible . '" data-code="' . $item['s_update_url'] . '" data-type="' . $type . '"' . '>' . __('Download') . '</span>';
    }
    echo '        </div>';
    echo '    </div>';
    echo '</div>';
    echo '</a>';
}

function check_market_language_compatibility($slug, $language_version) {
    return osc_check_language_update($slug);
}

function check_market_compatibility($versions) {
    $versions = explode(',',$versions);
    $current_version = OSCLASS_VERSION;

    foreach($versions as $_version) {
        $result = version_compare2(OSCLASS_VERSION, $_version);

        if( $result == 0 || $result == -1 ) {
            return true;
        }
    }
    return false;
}

function add_market_jsvariables(){
    $marketPage = Params::getParam("mPage");
    $version_length = strlen(osc_version());
    $main_version = substr(osc_version(),0, $version_length-2).".".substr(osc_version(),$version_length-2, 1);


    if($marketPage>=1) $marketPage--;
    $action = Params::getParam("action");

    $js_lang = array(
        'by'                 => __('by'),
        'ok'                 => __('Ok'),
        'error_item'         => __('There was a problem, try again later please'),
        'wait_download'      => __('Please wait until the download is completed'),
        'downloading'        => __('Downloading'),
        'close'              => __('Close'),
        'download'           => __('Download'),
        'update'             => __('Update'),
        'last_update'        => __('Last update'),
        'downloads'          => __('Downloads'),
        'requieres_version'  => __('Requires at least'),
        'compatible_with'    => __('Compatible up to'),
        'screenshots'        => __('Screenshots'),
        'preview_theme'      => __('Preview theme'),
        'download_manually'  => __('Download manually'),
        'buy'                => __('Buy'),
        'proceed_anyway'     => sprintf(__('Warning! This package is not compatible with your current version of Osclass (%s)'), $main_version),
        'sure'               => __('Are you sure?'),
        'proceed_anyway_btn' => __('Ok, proceed anyway'),
        'not_compatible'     => sprintf(__('Warning! This theme is not compatible with your current version of Osclass (%s)'), $main_version),
        'themes'             => array(
            'download_ok' => __('The theme has been downloaded correctly, proceed to activate or preview it.')
        ),
        'plugins'            => array(
            'download_ok' => __('The plugin has been downloaded correctly, proceed to install and configure.')
        ),
        'languages'          => array(
            'download_ok' => __('The language has been downloaded correctly, proceed to activate.')
        )

    );
    ?>
    <script type="text/javascript">
        var theme = window.theme || {};
        theme.adminBaseUrl  = "<?php echo osc_admin_base_url(true); ?>";
        theme.marketAjaxUrl = "<?php echo osc_admin_base_url(true); ?>?page=ajax&action=market&<?php echo osc_csrf_token_url(); ?>";
        theme.marketCurrentURL = "<?php echo osc_admin_base_url(true); ?>?page=market&action=<?php echo Params::getParam('action'); ?>";
        theme.themUrl       = "<?php echo osc_current_admin_theme_url(); ?>";
        theme.langs         = <?php echo json_encode($js_lang); ?>;
        theme.CSRFToken     = "<?php echo osc_csrf_token_url(); ?>";

        var osc_market = {};
        osc_market.main_version = <?php echo $main_version; ?>;

    </script>
    <?php
}

function check_version_admin_footer() {
    if( (time() - osc_last_version_check()) > (24 * 3600) ) {
        ?>
        <script type="text/javascript">
            $(document).ready(function() {
                $.getJSON(
                    '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_version',
                    {},
                    function(data){}
                );
            });
        </script>
        <?php
    }
}
osc_add_hook('admin_footer', 'check_version_admin_footer');

function check_languages_admin_footer() {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.getJSON(
                '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_languages',
                {},
                function(data){}
            );
        });
    </script>
<?php
}

function check_themes_admin_footer() {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.getJSON(
                '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_themes',
                {},
                function(data){}
            );
        });
    </script>
<?php
}

function check_plugins_admin_footer() {
    ?>
    <script type="text/javascript">
        $(document).ready(function() {
            $.getJSON(
                '<?php echo osc_admin_base_url(true); ?>?page=ajax&action=check_plugins',
                {},
                function(data){}
            );
        });
    </script>
<?php
}

/* end of file */
